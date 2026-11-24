<?php

declare(strict_types=1);

namespace Janolaw\Janolawservice\Controller;

use Janolaw\Janolawservice\Domain\Model\JanolawService;
use Janolaw\Janolawservice\Domain\Repository\JanolawServiceRepository;
use Janolaw\Janolawservice\Utility\JanolawConfigurationUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\NormalizedParams;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * JanolawServiceController
 */
class JanolawServiceController extends ActionController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly FrontendInterface $cache,
        private readonly PersistenceManager $persistenceManager,
        private readonly JanolawConfigurationUtility $configUtily,
        private readonly RequestFactory $requestFactory,
        private readonly JanolawServiceRepository $janolawServiceRepository,
        private readonly ExtensionConfiguration $extensionConfiguration
    ) {}

    /**
     * action generate
     */
    public function generateAction(): ResponseInterface
    {
        $settings = $this->settings['janolawservice'] ?? [];
        $language = (string)($settings['language'] ?? 'de');
        $type = (string)($settings['type'] ?? '');
        $pdflink = (string)($settings['pdflink'] ?? 'no_pdf');
        $userid = $settings['userid'] ?? 0;
        $shopid = $settings['shopid'] ?? 0;
        $janolawContent = $this->getJanolawContent(
            $type,
            $language,
            $pdflink,
            $userid,
            $shopid
        );
        $this->view->assign('janolawContent', $janolawContent);
        return $this->htmlResponse();
    }

    /**
     * @throws UnknownObjectException
     * @throws IllegalObjectTypeException
     */
    public function getJanolawContent(
        $type,
        $language = 'de',
        $pdf = 'no_pdf',
        $userid = 0,
        $shopid = 0
    ) {
        $debugMessage = '';
        list($lifetime, $userid, $shopid) = $this->getExtConfigSettings($userid, $shopid);

        // create Cache Identifier
        $cacheIdentifier = md5(
            $language . '-' . $type . '-' . $userid . '-' . $shopid . '-' . $pdf
        );
        $content = $this->cache->get($cacheIdentifier);

        //if there is no valid cache content
        if ($content === false || $content === null) {
            $validUserData = $this->configUtily->hasValidUserData($userid, $shopid);
            $validVersion = $this->configUtily->janolawGetVersion($userid, $shopid, $debugMessage);
            if (!($language === 'de') && !($validVersion === '3m')) {
                // only in Version '3m' other languages are allowed, this parameter combination is invalid
                $validUserData = false;
            }

            /** @var JanolawService $janolawService */
            $janolawService = $this->getJanolawService(
                $language,
                $type,
                $userid,
                $shopid,
                $pdf
            );

            if (
                $validUserData && (($validVersion == 1) || ($validVersion == 2) ||
                                   ($validVersion == 3) || ($validVersion == '3m'))
            ) {
                $debugMessage .= 'No Cache exists - generate for Identifier  '
                                 . $cacheIdentifier . ' and lifetime in seconds ' . $lifetime;
                $content = $this->configUtily->janolawGetContent(
                    $validVersion,
                    $language,
                    $type,
                    $userid,
                    $shopid,
                    $debugMessage,
                    $pdf
                );
                if (is_string($content) && $content !== '') {
                    $this->cache->set(
                        $cacheIdentifier,
                        $content,
                        $this->getCacheTags($language, $type, $userid, $shopid, $pdf),
                        $lifetime
                    );
                    //set new content to database, fallback if connection to external janolaw server fails
                    $janolawService->setExternal($content);
                    $this->janolawServiceRepository->update($janolawService);
                    $this->persistenceManager->persistAll();
                }
            }
            if (!$content) {
                //no content from external server, get value from database and set it to cache
                $content = $janolawService->getExternal();
                if ($content) {
                    $debugMessage .= 'No valid return from external server, use latest value from
                    database and set this to cache';
                    $this->cache->set(
                        $cacheIdentifier,
                        $content,
                        $this->getCacheTags($language, $type, $userid, $shopid, $pdf),
                        $lifetime
                    );
                }
            }
        }
        return $content;
    }

    private function getJanolawService(
        $language,
        $type,
        $userid,
        $shopid,
        $pdf = 'no_pdf'
    ): object {
        $query = $this->janolawServiceRepository->findByJanolawServiceParams(
            $language,
            $type,
            $userid,
            $shopid,
            $pdf
        );
        if ($query != null && $query->count() >= 1) {
            return $query->getFirst();
        }

        //DB Entry did not exist - create
        return $this->createJanolawService($language, $type, $userid, $shopid, $pdf);
    }

    private function createJanolawService($language, $type, $userid, $shopid, $pdf): object
    {
        $janolawServiceModel = new JanolawService();
        $janolawServiceModel->setLegacyLanguage($language);
        $janolawServiceModel->setType($type);
        $janolawServiceModel->setUserId((int)$userid);
        $janolawServiceModel->setShopId((int)$shopid);
        $janolawServiceModel->setPdf($pdf);
        try {
            $this->janolawServiceRepository->add($janolawServiceModel);
            $this->persistenceManager->persistAll();
            $url = 'https://api.janolaw.de/pluginsettings';
            $jsonData = [
                'userID' => $userid,
                'shopID' => $shopid,
                'plugin' => 'TYPO3',
                'domain' => $this->getRequestUrl(),
                'pluginversion' => ExtensionManagementUtility::getExtensionVersion(
                    'janolawservice'
                ),
                'cmsversion' => VersionNumberUtility::getCurrentTypo3Version(),
                'settings' => '<b>Language:</b> ' . $language . '<br/><b>Type:</b> '
                              . $type . '<br/><b>PDF:</b> ' . $pdf,
                'misc' => '',
            ];
            $jsonDataEncoded = (string)json_encode($jsonData);
            $additionalOptions = [
                'body' => $jsonDataEncoded,
                // OR form data:
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Content-Length' => strlen($jsonDataEncoded),
                ],
            ];

            try {
                $this->requestFactory->request($url, 'POST', $additionalOptions);
            } catch (\Throwable $e) {
                // registration at janolaw is informational only, rendering must not fail because of it
                $this->logger?->warning(
                    'janolaw plugin registration for user {userid} / shop {shopid} failed: {error}',
                    [
                        'userid' => $userid,
                        'shopid' => $shopid,
                        'error' => $e->getMessage(),
                        'url' => $url,
                    ]
                );
            }
        } catch (IllegalObjectTypeException) {
        }

        return $janolawServiceModel;
    }

    /**
     * @param mixed $userid
     * @param mixed $shopid
     * @return array
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    protected function getExtConfigSettings(mixed $userid, mixed $shopid): array
    {
        $lifetime = 12 * 3600;
        $_extConfig = $this->extensionConfiguration->get(
            'janolawservice'
        );
        if (is_array($_extConfig)) {
            $lifetime = (int)($_extConfig['lifetimeHours'] ?? 12) * 3600;
            if (!isset($userid) || (int)$userid <= 0) {
                $userid = $_extConfig['user_id'] ?? 0;
            }
            if (!isset($shopid) || (int)$shopid <= 0) {
                $shopid = $_extConfig['shop_id'] ?? 0;
            }
        }
        return [$lifetime, $userid, $shopid];
    }

    /**
     * Cache tags must only contain [a-zA-Z0-9_%\-&]
     */
    private function getCacheTags(string $language, string $type, mixed $userid, mixed $shopid, string $pdf): array
    {
        return array_map(
            static fn(mixed $tag): string => preg_replace('/[^a-zA-Z0-9_%\-&]/', '_', (string)$tag),
            [$language, $type, $userid, $shopid, $pdf]
        );
    }

    private function getRequestUrl(): string
    {
        if (!isset($this->request)) {
            return '';
        }
        $normalizedParams = $this->request->getAttribute('normalizedParams');
        return $normalizedParams instanceof NormalizedParams ? $normalizedParams->getRequestUrl() : '';
    }
}

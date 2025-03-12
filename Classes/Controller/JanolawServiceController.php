<?php

namespace Janolaw\Janolawservice\Controller;

use Janolaw\Janolawservice\Domain\Model\JanolawService;
use Janolaw\Janolawservice\Domain\Repository\JanolawServiceRepository;
use Janolaw\Janolawservice\Utility\JanolawConfigurationUtility;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * JanolawServiceController
 */
class JanolawServiceController extends ActionController
{
    /**
     * janolawServiceRepository
     * ^^
     *
     * @var JanolawServiceRepository
     */
    protected JanolawServiceRepository $janolawServiceRepository;
    /**
     * @var FrontendInterface
     */
    private FrontendInterface $cache;

    /**
     * @var PersistenceManager
     */
    private PersistenceManager $persistenceManager;
    private JanolawConfigurationUtility $configUtily;
    private RequestFactory $requestFactory;
    private ExtensionConfiguration $extensionConfiguration;

    public function __construct(
        FrontendInterface $cache,
        PersistenceManager $persistenceManager,
        JanolawConfigurationUtility $configUtily,
        RequestFactory $requestFactory,
        JanolawServiceRepository $janolawServiceRepository,
        ExtensionConfiguration $extensionConfiguration
    ) {
        $this->configUtily = $configUtily;
        $this->cache = $cache;
        $this->persistenceManager = $persistenceManager;
        $this->requestFactory = $requestFactory;
        $this->janolawServiceRepository = $janolawServiceRepository;
        $this->extensionConfiguration = $extensionConfiguration;
    }

    /**
     * action generate
     */
    public function generateAction(): ResponseInterface
    {
        $language = $this->settings['janolawservice']['language'];
        $type = $this->settings['janolawservice']['type'];
        $pdflink = $this->settings['janolawservice']['pdflink'];
        $userid = $this->settings['janolawservice']['userid'];
        $shopid = $this->settings['janolawservice']['shopid'];
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
                if ($content) {
                    $this->cache->set(
                        $cacheIdentifier,
                        $content,
                        [ $language, $type, $userid, $shopid, $pdf ],
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
                        [ $language, $type, $userid, $shopid, $pdf ],
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
        $janolawServiceModel->setShopId($shopid);
        $janolawServiceModel->setPdf($pdf);
        try {
            $this->janolawServiceRepository->add($janolawServiceModel);
            $this->persistenceManager->persistAll();
            $url = 'https://api.janolaw.de/pluginsettings';
            $jsonData = [
                'userID' => $userid,
                'shopID' => $shopid,
                'plugin' => 'TYPO3',
                'domain' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'),
                'pluginversion' => ExtensionManagementUtility::getExtensionVersion(
                    'janolawservice'
                ),
                'cmsversion' => VersionNumberUtility::getCurrentTypo3Version(),
                'settings' => '<b>Language:</b> ' . $language . '<br/><b>Type:</b> '
                              . $type . '<br/><b>PDF:</b> ' . $pdf,
                'misc' => '',
            ];
            $jsonDataEncoded = json_encode($jsonData);
            $additionalOptions = [
                'body' => $jsonDataEncoded,
                // OR form data:
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Content-Length' => strlen($jsonDataEncoded),
                ],
            ];

            $this->requestFactory->request($url, 'POST', $additionalOptions);
        } catch (IllegalObjectTypeException $e) {
            $error = true;
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
            $lifetime = $_extConfig['lifetimeHours'] * 3600;
            if (!isset($userid) || $userid <= 0) {
                $userid = $_extConfig['user_id'];
            }
            if (!isset($shopid) || $shopid <= 0) {
                $shopid = $_extConfig['shop_id'];
            }
        }
        return [$lifetime, $userid, $shopid];
    }
}

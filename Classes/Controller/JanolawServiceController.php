<?php

namespace Janolaw\Janolawservice\Controller;

use Janolaw\Janolawservice\Domain\Model\JanolawService;
use Janolaw\Janolawservice\Domain\Repository\JanolawServiceRepository;
use Janolaw\Janolawservice\Utility\JanolawConfigurationUtility;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

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
     * /**
     * @var FrontendInterface
     */
    private FrontendInterface $cache;

    public function __construct( FrontendInterface $cache )
    {
        $this->cache = $cache;
    }

    /**
     * @param JanolawServiceRepository $janolawServiceRepository
     */
    public function injectJanolawServiceRepository(
        JanolawServiceRepository $janolawServiceRepository
    )
    {
        $this->janolawServiceRepository = $janolawServiceRepository;
    }

    /**
     * action generate
     */

    public function generateAction()
    {
        $language = $this->settings['janolawservice']['language'];
        $type = $this->settings['janolawservice']['type'];
        $pdflink = $this->settings['janolawservice']['pdflink'];
        $userid = $this->settings['janolawservice']['userid'];
        $shopid = $this->settings['janolawservice']['shopid'];

        $janolawContent = "";
        try
        {
            $janolawContent = $this->getJanolawContent(
                $type,
                $language,
                $pdflink,
                $userid,
                $shopid
            );
        }
        catch ( IllegalObjectTypeException $e )
        {
        }
        catch ( UnknownObjectException $e )
        {
        }

        $this->view->assign( 'janolawContent', $janolawContent );
    }

    /**
     * @param $type
     * @param $language
     * @param string $pdf
     * @param int $userid
     * @param int $shopid
     *
     * @return bool|mixed|string
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function getJanolawContent(
        $type,
        $language,
        $pdf = "no_pdf",
        $userid = 0,
        $shopid = 0
    )
    {
        $debugMessage = "";

        $_extConfig = GeneralUtility::makeInstance( ExtensionConfiguration::class )->get(
            'janolawservice'
        );
        $lifetime = $_extConfig['lifetimeHours'] * 3600;
        if ( !isset( $userid ) || $userid <= 0 )
        {
            $userid = $_extConfig['user_id'];
        }
        if ( !isset( $shopid ) || $shopid <= 0 )
        {
            $shopid = $_extConfig['shop_id'];
        }
        // eindeutigen identifier für unseren cache generieren
        $cacheIdentifier = md5(
            $language . "-" . $type . "-" . $userid . "-" . $shopid . "-" . $pdf
        );
        $content = $this->cache->get( $cacheIdentifier );

        if ( $content === false )
        {
            $configUtil = new JanolawConfigurationUtility();

            $validUserData = $configUtil->hasValidUserData( $userid, $shopid );
            $validVersion = $configUtil->janolaw_get_version( $userid, $shopid, $debugMessage );

            if ( !( $language === "de" ) && !( $validVersion === "3m" ) )
            {
                // only in Version 3m multilanguage is enabled
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

            if ( $validUserData && ( ( $validVersion == 1 ) || ( $validVersion == 2 ) || ( $validVersion == 3 ) || ( $validVersion == "3m" ) ) )
            {
                // Cache nicht vorhanden
                $debugMessage .= "Kein Cache vorhanden - generieren für identifier " . $cacheIdentifier . " und Lifetime in secs: " . $lifetime;
                $content = $configUtil->janolaw_get_content(
                    $validVersion,
                    $language,
                    $type,
                    $userid,
                    $shopid,
                    $debugMessage,
                    $pdf
                );

                if ( $content )
                {
                    $this->cache->set(
                        $cacheIdentifier,
                        $content,
                        array( $language, $type, $userid, $shopid, $pdf ),
                        $lifetime
                    );
                    //set new Content to Janolaw Service DB Entries... fallback of Content from janolaw fails
                    $janolawService->setExternal( $content );
                    $this->janolawServiceRepository->update( $janolawService );
                    $this->objectManager->get(
                        'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager'
                    )->persistAll();
                }
            }
            if ( !$content )
            {
                //no valid Content from Janolaw, set Value from DB to cache
                $content = $janolawService->getExternal();
                if ( $content )
                {
                    $debugMessage .= "No Valid Return from Janolaw, use latest Value from DB and set this to cache";
                    $this->cache->set(
                        $cacheIdentifier,
                        $content,
                        array( $language, $type, $userid, $shopid, $pdf ),
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
        $pdf = "no_pdf"
    ): object
    {
        $query = $this->janolawServiceRepository->findByJanolawServiceParams(
            $language,
            $type,
            $userid,
            $shopid,
            $pdf
        );
        if ( $query->count() >= 1 )
        {
            return $query->getFirst();
        }
        else
        {
            //DB Entry did not exist - create
            return $this->createJanolawService( $language, $type, $userid, $shopid, $pdf );
        }
    }

    private function createJanolawService( $language, $type, $userid, $shopid, $pdf ): object
    {
        $janolawServiceModel = new JanolawService( $type, $shopid, $userid, $language, $pdf );
        try
        {
            $this->janolawServiceRepository->add( $janolawServiceModel );
            $this->objectManager->get(
                'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager'
            )->persistAll();

            $url = 'https://api.janolaw.de/pluginsettings';
            $jsonData = array(
                'userID' => $userid,
                'shopID' => $shopid,
                'plugin' => 'TYPO3',
                'domain' => GeneralUtility::getIndpEnv( 'TYPO3_REQUEST_URL' ),
                'pluginversion' => ExtensionManagementUtility::getExtensionVersion(
                    'janolawservice'
                ),
                'cmsversion' => VersionNumberUtility::getCurrentTypo3Version(),
                'settings' => '<b>Language:</b> ' . $language . '<br/><b>Type:</b> ' . $type . '<br/><b>PDF:</b> ' . $pdf,
                'misc' => '',
            );
            $jsonDataEncoded = json_encode( $jsonData );
            $additionalOptions = [
                'body' => $jsonDataEncoded,
                // OR form data:
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Content-Length' => strlen( $jsonDataEncoded ),
                ]
            ];

            $requestFactory = GeneralUtility::makeInstance( RequestFactory::class );

            $requestFactory->request( $url, 'POST', $additionalOptions );

        }
        catch ( IllegalObjectTypeException $e )
        {
        }

        return $janolawServiceModel;
    }
}

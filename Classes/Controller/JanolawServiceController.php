<?php

namespace Janolaw\Janolawservice\Controller;

use Janolaw\Janolawservice\Domain\Repository\JanolawServiceRepository;
use Janolaw\Janolawservice\Utility\JanolawConfigurationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
    protected $janolawServiceRepository = null;
    /**
     * cacheUtility
     *
     * @var \TYPO3\CMS\Core\Cache\CacheManager
     */
    protected $cacheInstance;

    /**
     * @param JanolawServiceRepository $janolawServiceRepository
     */
    public function injectJanolawServiceRepository(
        JanolawServiceRepository $janolawServiceRepository
    )
    {
        $this->janolawServiceRepository = $janolawServiceRepository;
    }

    public function initializeAction()
    {
        $name = 'TYPO3\\CMS\\Core\\Cache\\CacheManager';
        $manager = GeneralUtility::makeInstance( $name );
        $this->cacheInstance = $manager->getCache( 'janolaw_janolawservices' );
    }

    /**
     * action generate
     *
     * @return void
     */

    public function generateAction()
    {
        $language = $this->settings['janolawservice']['language'];
        $type = $this->settings['janolawservice']['type'];
        $pdflink = $this->settings['janolawservice']['pdflink'];
        $userid = $this->settings['janolawservice']['userid'];
        $shopid = $this->settings['janolawservice']['shopid'];

        try
        {
            $content = $this->getJanoloawContent( $type, $language, $pdflink, $userid, $shopid );
        }
        catch ( IllegalObjectTypeException $e )
        {
        }
        catch ( UnknownObjectException $e )
        {
        }

        $this->view->assign( 'content', $content );
    }

    /**
     * @param $type
     * @param $language
     * @param string $pdf
     * @param int $userid
     * @param int $shopid
     *
     * @return bool|mixed|string
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function getJanoloawContent(
        $type,
        $language,
        $pdf = "no_pdf",
        $userid = 0,
        $shopid = 0
    )
    {
        $debugMessage = "";
        $_extConfig = unserialize(
            $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['janolawservice']
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
        $content = $this->cacheInstance->get( $cacheIdentifier );

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
                    $pdf,
                    $debugMessage
                );

                if ( $content )
                {
                    $this->cacheInstance->set(
                        $cacheIdentifier,
                        $content,
                        array( $language, $type, $userid, $shopid, $pdf ),
                        $lifetime
                    );
                    //set new Content to Janolaw Service DB Entries... fallback of Content from janolaw fails
                    $janolawService->setContent( $content );
                    $this->janolawServiceRepository->update( $janolawService );
                }
            }
            if (!$content)
            {
                //no valid Content from Janolaw, set Value from DB to cache
                $content = $janolawService->getContent();
                if ( $content )
                {
                    $debugMessage .= "No Valid Return from Janolaw, use latest Value from DB and set this to cache";
                    $this->cacheInstance->set(
                        $cacheIdentifier,
                        $content,
                        array( $language, $type, $userid, $shopid, $pdf ),
                        $lifetime
                    );
                }
            }
        }
        else
        {
            $debugMessage .= "Get Content from Cache";
        }
        return $content;
    }

    private function getJanolawService( $language, $type, $userid, $shopid, $pdf = "no_pdf" )
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

    private function createJanolawService( $language, $type, $userid, $shopid, $pdf )
    {
        $janolawService = $this->objectManager->get(
            'Janolaw\\Janolawservice\\Domain\\Model\\JanolawService'
        );
        $janolawService->setContent( '' );
        $janolawService->setLegacyLanguage( $language );
        $janolawService->setType( $type );
        $janolawService->setUserId( $userid );
        $janolawService->setShopId( $shopid );
        $janolawService->setPdf( $pdf );
        try
        {
            $this->janolawServiceRepository->add( $janolawService );
            $this->objectManager->get(
                'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager'
            )->persistAll();
        }
        catch ( IllegalObjectTypeException $e )
        {
        }

        return $janolawService;
    }
}

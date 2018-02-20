<?php

namespace Janolaw\Janolawservice\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * class providing configuration checks for janolaw
 */
class JanolawConfigurationUtility
{
    /**
     * Checks the backend configuration and shows a message if necessary.
     * The method returns an array or the HTML code depends on
     * $params['propertyName'] is set or not.
     *
     *     * @return array|string array with errorType and HTML or only the HTML as string
     */

    public function checkUserData()
    {
        $_extConfig = unserialize(
            $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['janolawservice']
        );

        $userid = $_extConfig['user_id'];
        $shopid = $_extConfig['shop_id'];

        if ( $this->hasValidUserData( $userid, $shopid ) )
        {
            $result = 'Ihre Daten sind o.k., der Janolaw Server ist erreichbar.<br/>';
            $this->janolaw_get_version( $userid, $shopid, $result );
        }
        else if ( isset( $userid ) && isset( $shopid ) && ( $shopid != "" ) && ( $userid != "" ) )
        {
            $result = "Für Ihre Shop ID und User ID ist kein Janolaw Service erreichbar.";

        }
        else
        {
            $result = "Bitte geben Sie Shop ID und User ID ein!";
        }

        return $result;
    }

    public function hasValidUserData( $userid, $shopid )
    {
        if ( isset( $userid ) && isset( $shopid ) && ( $shopid != "" ) && ( $userid != "" ) )
        {
            $base_url = 'http://www.janolaw.de/agb-service/shops/';
            $report = array();
            GeneralUtility::getURL(
                $base_url . '/' . $userid . '/' . $shopid . '/',
                true,
                false,
                $report
            );

            if ( $report["error"] == '404' ||  $report["http_code"] == '404')
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * @param $userid
     * @param $shopid
     *
     * @param $debugMessage
     *
     * @return mixed
     */
    public function janolaw_get_version( $userid, $shopid, &$debugMessage )
    {
        $base_url = 'http://www.janolaw.de/agb-service/shops/';

        $report = array();
        GeneralUtility::getURL(
            $base_url . '/' . $userid . '/' . $shopid . '/',
            true,
            false,
            $report
        );

        $version = 1;
        if ( ($report["error"] == '404' ||  $report["http_code"] == '404'))
        {
            $debugMessage .= "janolaw server <u>NICHT</u> verfügbar.<br/>";
        }
        else
        {

            # check for version 1
            GeneralUtility::getURL(
                $base_url . '/' . $userid . '/' . $shopid . '/legaldetails_include.html',
                true,
                false,
                $report
            );
            if ( !($report["error"] == '404' ||  $report["http_code"] == '404'))
            {
                $version = 1;
            }
            # check for version 2
            GeneralUtility::getURL(
                $base_url . '/' . $userid . '/' . $shopid . '/de/legaldetails_include.html',
                true,
                false,
                $report
            );
            if ( !($report["error"] == '404' ||  $report["http_code"] == '404'))
            {
                $version = 2;
            }
            # check for version 3
            GeneralUtility::getURL(
                $base_url . '/' . $userid . '/' . $shopid . '/de/legaldetails.pdf',
                true,
                false,
                $report
            );
            if ( !($report["error"] == '404' ||  $report["http_code"] == '404'))
            {
                $version = 3;
            }

            $debugMessage .= "janolaw server verfügbar in Version " . $version . "<br/>";
            # check for version 3 with Multilanguage
            GeneralUtility::getURL(
                $base_url . '/' . $userid . '/' . $shopid . '/gb/legaldetails_include.html',
                true,
                false,
                $report
            );

            if ( !($report["error"] == '404' ||  $report["http_code"] == '404'))
            {
                $debugMessage .= "janolaw server verfügbar in Mehrsprachig<br/>";
                $version = "3m";
            }

        }
        if ( $version <= 2 )
        {
            $debugMessage .= "Sie nutzen eine alte Version des janolaw-Service, bitte prüfen Sie, ob Sie die Texte neu generiert haben und für die optionale mehrsprachige Version den Service bei janolaw gebucht haben. Kontaktieren Sie dazu janolaw unter <a href='mailto:support@janolaw.de'>support@janolaw.de</a> oder unter 06196 / 77 22 777.";
        }

        return $version;
    }

    /**
     * Checks the backend configuration and shows a message if necessary.
     * The method returns an array or the HTML code depends on
     * $params['propertyName'] is set or not.
     *
     * @return array|string array with errorType and HTML or only the HTML as string
     */

    public function checkVersion(  )
    {
        $_extConfig = unserialize(
            $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['janolawservice']
        );
        $userid = $_extConfig['user_id'];
        $shopid = $_extConfig['shop_id'];
        $version = 0;
        if ( isset( $userid ) && isset( $shopid ) && ( $shopid != "" ) && ( $userid != "" ) )
        {
            $version = $this->janolaw_get_version( $userid, $shopid, $debugMessage );
        }
        return $version;
    }

    /**
     * @param $version
     * @param $language
     * @param $type
     * @param $userid
     * @param $shopid
     * @param string $pdf
     * @param $debugMessage
     *
     * @return mixed|string
     */
    public function janolaw_get_content(
        $version,
        $language,
        $type,
        $userid,
        $shopid,
        $pdf = 'no_pdf',
        &$debugMessage
    )
    {
        $base_url = 'http://www.janolaw.de/agb-service/shops/' . $userid . '/' . $shopid . '/';
        $docUrl = '';
        $filename = '';
        $pdfUrl = '';
        switch ( $version )
        {
            case 1:
                if ( $language !== "de" )
                {
                    return false;
                }
                switch ( $type )
                {
                    case "legaldetails":
                        $filename = "impressum";
                        break;
                    case "terms":
                        $filename = "agb";
                        break;
                    case "revocation":
                        $filename = "widerrufsbelehrung";
                        break;
                    case "datasecurity":
                        $filename = "datenschutzerklaerung";
                        break;
                    case "model-withdrawal-form":
                        return false;
                        break;
                }
                $docUrl = $base_url . $filename . "_include.html";
                break;
            case 2:
                if ( $language !== "de" )
                {
                    return false;
                }
                if ( $type == "model-withdrawal-form" )
                {
                    return false;
                }
                $docUrl = $base_url . $language . "/" . $type . "_include.html";
                break;
            case 3:
            case "3m":
                $docUrl = $base_url . $language . "/" . $type . "_include.html";
                $pdfUrl = $base_url . $language . "/" . $type . ".pdf";
                break;
        }
        $debugMessage .= "Version: " . $version . " Link: " . $docUrl . "  ";

        $content = GeneralUtility::getURL( $docUrl, false );
        if ( ( $version === 3 ) || ( $version === "3m" ) )
        {
            //PDF File download, needed in every call, may be used in caching is managed before
            $pdfContent = GeneralUtility::getURL( $pdfUrl );
            $pdfName = $language . "_" . $type . ".pdf";
            $pdfPath = 'typo3temp/janolaw/'.$shopid."/";
            if ( !is_dir( $pdfPath ) )
            {
                GeneralUtility::mkdir_deep( PATH_site, $pdfPath );
            }
            $pdfsuccess = GeneralUtility::writeFile(
                $pdfPath . $pdfName,
                $pdfContent,
                true
            );
            if ( $pdfsuccess )
            {
                $pdfUrl = $pdfPath . $pdfName;

                $pdflink = "<p><a class='janolaw-pdflink' href='" . $pdfUrl . "' target='_blank'>Download as PDF</a></p>";
                if ( $pdf === "pdf_top" )
                {
                    //show pdf link at top
                    $content = $pdflink . $content;
                }
                elseif ( $pdf === "pdf_bottom" )
                {
                    //show pdf link at bottom
                    $content = $content . $pdflink;
                }
                elseif ( $pdf === "only_pdf_link" )
                {
                    $content = $pdflink;
                }
            }
        }

        return $content;
    }
}

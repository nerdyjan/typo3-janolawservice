<?php
namespace Janolaw\Janolawservice\Utility;

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
     * @param array $params Field information to be rendered
     * @param \TYPO3\CMS\Core\TypoScript\ConfigurationForm $pObj The calling parent object.
     * @return array|string array with errorType and HTML or only the HTML as string
     */

    public function checkUserData(array $params, $pObj)
    {
        $_extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['janolawservice']);

        $userid = $_extConfig['user_id'];
        $shopid = $_extConfig['shop_id'];

        if ($this->hasValidUserData($userid, $shopid))
        {
            $result = 'Ihre Daten sind o.k., der Janolaw Server ist erreichbar.<br/>';
            $this->janolaw_get_version($userid, $shopid, $result);
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

    public function hasValidUserData($userid, $shopid)
    {
        if (isset($userid) && isset($shopid) && ($shopid != "") && ($userid !=""))
        {
            $base_url = 'http://www.janolaw.de/agb-service/shops/';
            $report = array();
            $content = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($base_url.'/'.$userid.'/'.$shopid.'/', 1, false, $report);

            if ($report["http_code"] == '404')
            {
                return 0;
            }
            else
            {
                return 1;
            }
        }
        else
            return 1;
    }
    /**
     * Checks the backend configuration and shows a message if necessary.
     * The method returns an array or the HTML code depends on
     * $params['propertyName'] is set or not.
     *
     * @param array $params Field information to be rendered
     * @param \TYPO3\CMS\Core\TypoScript\ConfigurationForm $pObj The calling parent object.
     * @return array|string array with errorType and HTML or only the HTML as string
     */

    public function checkVersion(array $params, $pObj)
    {
        $_extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['janolawservice']);
        $userid = $_extConfig['user_id'];
        $shopid = $_extConfig['shop_id'];
        $version = 0;
        if (isset($userid) && isset($shopid) && ($shopid != "") && ($userid !=""))
        {
            $version = $this->janolaw_get_version($userid, $shopid, $debugMessage);
        }
        else
        {
            $debugMessage="Version kann erst ermittelt werden, wenn es Shop ID und User ID gibt.";
        }
        return $debugMessage;
    }

    /**
     * @param $userid
     * @param $shopid
     *
     * @return mixed
     */
    public function janolaw_get_version($userid, $shopid, &$debugMessage) {
        $base_url = 'http://www.janolaw.de/agb-service/shops/';

        $report = array();
        $content = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($base_url.'/'.$userid.'/'.$shopid.'/', 1, false, $report);

        $version = 1;

        if ($report["http_code"] == '404') {
            $debugMessage .= "janolaw server <u>NICHT</u> verfügbar.<br/>";
        } else {

            # check for version 1
            $content =  \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($base_url.'/'.$userid.'/'.$shopid.'/legaldetails_include.html', 1, false, $report);
            if ($report["http_code"] != '404') {
                $version = 1;
            }
            # check for version 2
            $content =  \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($base_url.'/'.$userid.'/'.$shopid.'/de/legaldetails_include.html', 1, false, $report);
            if ($report["http_code"] != '404') {
                $version = 2;
            }
            # check for version 3
            $content =  \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($base_url.'/'.$userid.'/'.$shopid.'/de/legaldetails.pdf', 1, false, $report);
            if ($report["http_code"] != '404'){
                $version = 3;
            }

            $debugMessage .= "janolaw server verfügbar in Version ".$version."<br/>";
            # check for version 3 with Multilanguage
            $content =  \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($base_url.'/'.$userid.'/'.$shopid.'/gb/legaldetails_include.html', 1, false, $report);
            if ($report["http_code"] != '404'){
                $debugMessage .= "janolaw server verfügbar in Mehrsprachig<br/>";
                $version = "3m";
            }

        }
        if ($version <= 2) {
            $debugMessage .= "Sie nutzen eine alte Version des janolaw-Service, bitte prüfen Sie, ob Sie die Texte neu generiert haben und für die optionale mehrsprachige Version den Service bei janolaw gebucht haben. Kontaktieren Sie dazu janolaw unter <a href='mailto:support@janolaw.de'>support@janolaw.de</a> oder unter 06196 / 77 22 777.";
        }
        return $version;
    }

    public function janolaw_get_content($version, $language , $type, $userid, $shopid, $pdf='no_pdf',  &$debugMessage)
    {
        $base_url = 'http://www.janolaw.de/agb-service/shops/'.$userid.'/'.$shopid.'/';

        switch ($version)
        {
            case 1:
                if ($language !== "de")
                    return false;
                switch ($type)
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
                $docUrl = $base_url.$filename."_include.html";
                break;
            case 2:
                if ($language !== "de")
                    return false;
                if ($type == "model-withdrawal-form")
                    return false;
                $docUrl = $base_url.$language."/".$type."_include.html";
                break;
            case 3:
            case "3m":
                $docUrl = $base_url.$language."/".$type."_include.html";
                $pdfUrl = $base_url.$language."/".$type.".pdf";
                break;
        }
        $debugMessage .="Version: ".$version." Link: ".$docUrl."  ";

        $content = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($docUrl, 0);
        if  (($version === 3) || ($version === "3m"))
        {
            //PDF File download, needed in every call, may be used in caching is managed before
            $pdfContent = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($pdfUrl);
            $pdfName = $language."_".$type.".pdf";
            $pdfPath = 'typo3temp/janolaw/';
            if (!is_dir($pdfPath)) {
                \TYPO3\CMS\Core\Utility\GeneralUtility::mkdir_deep(PATH_site, $pdfPath);
            }
            $pdfsuccess = \TYPO3\CMS\Core\Utility\GeneralUtility::writeFile($pdfPath.$pdfName, $pdfContent, true);
            if ( $pdfsuccess)
            {
                $pdfUrl = $pdfPath . $pdfName;
            }

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
            elseif ( $pdf === "only_pdf_link")
            {
                $content = $pdflink;
            }
        }
        return $content;
    }
}

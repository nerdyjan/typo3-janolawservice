<?php

namespace Janolaw\Janolawservice\Utility;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * class providing configuration checks for janolaw
 */
class JanolawConfigurationUtility
{
    /**
     * @param RequestFactory $requestFactory RequestFactory
     */
    public function injectRequestFactory(
        RequestFactory $requestFactory
    ): void
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * Checks the backend configuration and shows a message if necessary.
     * The method returns an array or the HTML code depends on
     * $params['propertyName'] is set or not.
     *
     *     * @return string result
     */
    public function checkUserData()
    {
        $_extConfig = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get(
            'janolawservice'
        );

        $userid = $_extConfig['user_id'];
        $shopid = $_extConfig['shop_id'];

        if ($this->hasValidUserData($userid, $shopid)) {
            $result = 'Ihre Daten sind o.k., der Janolaw Server ist erreichbar.<br/>';
            $this->janolawGetVersion($userid, $shopid, $result);
        } else {
            if (isset($userid) && isset($shopid) && ($shopid != '') && ($userid != '')) {
                $result = 'Für Ihre Shop ID und User ID ist kein Janolaw Service erreichbar.';
            } else {
                $result = 'Bitte geben Sie Shop ID und User ID ein!';
            }
        }

        return $result;
    }

    public function hasValidUserData($userid, $shopid): bool
    {
        if (isset($userid) && isset($shopid) && ($shopid != '') && ($userid != '')) {
            $base_url = 'https://www.janolaw.de/agb-service/shops/' . $userid . '/' . $shopid . '/';

            $additionalOptions = [
                'allow_redirects' => true,
                'http_errors' => false,
            ];
            $response = $this->requestFactory->request($base_url, 'GET', $additionalOptions);
            if ($response->getStatusCode() == '404' || $response->getStatusCode() == '404') {
                return false;
            }
            return true;
        }
        return false;
    }

    public function janolawGetVersion($userid, $shopid, &$debugMessage)
    {
        $base_url = 'https://www.janolaw.de/agb-service/shops/';

        $additionalOptions = [
            'allow_redirects' => true,
            'http_errors' => false,
        ];
        $response = $this->requestFactory->request($base_url, 'GET', $additionalOptions);

        $version = 1;
        if (($response->getStatusCode() == '404' || $response->getStatusCode() == '404')) {
            $debugMessage .= 'janolaw server <u>NICHT</u> verfügbar.<br/>';
        } else {
            // check for version 3
            $response = $this->requestFactory->request(
                $base_url . '/' . $userid . '/' . $shopid . '/de/legaldetails.pdf',
                'GET',
                $additionalOptions
            );
            if (!($response->getStatusCode() == '404' || $response->getStatusCode() == '404')) {
                $version = 3;
                // check for version 3 with Multilanguage
                $response = $this->requestFactory->request(
                    $base_url . '/' . $userid . '/' . $shopid . '/gb/legaldetails_include.html',
                    'GET',
                    $additionalOptions
                );
                if (!($response->getStatusCode() == '404' || $response->getStatusCode() == '404')) {
                    $debugMessage .= 'janolaw server verfügbar in Mehrsprachig<br/>';
                    $version = '3m';
                }
            } else {
                // check for version 2
                $response = $this->requestFactory->request(
                    $base_url . '/' . $userid . '/' . $shopid . '/de/legaldetails_include.html',
                    'GET',
                    $additionalOptions
                );
                if (!($response->getStatusCode() == '404' || $response->getStatusCode() == '404')) {
                    $version = 2;
                } else {
                    $response = $this->requestFactory->request(
                        $base_url . '/' . $userid . '/' . $shopid . '/legaldetails_include.html',
                        'GET',
                        $additionalOptions
                    );
                    if (!($response->getStatusCode() == '404' || $response->getStatusCode() == '404')) {
                        $version = 1;
                    }
                }
            }
            $debugMessage .= 'janolaw server verfügbar in Version ' . $version . '<br/>';
        }
        if ($version <= 2) {
            $debugMessage .= "Sie nutzen eine alte Version des janolaw-Service,
                bitte prüfen Sie, ob Sie die Texte neu generiert haben und für die optionale
                mehrsprachige Version den Service bei janolaw gebucht haben. Kontaktieren Sie dazu
                janolaw unter <a href='mailto:support@janolaw.de'>support@janolaw.de</a> oder
                unter 06196 / 77 22 777.";
        }

        return $version;
    }

    public function janolawGetContent($version, $language, $type, $userid, $shopid, &$debugMessage, $pdf = 'no_pdf')
    {
        $base_url = 'https://www.janolaw.de/agb-service/shops/' . $userid . '/' . $shopid . '/';
        $docUrl = '';
        $pdfUrl = '';
        $content = false;
        switch ($version) {
            case 1:
                if ($language !== 'de') {
                    return false;
                }
                $docUrl = $base_url . $this->getVersion1Filename($type) . '_include.html';
                $debugMessage .= 'Version: ' . $version . ' Link: ' . $docUrl . '  ';
                $content = GeneralUtility::getURL($docUrl);
                break;
            case 2:
                if ($language !== 'de') {
                    return false;
                }
                if ($type == 'model-withdrawal-form') {
                    return false;
                }
                $docUrl = $base_url . $language . '/' . $type . '_include.html';
                $debugMessage .= 'Version: ' . $version . ' Link: ' . $docUrl . '  ';
                $content = GeneralUtility::getURL($docUrl);
                break;
            case 3:
            case '3m':
                $docUrl = $base_url . $language . '/' . $type . '_include.html';
                $pdfUrl = $base_url . $language . '/' . $type . '.pdf';
                $debugMessage .= 'Version: ' . $version . ' Link: ' . $docUrl . '  ';
                $content = GeneralUtility::getURL($docUrl);
                $this->generatePdfContent($pdfUrl, $content, $language, $type, $shopid, $pdf);
                break;
        }
        return $content;
    }

    private function generatePdfContent($pdfUrl, &$content, $language, $type, $shopid, $pdf)
    {
        //PDF File download, needed in every call, may be used in caching is managed before
        $pdfContent = GeneralUtility::getURL($pdfUrl);
        $pdfName = $language . '_' . $type . '.pdf';
        $pdfPath = '/typo3temp/janolaw/' . $shopid;
        if (!is_dir(Environment::getPublicPath() . $pdfPath)) {
            GeneralUtility::mkdir_deep(Environment::getPublicPath() . $pdfPath);
        }
        $errorMessage = GeneralUtility::writeFileToTypo3tempDir(Environment::getPublicPath()
                                                                . $pdfPath . '/' . $pdfName, $pdfContent);

        if ($errorMessage == null) {
            $pdfUrl = $pdfPath . '/' . $pdfName;
            $pdflink = "<p><a class='janolaw-pdflink' href='"
                       . $pdfUrl . "' target='_blank'>Download as PDF</a></p>";
            if ($pdf === 'pdf_top') {
                //show pdf link at top
                $content = $pdflink . $content;
            } elseif ($pdf === 'pdf_bottom') {
                //show pdf link at bottom
                $content = $content . $pdflink;
            } elseif ($pdf === 'only_pdf_link') {
                $content = $pdflink;
            }
        }
    }

    private function getVersion1Filename($type)
    {
        $filename = '';
        switch ($type) {
            case 'legaldetails':
                $filename = 'impressum';
                break;
            case 'terms':
                $filename = 'agb';
                break;
            case 'revocation':
                $filename = 'widerrufsbelehrung';
                break;
            case 'datasecurity':
                $filename = 'datenschutzerklaerung';
                break;
        }
        return $filename;
    }
}

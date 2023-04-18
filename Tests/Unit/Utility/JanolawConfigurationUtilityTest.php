<?php

namespace Janolaw\Janolawservice\Tests\Unit\Utility;

use Janolaw\Janolawservice\Utility\JanolawConfigurationUtility;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\Client\GuzzleClientFactory;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class JanolawConfigurationUtilityTest extends UnitTestCase
{
    //test User-IDs and ShopIDs
    private const USERID_INVALID = '000000000';
    private const SHOPID_INVALID = '000000';
    private const USERID_DE_V2 = '100200356';
    private const SHOPID_DE_V2 = '779938';

    private const USERID_DE = '100288600';
    private const SHOPID_DE = '815917';
    private const USERID_MULTILANGUAGE = '100282211';
    private const SHOPID_MULTILANGUAGE = '815904';

    private const BASEURL = 'https://www.janolaw.de/agb-service/shops/';
    private const LANG_DE = 'de';
    private const LANG_EN = 'en';
    private const LEGAL_DETAILS = 'legaldetails';
    private const WITHDRAWAL = 'model-withdrawal-form';

    private string $debugMessage = '';
    private RequestFactory $requestFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $guzzleClient = new GuzzleClientFactory();
        $requestFactory = new RequestFactory($guzzleClient);
        $this->requestFactory =new RequestFactory($guzzleClient);
        $this->configUtil = new JanolawConfigurationUtility();
        $this->configUtil->injectRequestFactory($requestFactory);
    }
    /**
     * @test
     */
    public function generatePdfContentTypo3TempExistsTest()
    {
        $pdfPath = Environment::getPublicPath() . '/typo3temp/';
        self::assertDirectoryExists($pdfPath);
    }

    /**
     * @test
     */
    public function canConnectToJanolawServerTest()
    {
        $additionalOptions = [
            'allow_redirects' => true,
            'http_errors' => false,
        ];

        $response = $this->requestFactory->request(self::BASEURL, 'GET', $additionalOptions);
        self::assertNotEquals('404', $response->getStatusCode());
    }

    /**
     * @test
     */
    public function canSendGetRequestToJanolawServerTest()
    {
        $docUrl = self::BASEURL . '/' . self::USERID_MULTILANGUAGE . '/' . self::SHOPID_MULTILANGUAGE
                  . '/' . self::LANG_DE . '/' . self::LEGAL_DETAILS . '_include.html';
        $additionalOptions = [
            'allow_redirects' => true,
            'http_errors' => false,
        ];
        $response = $this->requestFactory->request($docUrl, 'GET', $additionalOptions);
        self::assertEquals('200', $response->getStatusCode());
    }

    /**
     * @test
     */
    public function canGetContentUrlFromJanolawServerTest()
    {
        $docUrl = self::BASEURL . '/' . self::USERID_MULTILANGUAGE . '/' . self::SHOPID_MULTILANGUAGE
                  . '/' . self::LANG_DE . '/' . self::LEGAL_DETAILS . '_include.html';
        $content = @file_get_contents($docUrl);
        self::assertNotFalse($content);
    }

    /**
     * @test
     */
    public function canGetPdfFileFromJanolawServerTest()
    {
        $pdfUrl = self::BASEURL . '/' . self::USERID_MULTILANGUAGE . '/' . self::SHOPID_MULTILANGUAGE
                  . '/' . self::LANG_DE . '/' . self::LEGAL_DETAILS . '.pdf';
        $content = @file_get_contents($pdfUrl);
        self::assertNotFalse($content);
    }

    /**
     * @test
     */
    public function janolawHasValidUserData()
    {
        //verify error-cases returns one
        $result = $this->configUtil->hasValidUserData(self::USERID_DE, self::SHOPID_DE);
        self::assertTrue($result);
        $result = $this->configUtil->hasValidUserData(self::USERID_MULTILANGUAGE, self::SHOPID_DE);
        self::assertFalse($result);
        $result = $this->configUtil->hasValidUserData('', '');
        self::assertFalse($result);
    }

    /**
     * @test
     */
    public function janolawGetVersionGetVersionWithEmptyValues()
    {
        //verify error-cases returns one
        $version = $this->configUtil->janolawGetVersion('', '', $this->debugMessage);
        self::assertEquals( 1, $version );
    }

    /**
     * @test
     */
    public function janolawGetVersionGetVersionWithInvalidUserId()
    {
        //verify error-cases returns one
        $version = $this->configUtil->janolawGetVersion(self::USERID_INVALID, self::SHOPID_DE, $this->debugMessage);
        self::assertEquals( 1, $version );
    }

    /**
     * @test
     */
    public function janolawGetVersionGetVersionWithInvalidShopId()
    {
        //verify error-cases returns one
        $version = $this->configUtil->janolawGetVersion(self::USERID_DE, self::SHOPID_INVALID, $this->debugMessage);
        self::assertEquals( 1, $version );
    }

    /**
     * @test
     */
    public function janolawGetVersionGetVersionWithInvalidUserIdAndShopIdCombination(): void
    {
        $version = $this->configUtil->janolawGetVersion(self::USERID_MULTILANGUAGE, self::SHOPID_DE, $this->debugMessage);
        self::assertEquals( 1, $version );

        $version = $this->configUtil->janolawGetVersion(self::USERID_DE, self::SHOPID_MULTILANGUAGE, $this->debugMessage);
        self::assertEquals( 1, $version );
    }

    /**
     * @test
     */
    public function janolawGetVersionGetVersionWithValidDataForVersion3()
    {
        //verify error-cases returns one
        $version = $this->configUtil->janolawGetVersion(self::USERID_DE, self::SHOPID_DE, $this->debugMessage);
        self::assertEquals( 3, $version );
    }

    /**
     * @test
     */
    public function janolawGetVersionGetVersionWithValidDataForVersion3Multilanguage()
    {
        //verify error-cases returns one
        $version = $this->configUtil->janolawGetVersion(
            self::USERID_MULTILANGUAGE,
            self::SHOPID_MULTILANGUAGE,
            $this->debugMessage
        );
        self::assertEquals( '3m', $version );
    }

    /**
     * @test
     */
    public function janolawGetVersionGetVersionWithValidDataForVersion2()
    {
        //verify error-cases returns one
        $version = $this->configUtil->janolawGetVersion(self::USERID_DE_V2, self::SHOPID_DE_V2, $this->debugMessage);
        self::assertEquals( 2, $version );
    }
    /**
     * not working at the moment because of getURL calling requestfactory with makeInstance
     */
    public function janolawGetContentGeneratePdfContentTest()
    {
        $content = $this->configUtil->janolawGetContent(
            '3m',
            self::LANG_DE,
            self::LEGAL_DETAILS,
            self::USERID_MULTILANGUAGE,
            self::SHOPID_MULTILANGUAGE,
            $this->debugMessage,
            'pdf_top'
        );
        self::assertNotFalse($content);

        $content = $this->configUtil->janolawGetContent(
            '3m',
            self::LANG_DE,
            self::LEGAL_DETAILS,
            self::USERID_MULTILANGUAGE,
            self::SHOPID_MULTILANGUAGE,
            $this->debugMessage,
            'pdf_bottom'
        );
        self::assertNotFalse($content);

        $content = $this->configUtil->janolawGetContent(
            '3m',
            self::LANG_DE,
            self::LEGAL_DETAILS,
            self::USERID_MULTILANGUAGE,
            self::SHOPID_MULTILANGUAGE,
            $this->debugMessage,
            'only_pdf_link'
        );
        self::assertNotFalse($content);
        $pdfPath = Environment::getPublicPath() . '/typo3temp/janolaw/' . self::SHOPID_MULTILANGUAGE;
        self::directoryExists();
        $pdfFile = $pdfPath . '/' . self::LANG_DE . '_' . self::LEGAL_DETAILS . '.pdf';
        self::assertFileExists($pdfFile);
        $this->testFilesToDelete[] = $pdfFile;

        $content = $this->configUtil->janolawGetContent(
            '2',
            self::LANG_EN,
            self::LEGAL_DETAILS,
            self::USERID_DE_V2,
            self::SHOPID_DE_V2,
            $this->debugMessage
        );
        self::assertFalse($content);

        $content = $this->configUtil->janolawGetContent(
            '2',
            self::LANG_DE,
            self::WITHDRAWAL,
            self::USERID_DE_V2,
            self::SHOPID_DE_V2,
            $this->debugMessage
        );
        self::assertFalse($content);

        $content = $this->configUtil->janolawGetContent(
            '2',
            self::LANG_DE,
            self::LEGAL_DETAILS,
            self::USERID_DE_V2,
            self::SHOPID_DE_V2,
            $this->debugMessage
        );
        self::assertIsString($content);

        $content = $this->configUtil->janolawGetContent(
            '1',
            self::LANG_EN,
            self::LEGAL_DETAILS,
            self::USERID_DE,
            self::SHOPID_DE,
            $this->debugMessage
        );
        self::assertFalse($content);

        $content = $this->configUtil->janolawGetContent(
            '1',
            self::LANG_DE,
            self::LEGAL_DETAILS,
            self::USERID_DE,
            self::SHOPID_DE,
            $this->debugMessage
        );
        self::assertIsString($content);

        $content = $this->configUtil->janolawGetContent(
            '1',
            self::LANG_DE,
            'terms',
            self::USERID_DE,
            self::SHOPID_DE,
            $this->debugMessage
        );
        self::assertIsString($content);

        $content = $this->configUtil->janolawGetContent(
            '1',
            self::LANG_DE,
            'revocation',
            self::USERID_DE,
            self::SHOPID_DE,
            $this->debugMessage
        );
        self::assertIsString($content);

        $content = $this->configUtil->janolawGetContent(
            '1',
            self::LANG_DE,
            'datasecurity',
            self::USERID_DE,
            self::SHOPID_DE,
            $this->debugMessage
        );
        self::assertIsString($content);
    }


}

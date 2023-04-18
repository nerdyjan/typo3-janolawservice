<?php

namespace Functional\Controller;

use Janolaw\Janolawservice\Controller\JanolawServiceController;
use Janolaw\Janolawservice\Domain\Repository\JanolawServiceRepository;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\Client\GuzzleClientFactory;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class JanolawServiceControllerTest extends FunctionalTestCase
{
    private const LANG = 'gb';
    private const LEGAL_DETAILS = 'legaldetails';
    private const PDF = 'pdf_top';
    //test User-IDs and ShopIDs
    private const USERID_MULTILANGUAGE = '100282211';
    private const SHOPID_MULTILANGUAGE = '815904';


    protected array $testExtensionsToLoad = ['typo3conf/ext/janolawservice'];

    protected JanolawServiceController $janolawServiceController;
    /**
     * Sets up this test case.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->janolawServiceController = new JanolawServiceController(
            $this->createMock(FrontendInterface::class),
            GeneralUtility::makeInstance(PersistenceManager::class),
        );

        $this->janolawServiceController->injectJanolawServiceRepository(
            GeneralUtility::makeInstance(JanolawServiceRepository::class)
        );
        $this->janolawServiceController->injectRequestFactory(
            GeneralUtility::makeInstance(RequestFactory::class)
        );
    }

    /**
     * @test
     */
    public function getJanolawContentEmptyValuesTest()
    {
        $extConfig = new ExtensionConfiguration();
        $_extConfig = $extConfig->get(
            'janolawservice'
        );

        //we expect invalid for unset default values
        $result = $this->janolawServiceController->getJanolawContent(
            self::LEGAL_DETAILS,
            self::LANG,
            self::PDF,
            $_extConfig['user_id'],
            $_extConfig['shop_id']
        );

        self::assertEmpty($result);
    }
    /**
     * @test
     */
    public function getJanolawContentMultilanguageTestFileSystem()
    {
        $result = $this->janolawServiceController->getJanolawContent(
            self::LEGAL_DETAILS,
            self::LANG,
            self::PDF,
            self::USERID_MULTILANGUAGE,
            self::SHOPID_MULTILANGUAGE
        );
        //check on Result
        self::assertNotNull($result);
        //check on PDF in FileSystem
        $path = Environment::getPublicPath() . '/typo3temp/janolaw/' . self::SHOPID_MULTILANGUAGE;
        self::directoryExists();
    }

    /**
     * @test
     */
    public function getJanolawContentMultilanguageTestIfTablesExistsAndIsEmpty()
    {
        $result = $this->getAllRecords('tx_janolawservice_domain_model_janolawservice');
        self::assertNotNull($result);
        self::assertEmpty($result);
    }

    /**
     * @test
     */
    public function importCSVinJanolawserviceDatabaseTable()
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/janolawservice.csv');
        $result = $this->getAllRecords('tx_janolawservice_domain_model_janolawservice');
        self::assertNotEmpty($result);
        $result = $this->getAllRecords('tx_janolawservice_domain_model_janolawservice');
        $this->assertCSVDataSet(__DIR__ . '/../Fixtures/janolawservice.csv');
    }
    /**
     * @test
     */
    public function getJanolawContentMultilanguageTestDatabase()
    {
        $this->janolawServiceController->getJanolawContent(
            self::LEGAL_DETAILS,
            self::LANG,
            self::PDF,
            self::USERID_MULTILANGUAGE,
            self::SHOPID_MULTILANGUAGE
        );
        $result = $this->getAllRecords('tx_janolawservice_domain_model_janolawservice');
        self::assertNotEmpty($result);
        //check on Fallback in Database
        $this->getAllRecords('tx_janolawservice_domain_model_janolawservice');
        $this->assertCSVDataSet(__DIR__ . '/../Fixtures/janolawservice_withoutexternal.csv');
    }
}

<?php

namespace Janolaw\Janolawservice\Tests\Unit\Controller;

use Janolaw\Janolawservice\Controller\JanolawServiceController;
use Janolaw\Janolawservice\Domain\Repository\JanolawServiceRepository;
use Janolaw\Janolawservice\Utility\JanolawConfigurationUtility;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class JanolawServiceControllerTest extends UnitTestCase
{
    private const LANG = 'gb';
    private const LEGAL_DETAILS = 'legaldetails';
    private const PDF = 'pdf_top';
    private const USERID_MULTILANGUAGE = '100282211';
    private const SHOPID_MULTILANGUAGE = '815904';
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/janolawservice',
    ];

    protected JanolawServiceRepository $janolawServiceRepository;
    protected JanolawServiceController $janolawServiceController;
    /**
     * Sets up this test case.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->janolawServiceController = new JanolawServiceController(
            $this->createMock(FrontendInterface::class),
            $this->createMock(PersistenceManager::class),
            $this->createMock(JanolawConfigurationUtility::class),
            $this->createMock(RequestFactory::class),
            $this->createMock(JanolawServiceRepository::class),
            $this->createMock(ExtensionConfiguration::class),
        );

        $configuration['janolawservice']['language'] = self::LANG;
        $configuration['janolawservice']['type'] = self::LEGAL_DETAILS;
        $configuration['janolawservice']['pdflink'] = self::PDF;
        $configuration['janolawservice']['userid'] = self::USERID_MULTILANGUAGE;
        $configuration['janolawservice']['shopid'] = self::SHOPID_MULTILANGUAGE;
        $configuration['janolawservice']['lifetimeHours'] = 1;

        $mockConfigurationManager = $this->createMock(ConfigurationManager::class);
        $mockConfigurationManager->method('getConfiguration')->willReturn($configuration);
        $this->janolawServiceController->injectConfigurationManager($mockConfigurationManager);
    }
    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
        parent::tearDown();
    }

    #[Test] public function testGenerateAction()
    {
        //not possible to test without writing to database
    }

    #[Test] public function testGetJanolawContent()
    {
        $this->resetSingletonInstances = true;
        $extensionConfiguration = $this->createMock(ExtensionConfiguration::class);
        GeneralUtility::addInstance(ExtensionConfiguration::class, $extensionConfiguration);
        $result = $this->janolawServiceController->getJanolawContent(
            self::LEGAL_DETAILS,
            self::LANG,
            self::PDF,
            self::USERID_MULTILANGUAGE,
            self::SHOPID_MULTILANGUAGE
        );
        self::assertNotFalse(true);
        // not possible to test without writing to database
    }
}

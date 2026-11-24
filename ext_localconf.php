<?php

declare(strict_types=1);
defined('TYPO3') or die();

use Janolaw\Janolawservice\Controller\JanolawServiceController;
use TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Log\Writer\DatabaseWriter;
use TYPO3\CMS\Core\Log\Writer\FileWriter;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin(
    'Janolawservice',
    'Showjanolawservice',
    [JanolawServiceController::class => 'generate'],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

// Log warnings of the plugin controller to sys_log (backend module "Log") and to the default log file
$GLOBALS['TYPO3_CONF_VARS']['LOG']['Janolaw']['Janolawservice']['Controller']['writerConfiguration'] ??= [
    LogLevel::WARNING => [
        DatabaseWriter::class => [],
        FileWriter::class => [],
    ],
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['janolaw_janolawservices'] ??= [];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['janolaw_janolawservices']['frontend'] ??= VariableFrontend::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['janolaw_janolawservices']['backend'] ??= Typo3DatabaseBackend::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['janolaw_janolawservices']['groups'] ??= ['pages'];

<?php

declare(strict_types=1);
defined('TYPO3') or die();

use Janolaw\Janolawservice\Controller\JanolawServiceController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin(
    'Janolawservice',
    'Showjanolawservice',
    [JanolawServiceController::class => 'generate'],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

if (!(isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['janolaw_janolawservices']))) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['janolaw_janolawservices'] = [];
}
if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['janolaw_janolawservices']['frontend'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['janolaw_janolawservices']['frontend'] = 'TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend';
}
if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['janolaw_janolawservices']['backend'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['janolaw_janolawservices']['backend'] = 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend';
}
if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['janolaw_janolawservices']['groups'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['janolaw_janolawservices']['groups'] = ['pages'];
}

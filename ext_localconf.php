<?php

use Janolaw\Janolawservice\Controller\JanolawServiceController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
defined('TYPO3') or die();


ExtensionUtility::configurePlugin(
    'Janolawservice',
    'Showjanolawservice',
    [JanolawServiceController::class => 'generate']
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

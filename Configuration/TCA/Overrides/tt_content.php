<?php

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

$flexForm = 'FILE:EXT:janolawservice/Configuration/FlexForms/FF_JanolawService_ShowJanolawservice.xml';

if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() >= 14) {
    // TYPO3 v14+: FlexForm is attached via the plugin registration (adds the "Plugin" tab automatically)
    ExtensionUtility::registerPlugin(
        'Janolawservice',
        'Showjanolawservice',
        'Show janolaw Service',
        'janolawservice-plugin',
        'plugins',
        '',
        $flexForm,
    );
    return;
}

// TYPO3 v13
$pluginSignature = ExtensionUtility::registerPlugin(
    'Janolawservice',
    'Showjanolawservice',
    'Show janolaw Service',
    'janolawservice-plugin',
);
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $pluginSignature,
    'after:subheader',
);
ExtensionManagementUtility::addPiFlexFormValue('*', $flexForm, $pluginSignature);

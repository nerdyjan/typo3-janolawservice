<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

call_user_func(
    function () {
        // Einbindung Flexform
        $pluginSignature = 'janolawservice_showjanolawservice';
        ExtensionUtility::registerPlugin(
            'Janolawservice',
            'Showjanolawservice',
            'Show janolaw Service'
        );

        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--div--;Configuration,pi_flexform,',
            $pluginSignature,
            'after:subheader',
        );

        ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:janolawservice/Configuration/FlexForms/FF_JanolawService_ShowJanolawservice.xml',
            $pluginSignature,
        );
    }
);

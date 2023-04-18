<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

call_user_func(
    function () {
        // Einbindung Flexform
        $pluginSignature = 'janolawservice_showjanolawservice';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
        ExtensionUtility::registerPlugin(
            'Janolawservice',
            'Showjanolawservice',
            'Show janolaw Service'
        );
        ExtensionManagementUtility::addPiFlexFormValue(
            $pluginSignature,
            'FILE:EXT:janolawservice/Configuration/FlexForms/FF_JanolawService_ShowJanolawservice.xml'
        );
    }
);

<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice',
        'label' => 'type',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'iconfile' => 'EXT:janolawservice/Resources/Public/Icons/Extension.svg',
    ],
    'types' => [
        '1' => [
            'showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, type, userid, shopid, external, legacy_language, pdf, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, starttime, endtime',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'tx_janolawservice_domain_model_janolawservice',
                'foreign_table_where' => 'AND {#tx_janolawservice_domain_model_janolawservice}.{#pid}=###CURRENT_PID### AND {#tx_janolawservice_domain_model_janolawservice}.{#sys_language_uid} IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, (int)date('m'), (int)date('d'), (int)date('Y')),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, (int)date('m'), (int)date('d'), (int)date('Y')),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'type' => [
            'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.type',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'required' => true,
                'eval' => 'trim',
            ],
        ],
        'userid' => [
            'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.userid',
            'config' => [
                'type' => 'number',
                'size' => 10,
                'required' => true,
            ],
        ],
        'shopid' => [
            'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.shopid',
            'config' => [
                'type' => 'number',
                'size' => 10,
                'required' => true,
            ],
        ],
        'external' => [
            'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.external',
            'config' => [
                'type' => 'text',
                'eval' => 'trim',
            ],
        ],
        'legacy_language' => [
            'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.legacy_language',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'pdf' => [
            'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.pdf',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
    ],
];

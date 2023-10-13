<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice',
        'label' => 'type',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'dividers2tabs' => true,
        'versioningWS' => true,

        'languageField' => 'sys_language_uid',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'type,userid, shopid,external,legacy_language,pdf',
        'iconfile' => 'EXT:janolawservice/Resources/Public/Icons/tx_janolawservice_domain_model_janolawservice.gif',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,--palette--;;1,type,userid,shopid,external,legacy_language, pdf,--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,starttime,endtime'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0],
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    'label' => '',
                    'value' => 0,
                ],
                'foreign_table' => 'tx_janolawservice_domain_model_janolawservice',
                'foreign_table_where' => 'AND tx_janolawservice_domain_model_janolawservice.pid=###CURRENT_PID### AND tx_janolawservice_domain_model_janolawservice.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],

        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],

        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'renderType' => 'datetime',
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'renderType' => 'datetime',
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],

        'type' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.type',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'required' => true,
                'eval' => 'trim',
            ],
        ],
        'userid' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.userid',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'required' => true,
                'eval' => 'number',
            ],
        ],
        'shopid' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.shopid',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'required' => true,
                'eval' => 'number',
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
            'exclude' => 0,
            'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.legacy_language',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'pdf' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.pdf',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
    ],
];

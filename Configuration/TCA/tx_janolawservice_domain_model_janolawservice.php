<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice',
		'label' => 'type',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'type,userid, shopid,content,legacy_language,pdf',
		'iconfile' => 'EXT:janolawservice/Resources/Public/Icons/tx_janolawservice_domain_model_janolawservice.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, type, userid, shopid, content, legacy_language, pdf',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,--palette--;;1,type,userid,shopid,content,legacy_language, pdf,--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,starttime,endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_janolawservice_domain_model_janolawservice',
				'foreign_table_where' => 'AND tx_janolawservice_domain_model_janolawservice.pid=###CURRENT_PID### AND tx_janolawservice_domain_model_janolawservice.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),

		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
	
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'renderType' => 'inputDateTime',
				'type' => 'input',
				'size' => 13,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
				'behaviour' => array(
					'allowLanguageSynchronization' => TRUE
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'renderType' => 'inputDateTime',
				'type' => 'input',
				'size' => 13,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
				'behaviour' => array( 
					'allowLanguageSynchronization' => TRUE
				),
			),
		),

		'type' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.type',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'userid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.userid',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int,required'
			)
		),
		'shopid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.shopid',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int,required'
			)
		),
		'content' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.content',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim',
				'wizards' => array(
					'RTE' => array(
						'icon' => 'actions-wizard-rte',
						'notNewRecords'=> 1,
						'RTEonly' => 1,
						'module' => array(
							'name' => 'wizard_rich_text_editor',
							'urlParameters' => array(
								'mode' => 'wizard',
								'act' => 'wizard_rte.php'
							)
						),
						'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
						'type' => 'script'
					)
				)
			),
		),
		'legacy_language' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.legacy_language',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'pdf' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:janolawservice/Resources/Private/Language/locallang_db.xlf:tx_janolawservice_domain_model_janolawservice.pdf',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
	),
);

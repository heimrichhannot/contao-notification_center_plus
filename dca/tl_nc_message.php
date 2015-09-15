<?php

$arrDca = &$GLOBALS['TL_DCA']['tl_nc_message'];

/**
 * Palettes
 */
$arrDca['palettes']['email'] = str_replace('email_template', 'email_template,convertPtoBr,addStylesheets',
	$arrDca['palettes']['email']);

/**
 * Subpalettes
 */
$arrDca['subpalettes']['addStylesheets'] = 'inlineStylesheets,headerStylesheets';
$arrDca['palettes']['__selector__'][] = 'addStylesheets';

/**
 * Fields
 */
$arrDca['fields']['inlineStylesheets'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_nc_message']['inlineStylesheets'],
	'exclude' => true,
	'inputType' => 'fileTree',
	'eval' => array(
		'fieldType' => 'checkbox',
		'filesOnly' => true,
		'multiple' => true,
		'extensions' => 'css',
		'tl_class' => 'w50 clr'
	),
	'sql' => 'blob NULL'
);

$arrDca['fields']['headerStylesheets'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_nc_message']['headerStylesheets'],
	'exclude' => true,
	'inputType' => 'fileTree',
	'eval' => array(
		'fieldType' => 'checkbox',
		'filesOnly' => true,
		'multiple' => true,
		'extensions' => 'css',
		'tl_class' => 'w50'
	),
	'sql' => 'blob NULL'
);

$arrDca['fields']['addStylesheets'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_nc_message']['addStylesheets'],
	'exclude'                 => true,
	'filter'                  => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true, 'tl_class' => 'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);

$arrDca['fields']['convertPtoBr'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_nc_message']['convertPtoBr'],
	'exclude'                 => true,
	'filter'                  => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class' => 'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);
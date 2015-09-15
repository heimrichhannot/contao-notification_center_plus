<?php

$arrDca = &$GLOBALS['TL_DCA']['tl_module'];

/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['lostPasswordNotificationCenterPlus'] = str_replace(
	'reg_jumpTo', 'changePasswordJumpTo,reg_jumpTo',
	$GLOBALS['TL_DCA']['tl_module']['palettes']['lostPasswordNotificationCenter']
);

/**
 * Fields
 */
$arrDca['fields']['changePasswordJumpTo'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['changePasswordJumpTo'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'foreignKey'              => 'tl_page.title',
	'eval'                    => array('fieldType'=>'radio'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'",
	'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
);
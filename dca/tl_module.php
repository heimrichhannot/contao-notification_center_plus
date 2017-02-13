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
$arrDca['fields']['changePasswordJumpTo'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['changePasswordJumpTo'],
    'exclude'                 => true,
    'inputType'               => 'pageTree',
    'foreignKey'              => 'tl_page.title',
    'eval'                    => ['fieldType' =>'radio'],
    'sql'                     => "int(10) unsigned NOT NULL default '0'",
    'relation'                => ['type' =>'hasOne', 'load' =>'lazy']];
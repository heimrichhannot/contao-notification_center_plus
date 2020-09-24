<?php

$dca = &$GLOBALS['TL_DCA']['tl_nc_message'];

/**
 * Palettes
 */
$dca['palettes']['email'] = str_replace('email_template', 'email_template,convertPtoBr,addStylesheets', $dca['palettes']['email']);

/**
 * Subpalettes
 */
$dca['subpalettes']['addStylesheets'] = 'inlineStylesheets,headerStylesheets';
$dca['palettes']['__selector__'][]    = 'addStylesheets';

/**
 * Fields
 */
$dca['fields']['inlineStylesheets'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_nc_message']['inlineStylesheets'],
    'exclude'   => true,
    'inputType' => 'fileTree',
    'eval'      => [
        'fieldType'  => 'checkbox',
        'filesOnly'  => true,
        'multiple'   => true,
        'extensions' => 'css',
        'tl_class'   => 'w50 clr'
    ],
    'sql'       => 'blob NULL'
];

$dca['fields']['headerStylesheets'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_nc_message']['headerStylesheets'],
    'exclude'   => true,
    'inputType' => 'fileTree',
    'eval'      => [
        'fieldType'  => 'checkbox',
        'filesOnly'  => true,
        'multiple'   => true,
        'extensions' => 'css',
        'tl_class'   => 'w50'
    ],
    'sql'       => 'blob NULL'
];

$dca['fields']['addStylesheets'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_nc_message']['addStylesheets'],
    'exclude'   => true,
    'filter'    => true,
    'inputType' => 'checkbox',
    'eval'      => ['submitOnChange' => true, 'tl_class' => 'w50'],
    'sql'       => "char(1) NOT NULL default ''"
];

$dca['fields']['convertPtoBr'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_nc_message']['convertPtoBr'],
    'exclude'   => true,
    'filter'    => true,
    'inputType' => 'checkbox',
    'eval'      => ['tl_class' => 'w50'],
    'sql'       => "char(1) NOT NULL default ''"
];

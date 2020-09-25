<?php

$dca = &$GLOBALS['TL_DCA']['tl_nc_language'];

/**
 * Palettes
 */
$dca['palettes']['__selector__'][] = 'ics_attachment';
$dca['palettes']['__selector__'][] = 'ics_add_time';
$dca['palettes']['email']        = str_replace('attachment_tokens', 'attachment_tokens,ics_attachment', $dca['palettes']['email']);

/**
 * Subpalettes
 */
$dca['subpalettes']['ics_attachment'] = 'ics_title_field,ics_description_field,ics_street_field,ics_postal_field,ics_city_field,ics_country_field,ics_location_field,ics_url_field,ics_start_date_field,ics_end_date_field,ics_add_time';
$dca['subpalettes']['ics_add_time']   = 'ics_add_time_field,ics_start_time_field,ics_end_time_field';

/**
 * Fields
 */
$fields = [
    'ics_attachment'  => [
        'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['ics_attachment'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50 clr', 'submitOnChange' => true],
        'sql'       => "char(1) NOT NULL default ''"
    ],
    'ics_title_field' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['ics_title_field'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'nc_tokens', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''"
    ],
    'ics_description_field' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['ics_description_field'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'nc_tokens', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''"
    ],
    'ics_location_field' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['ics_location_field'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'nc_tokens', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''"
    ],
    'ics_street_field' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['ics_street_field'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'nc_tokens', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''"
    ],
    'ics_postal_field' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['ics_postal_field'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'nc_tokens', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''"
    ],
    'ics_city_field' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['ics_city_field'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'nc_tokens', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''"
    ],
    'ics_country_field' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['ics_country_field'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'nc_tokens', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''"
    ],
    'ics_url_field' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['ics_url_field'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'nc_tokens', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''"
    ],
    'ics_start_date_field' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['ics_start_date_field'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'nc_tokens', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''"
    ],
    'ics_end_date_field' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['ics_end_date_field'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'nc_tokens', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''"
    ],
    'ics_add_time'  => [
        'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['ics_add_time'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50 clr', 'submitOnChange' => true],
        'sql'       => "char(1) NOT NULL default ''"
    ],
    'ics_add_time_field' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['ics_add_time_field'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'nc_tokens', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''"
    ],
    'ics_start_time_field' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['ics_start_time_field'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'nc_tokens', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''"
    ],
    'ics_end_time_field' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['ics_end_time_field'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['rgxp' => 'nc_tokens', 'decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
        'sql'       => "varchar(255) NOT NULL default ''"
    ],
];

$dca['fields'] = array_merge(is_array($dca['fields']) ? $dca['fields'] : [], $fields);

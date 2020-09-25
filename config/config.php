<?php

/**
 * notification_center_plus extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2015 Heimrich & Hannot GmbH
 * @author     Dennis Patzer <d.patzer@heimrich-hannot.de>
 * @license    LGPL
 */

/**
 * Frontend Modules
 */
$GLOBALS['FE_MOD']['user']['lostPasswordNotificationCenterPlus'] = 'HeimrichHannot\NotificationCenterPlus\ModulePasswordNotificationCenterPlus';

/**
 * Notification Center Gateways
 */
$GLOBALS['NOTIFICATION_CENTER']['GATEWAY']['email'] = 'HeimrichHannot\NotificationCenterPlus\Gateway\Email';

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_nc_queue'] = 'HeimrichHannot\NotificationCenterPlus\QueuedMessagePlus';

/**
 * Notification Center Notification Types
 */
foreach ($GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao'] as $strType => $strField) {
    if (isset($GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao'][$strType]['email_html'])) {
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao'][$strType]['email_html'] = array_merge($GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao'][$strType]['email_html'], ['salutation_user', 'salutation_form', 'salutation_billing_address']);
    }

    if (isset($GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao'][$strType]['email_text'])) {
        $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao'][$strType]['email_text'] = array_merge($GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao'][$strType]['email_text'], ['salutation_user', 'salutation_form', 'salutation_billing_address']);
    }
}

/**
 * Notification Center Tokens
 */
foreach ($GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'] as $strType => $arrTypes) {
    foreach ($arrTypes as $strConcreteType => &$arrType) {
        foreach (['email_subject', 'email_text', 'email_html'] as $strName) {
            if (isset($arrType[$strName])) {
                $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'][$strType][$strConcreteType][$strName] = array_unique(array_merge([
                    'env_*',
                    'page_*',
                    'user_*',
                    'date',
                    'last_update',
                ], $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'][$strType][$strConcreteType][$strName]));
            }
        }

        if (isset($arrType['attachment_tokens'])) {
            $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'][$strType][$strConcreteType]['attachment_tokens'] = array_unique(array_merge([
                'ics_attachment_token',
            ], $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'][$strType][$strConcreteType]['attachment_tokens']));
        }
    }
}

foreach ($GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'] as $strType => $arrTypes) {
    foreach ($arrTypes as $strConcreteType => &$arrType) {
        foreach (['email_subject', 'email_text', 'email_html'] as $strName) {
            if (isset($arrType[$strName])) {
                $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'][$strType][$strConcreteType][$strName] = array_unique(array_merge([
                    'env_*',
                    'page_*',
                    'user_*',
                    'date',
                    'last_update',
                ], $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'][$strType][$strConcreteType][$strName]));
            }
        }

        if (isset($arrType['attachment_tokens'])) {
            $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'][$strType][$strConcreteType]['attachment_tokens'] = array_unique(array_merge([
                'ics_attachment_token',
            ], $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'][$strType][$strConcreteType]['attachment_tokens']));
        }

        foreach (['ics_title_field', 'ics_description_field', 'ics_street_field', 'ics_postal_field', 'ics_city_field', 'ics_country_field', 'ics_location_field', 'ics_url_field', 'ics_start_date_field', 'ics_end_date_field', 'ics_add_time_field', 'ics_start_time_field', 'ics_end_time_field'] as $strName) {
            if (!isset($GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'][$strType][$strConcreteType][$strName])) {
                $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'][$strType][$strConcreteType][$strName] = $GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'][$strType][$strConcreteType]['email_subject'];
            }
        }
    }
}

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['sendNotificationMessage'][] = ['\HeimrichHannot\NotificationCenterPlus\NotificationCenterPlus', 'addTokens'];

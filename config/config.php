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
 * Notification Center Notification Types
 */
foreach ($GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao'] as $strType => $strField) {
	if (isset($GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao'][$strType]['email_html']))
		$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao'][$strType]['email_html'] = array_merge(
			$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao'][$strType]['email_html'],
			array('salutation_user', 'salutation_form', 'salutation_billing_address')
		);

	if (isset($GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao'][$strType]['email_text']))
		$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao'][$strType]['email_text'] = array_merge(
			$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao'][$strType]['email_text'],
			array('salutation_user', 'salutation_form', 'salutation_billing_address')
		);
}

/**
 * Notification Center Tokens
 */
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao']['core_form']['email_text'][] = 'env_*';
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao']['core_form']['email_text'][] = 'page_*';
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao']['core_form']['email_text'][] = 'user_*';
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao']['core_form']['email_text'][] = 'date';
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao']['core_form']['email_text'][] = 'last_update';

$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao']['core_form']['email_subject'][] = 'env_*';
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao']['core_form']['email_subject'][] = 'page_*';
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao']['core_form']['email_subject'][] = 'user_*';
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao']['core_form']['email_subject'][] = 'date';
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao']['core_form']['email_subject'][] = 'last_update';

$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao']['core_form']['email_html'][] = 'env_*';
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao']['core_form']['email_html'][] = 'page_*';
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao']['core_form']['email_html'][] = 'user_*';
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao']['core_form']['email_html'][] = 'date';
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['contao']['core_form']['email_html'][] = 'last_update';

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['sendNotificationMessage'][] = array('\HeimrichHannot\NotificationCenterPlus\NotificationCenterPlus', 'addTokens');

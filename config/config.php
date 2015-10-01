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
 * Hooks
 */
$GLOBALS['TL_HOOKS']['sendNotificationMessage'][] = array('\HeimrichHannot\NotificationCenterPlus\NotificationCenterPlus', 'addTokens');
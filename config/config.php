<?php

/**
 * notification_center_plus extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2015 Heimrich & Hannot GmbH
 * @author     Dennis Patzer <d.patzer@heimrich-hannot.de>
 * @license    LGPL
 */

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
			array('salutation_user', 'salutation_form')
		);
}

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['sendNotificationMessage'][] = array('\HeimrichHannot\NotificationCenterPlus\NotificationCenterPlus', 'addTokens');
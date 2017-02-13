<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(
    [
	'HeimrichHannot',]
);


/**
 * Register the classes
 */
ClassLoader::addClasses(
    [
	// Library
	'HeimrichHannot\NotificationCenterPlus\Gateway\Email'                        => 'system/modules/notification_center_plus/library/NotificationCenterPlus/Gateway/Email.php',
	'HeimrichHannot\NotificationCenterPlus\Util\StringUtil'                      => 'system/modules/notification_center_plus/library/NotificationCenterPlus/Util/StringUtil.php',
	'HeimrichHannot\NotificationCenterPlus\NotificationCenterPlus'               => 'system/modules/notification_center_plus/library/NotificationCenterPlus/NotificationCenterPlus.php',
	'HeimrichHannot\NotificationCenterPlus\MessageDraft\EmailMessageDraft'       => 'system/modules/notification_center_plus/library/NotificationCenterPlus/MessageDraft/EmailMessageDraft.php',
	'HeimrichHannot\NotificationCenterPlus\MessageModel'                         => 'system/modules/notification_center_plus/library/NotificationCenterPlus/Model/MessageModel.php',

	// Modules
	'HeimrichHannot\NotificationCenterPlus\ModulePasswordNotificationCenterPlus' => 'system/modules/notification_center_plus/modules/ModulePasswordNotificationCenterPlus.php',]
);

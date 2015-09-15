<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'HeimrichHannot',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Library
	'HeimrichHannot\NotificationCenterPlus\Gateway\Email'                  => 'system/modules/notification_center_plus/library/NotificationCenterPlus/Gateway/Email.php',
	'HeimrichHannot\NotificationCenterPlus\NotificationCenterPlus'         => 'system/modules/notification_center_plus/library/NotificationCenterPlus/NotificationCenterPlus.php',
	'HeimrichHannot\NotificationCenterPlus\MessageDraft\EmailMessageDraft' => 'system/modules/notification_center_plus/library/NotificationCenterPlus/MessageDraft/EmailMessageDraft.php',
));

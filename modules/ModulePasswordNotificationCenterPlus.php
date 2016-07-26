<?php

namespace HeimrichHannot\NotificationCenterPlus;


class ModulePasswordNotificationCenterPlus extends \ModulePassword
{
	/**
	 * Send a lost password e-mail
	 * @param object
	 */
	protected function sendPasswordLink($objMember)
	{
		$objNotification = \NotificationCenter\Model\Notification::findByPk($this->nc_notification);

		if ($objNotification === null) {
			$this->log('The notification was not found ID ' . $this->nc_notification, __METHOD__, TL_ERROR);
			return;
		}

		$confirmationId = md5(uniqid(mt_rand(), true));

		// Store the confirmation ID
		$objMember = \MemberModel::findByPk($objMember->id);
		$objMember->activation = $confirmationId;
		$objMember->save();

		$arrTokens = array();

		// Add member tokens
		foreach ($objMember->row() as $k => $v)
		{
			if (\Validator::isBinaryUuid($v))
			{
				$v = \StringUtil::binToUuid($v);
			}

			$arrTokens['member_' . $k] = $v;
		}

		// FIX: Add salutation token
		$arrTokens['salutation_user'] = NotificationCenterPlus::createSalutation($GLOBALS['TL_LANGUAGE'], $objMember);
		// ENDFIX

		$arrTokens['recipient_email'] = $objMember->email;
		$arrTokens['domain'] = \Idna::decode(\Environment::get('host'));
		$arrTokens['link'] = \Idna::decode(\Environment::get('base')) . \Environment::get('request') . (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos(\Environment::get('request'), '?') !== false) ? '&' : '?') . 'token=' . $confirmationId;

		// FIX: Add custom change password jump to
		if (($objJumpTo = $this->objModel->getRelated('changePasswordJumpTo')) !== null)
		{
			$arrTokens['link'] = \Idna::decode(\Environment::get('base')) . \Controller::generateFrontendUrl($objJumpTo->row(), '?token=' . $confirmationId);
		}
		// ENDFIX

		$objNotification->send($arrTokens, $GLOBALS['TL_LANGUAGE']);
		$this->log('A new password has been requested for user ID ' . $objMember->id . ' (' . $objMember->email . ')', __METHOD__, TL_ACCESS);

		// Check whether there is a jumpTo page
		if (($objJumpTo = $this->objModel->getRelated('jumpTo')) !== null)
		{
			$this->jumpToOrReload($objJumpTo->row());
		}

		$this->reload();
	}
}

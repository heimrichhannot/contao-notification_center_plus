<?php

namespace HeimrichHannot\NotificationCenterPlus;


use Contao\MemberModel;
use HeimrichHannot\Haste\Util\Salutations;
use HeimrichHannot\Request\Request;
use HeimrichHannot\StatusMessages\StatusMessage;
use function Sodium\version_string;

class ModulePasswordNotificationCenterPlus extends \ModulePassword
{
    /**
     * Generate the module
     */
    protected function compile()
    {
        $strParent = parent::compile();

        if ($this->Template->error == $GLOBALS['TL_LANG']['MSC']['accountNotFound'])
        {
            StatusMessage::addError($GLOBALS['TL_LANG']['MSC']['accountNotFound'], $this->objModel->id);
        }

        if (!StatusMessage::isEmpty($this->objModel->id))
        {
            $this->Template->error = StatusMessage::generate($this->objModel->id);
        }

	if(Request::getGet('token') && MemberModel::findOneByActivation(Request::getGet('token'))) {
            $this->Template->reset = true;
        }

        return $strParent;
    }
    
    /**
     * Send a lost password e-mail
     *
     * @param \MemberModel $objMember
     */
    protected function sendPasswordLink($objMember)
    {
        $objNotification = \NotificationCenter\Model\Notification::findByPk($this->nc_notification);

        if ($objNotification === null)
        {
            $this->log('The notification was not found ID ' . $this->nc_notification, __METHOD__, TL_ERROR);

            return;
        }

        if (version_compare(VERSION, '4.4', '<=') && version_compare(BUILD, '12', '<'))
        {
            $strToken = md5(uniqid(mt_rand(), true));
        }
        else
        {
            $strToken = 'PW' . substr(md5(uniqid(mt_rand(), true)), 2);
        }

        // Store the confirmation ID
        $objMember             = \MemberModel::findByPk($objMember->id);
        $objMember->activation = $strToken;
        $objMember->save();

        $arrTokens = [];

        // Add member tokens
        foreach ($objMember->row() as $k => $v)
        {
            if (\Validator::isBinaryUuid($v))
            {
                $v = \StringUtil::binToUuid($v);
            }

            $arrTokens['member_' . $k] = specialchars($v);
        }

        // FIX: Add salutation token
        $arrTokens['salutation_user'] = Salutations::createSalutation($GLOBALS['TL_LANGUAGE'], $objMember);
        // ENDFIX

        $arrTokens['recipient_email'] = $objMember->email;
        $arrTokens['domain']          = \Idna::decode(\Environment::get('host'));
        $arrTokens['link']            =
            \Idna::decode(\Environment::get('base')) . \Environment::get('request') . (($GLOBALS['TL_CONFIG']['disableAlias']
                                                                                        || strpos(
                                                                                               \Environment::get('request'),
                                                                                               '?'
                                                                                           ) !== false) ? '&' : '?') . 'token=' . $strToken;

        // FIX: Add custom change password jump to
        if (($objJumpTo = $this->objModel->getRelated('changePasswordJumpTo')) !== null)
        {
            $arrTokens['link'] =
                \Idna::decode(\Environment::get('base')) . \Controller::generateFrontendUrl($objJumpTo->row()) . '?token=' . $strToken;
        }
        // ENDFIX

        $objNotification->send($arrTokens, $GLOBALS['TL_LANGUAGE']);
        $this->log('A new password has been requested for user ID ' . $objMember->id . ' (' . $objMember->email . ')', __METHOD__, TL_ACCESS);

        // Check whether there is a jumpTo page
        if (($objJumpTo = $this->objModel->getRelated('jumpTo')) !== null)
        {
            $this->jumpToOrReload($objJumpTo->row());
        }

        StatusMessage::addSuccess(
            sprintf($GLOBALS['TL_LANG']['notification_center_plus']['sendPasswordLink']['messageSuccess'], $arrTokens['recipient_email']),
            $this->objModel->id
        );

        $this->reload();
    }
}


<?php

namespace HeimrichHannot\NotificationCenterPlus;


use Contao\Input;
use Contao\MemberModel;
use Contao\System;
use HeimrichHannot\Haste\Util\Salutations;
use HeimrichHannot\StatusMessages\StatusMessage;

class ModulePasswordNotificationCenterPlus extends \ModulePassword
{
    /**
     * Generate the module
     */
    protected function compile()
    {
        parent::compile();

        if ($this->Template->error == $GLOBALS['TL_LANG']['MSC']['accountNotFound'])
        {
            StatusMessage::addError($GLOBALS['TL_LANG']['MSC']['accountNotFound'], $this->objModel->id);
        }

        if (!StatusMessage::isEmpty($this->objModel->id))
        {
            $this->Template->error = StatusMessage::generate($this->objModel->id);
        }



        if (Input::get('token') && MemberModel::findOneByActivation(Input::get('token'))) {
            $this->Template->reset = true;
        }
    }
    
    /**
     * Send a lost password e-mail
     *
     * @param \MemberModel $objMember
     */
    protected function sendPasswordLink($objMember)
    {
        $objNotification = \NotificationCenter\Model\Notification::findByPk($this->nc_notification);

        if ($objNotification === null) {
            $this->log('The notification was not found ID ' . $this->nc_notification, __METHOD__, TL_ERROR);
            return;
        }

        $token         = md5(uniqid(mt_rand(), true));
        $contaoVersion = VERSION . '.' . BUILD;
        if (version_compare($contaoVersion, '4.7.0', '>=')) {
            /** @var \Contao\CoreBundle\OptIn\OptIn $optIn */
            $optIn      = System::getContainer()->get('contao.opt-in');
            $optInToken = $optIn->create('pw', $objMember->email, ['tl_member' => [$objMember->id]]);
            $token      = $optInToken->getIdentifier();
        } elseif (version_compare($contaoVersion, '4.4.12', '>=')) {
            $token = 'PW' . substr($token, 2);
        }

        if (!version_compare($contaoVersion, '4.7.0', '>=')) {
            // Store the token
            $objMember             = \MemberModel::findByPk($objMember->id);
            $objMember->activation = $token;
            $objMember->save();
        }

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
                                                                                           ) !== false) ? '&' : '?') . 'token=' . $token;

        // FIX: Add custom change password jump to
        if (($objJumpTo = $this->objModel->getRelated('changePasswordJumpTo')) !== null)
        {
            $arrTokens['link'] =
                \Idna::decode(\Environment::get('base')) . \Controller::generateFrontendUrl($objJumpTo->row()) . '?token=' . $token;
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


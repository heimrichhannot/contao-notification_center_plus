<?php

namespace HeimrichHannot\NotificationCenterPlus\Gateway;

use HeimrichHannot\NotificationCenterPlus\MessageDraft\EmailMessageDraft;
use NotificationCenter\Model\Message;
use NotificationCenter\Model\Language;
use NotificationCenter\MessageDraft\MessageDraftInterface;

class Email extends \NotificationCenter\Gateway\Email
{

    /**
     * Returns a MessageDraft
     *
     * @param   Message
     * @param   array
     * @param   string
     *
     * @return  MessageDraftInterface|null (if no draft could be found)
     */
    public function createDraft(Message $objMessage, array $arrTokens, $strLanguage = '')
    {
        if ($strLanguage == '')
        {
            $strLanguage = $GLOBALS['TL_LANGUAGE'];
        }

        if (($objLanguage = Language::findByMessageAndLanguageOrFallback($objMessage, $strLanguage)) === null)
        {
            \System::log(
                sprintf('Could not find matching language or fallback for message ID "%s" and language "%s".', $objMessage->id, $strLanguage),
                __METHOD__,
                TL_ERROR
            );

            return null;
        }

        return new EmailMessageDraft($objMessage, $objLanguage, $arrTokens);
    }

}

<?php

/**
 * notification_center_plus extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2015 Heimrich & Hannot GmbH
 * @author     Dennis Patzer <d.patzer@heimrich-hannot.de>
 * @license    LGPL
 */

namespace HeimrichHannot\NotificationCenterPlus\MessageDraft;

use HeimrichHannot\Haste\Dca\General;
use HeimrichHannot\Haste\Util\DOMUtil;
use HeimrichHannot\NotificationCenterPlus\NotificationCenterPlus;
use NotificationCenter\MessageDraft\EmailMessageDraft as NcEmailMessageDraft;
use NotificationCenter\Model\Language;
use NotificationCenter\Model\Message;

class EmailMessageDraft extends NcEmailMessageDraft
{
    public function __construct(Message $objMessage, Language $objLanguage, $arrTokens)
    {
        // add overridable properties
        if (is_array($arrTokens['overridableProperties'] ?? null) && is_array($arrTokens['overridableEntities'] ?? null)) {
            foreach ($arrTokens['overridableProperties'] as $strProperty) {
                $objLanguage->{$strProperty} = General::getOverridableProperty($strProperty, array_merge([$objLanguage], $arrTokens['overridableEntities']));
            }
        }

        parent::__construct(
            $objMessage,
            $objLanguage,
            $arrTokens
        );
    }


    /**
     * Returns the html body as a string
     *
     * @return  string
     */
    public function getHtmlBody()
    {
        $strHtmlBody = parent::getHtmlBody();

        if (!empty($strHtmlBody)) {
            if ($this->getMessage()->convertPtoBr) {
                $strHtmlBody = NotificationCenterPlus::convertPToBr($strHtmlBody);
            }

            if ($strHtmlBody && $this->getMessage()->addStylesheets) {
                $strHtmlBody = NotificationCenterPlus::addHeaderCss($strHtmlBody, $this->getMessage());

                $strHtmlBody = DOMUtil::convertToInlineCss(
                    $strHtmlBody,
                    implode(
                        ' ',
                        NotificationCenterPlus::getStylesheetContents($this->getMessage(), NotificationCenterPlus::CSS_MODE_INLINE)
                    )
                );
            }
        }


        return $strHtmlBody;
    }
}
<?php

/**
 * notification_center_plus extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2015 Heimrich & Hannot GmbH
 * @author     Dennis Patzer <d.patzer@heimrich-hannot.de>
 * @license    LGPL
 */

namespace HeimrichHannot\NotificationCenterPlus\MessageDraft;

use HeimrichHannot\HastePlus\DOM;
use HeimrichHannot\NotificationCenterPlus\NotificationCenterPlus;
use NotificationCenter\Util\String;

class EmailMessageDraft extends \NotificationCenter\MessageDraft\EmailMessageDraft
{

	/**
	 * Returns the html body as a string
	 *
	 * @return  string
	 */
	public function getHtmlBody()
	{
		$strHtmlBody = parent::getHtmlBody();

		if ($this->getMessage()->convertPtoBr)
			$strHtmlBody = NotificationCenterPlus::convertPToBr($strHtmlBody);

		if ($strHtmlBody && $this->getMessage()->addStylesheets)
		{
			$strHtmlBody = NotificationCenterPlus::addHeaderCss($strHtmlBody, $this->getMessage());

			$strHtmlBody = DOM::convertToInlineCss($strHtmlBody, implode(' ',
				NotificationCenterPlus::getStylesheetContents($this->getMessage(), NotificationCenterPlus::CSS_MODE_INLINE)));
		}

		ob_start();
		echo $strHtmlBody;
		file_put_contents('/home/dennis/debug.txt', ob_get_contents(), FILE_APPEND);
		ob_end_clean();

		return $strHtmlBody;
	}

}

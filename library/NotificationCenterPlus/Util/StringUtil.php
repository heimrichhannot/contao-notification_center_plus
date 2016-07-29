<?php

/**
 * notification_center extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2008-2015, terminal42
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    LGPL
 */

namespace HeimrichHannot\NotificationCenterPlus\Util;


class StringUtil extends \NotificationCenter\Util\StringUtil
{
	/**
	 * Gets an array of valid attachments of a token field
	 *
	 * @param string $strAttachmentTokens
	 * @param array  $arrTokens
	 *
	 * @return array
	 */
	public static function getTokenAttachments($strAttachmentTokens, array $arrTokens)
	{
		$arrAttachments = array();

		if ($strAttachmentTokens == '') {
			return $arrAttachments;
		}

		foreach (trimsplit(',', $strAttachmentTokens) as $strToken) {
			if (version_compare(VERSION . '.' . BUILD, '3.5.1', '<')) {
				$strParsedToken = \String::parseSimpleTokens($strToken, $arrTokens);
			} else {
				$strParsedToken = \StringUtil::parseSimpleTokens($strToken, $arrTokens);
			}

			foreach (trimsplit(',', $strParsedToken) as $strFile)
			{
				$strFileFull = TL_ROOT . '/' . str_replace($arrTokens['env_url'] . '/', '', $strFile);

				if (is_file($strFileFull))
				{
					$arrAttachments[$strFile] = $strFileFull;
				}
			}
		}

		return $arrAttachments;
	}
}
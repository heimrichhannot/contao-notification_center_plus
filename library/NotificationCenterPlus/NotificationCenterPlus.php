<?php

namespace HeimrichHannot\NotificationCenterPlus;

use Avisota\Contao\Message\Core\Event\PostRenderMessageContentEvent;
use Avisota\Contao\Message\Core\Event\RenderMessageContentEvent;
use HeimrichHannot\HastePlus\Arrays;
use HeimrichHannot\HastePlus\Environment;
use HeimrichHannot\HastePlus\Files;
use NotificationCenter\Model\Message;
use NotificationCenter\Model\Notification;

class NotificationCenterPlus
{

	const CSS_MODE_INLINE = 'inline';
	const CSS_MODE_HEADER = 'header';

	public static function addHeaderCss($strText, Message $objMessage)
	{
		$arrHeaderStylesheetContents =
			static::getStylesheetContents($objMessage, static::CSS_MODE_HEADER);

		if (!empty($arrHeaderStylesheetContents))
		{
			$doc = \phpQuery::newDocumentHTML($strText);

			pq('html > head')->append(sprintf('<style type="text/css">%s</style>', implode(' ',
				$arrHeaderStylesheetContents)));

			return $doc->htmlOuter();
		}

		return $strText;
	}

	// some mail clients can't handle margin'ed p elements :-(
	public static function convertPToBr($strText)
	{
		return preg_replace('@</p>[\n\r\s]*<p>@i', '<br><br>', $strText);
	}

	public static function getStylesheetPaths(Message $objMessage, $strMode)
	{
		$arrStylesheets = deserialize($strMode == static::CSS_MODE_INLINE ?
			$objMessage->inlineStylesheets : $objMessage->headerStylesheets, true);

		if (!empty($arrStylesheets))
		{
			$arrStylesheetPaths = array_map(function($strUuid) {
				$strPath = TL_ROOT . '/' . Files::getPathFromUuid($strUuid);

				return (file_exists($strPath) ? $strPath : '');
			}, $arrStylesheets);

			// remove non-found stylesheets
			return array_filter($arrStylesheetPaths);
		}

		return array();
	}

	public static function getStylesheetContents(Message $objMessage, $strMode)
	{
		return array_map('file_get_contents', static::getStylesheetPaths($objMessage, $strMode));
	}

	public function addTokens($objMessage, &$arrTokens, $language, $objGatewayModel)
	{
		if (!isset($arrTokens['salutation_user']))
			$arrTokens['salutation_user'] = static::createSalutation($language, \FrontendUser::getInstance());

		if (!isset($arrTokens['salutation_form']))
			$arrTokens['salutation_form'] = static::createSalutation($language, array(
				'gender' => $arrTokens['form_gender'],
				'title' => $arrTokens['form_title'],
				'lastname' => $arrTokens['form_lastname']
			));

		if (in_array('isotope', \ModuleLoader::getActive()))
		{
			if (!isset($arrTokens['billing_address_form']))
				$arrTokens['salutation_billing_address'] = static::createSalutation($language, array(
					'gender' => $arrTokens['billing_address_gender'],
					'title' => $arrTokens['billing_address_title'],
					'lastname' => $arrTokens['billing_address_lastname']
				));
		}

		return true;
	}

	/**
	 * @param $strLanguage
	 * @param $varEntity object or array
	 *
	 * @return string
	 */
	public static function createSalutation($strLanguage, $varEntity)
	{
		if (is_array($varEntity))
			$varEntity = Arrays::arrayToObject($varEntity);

		$blnHasLastname = $varEntity->lastname;
		$blnHasTitle = $varEntity->title && $varEntity->title != '-' && $varEntity->title != 'Titel' && $varEntity->title != 'Title';

		switch ($strLanguage)
		{
			case 'en':
				if ($blnHasLastname)
				{
					if ($blnHasTitle)
						$strSalutation =
							$GLOBALS['TL_LANG']['notification_center_plus']['salutation'] . ' ' . $varEntity->title;
					else
						$strSalutation =
							$GLOBALS['TL_LANG']['notification_center_plus']['salutation' .
							($varEntity->gender == 'female' ? 'Female' : 'Male')
							];

					return $strSalutation .  ' ' . $varEntity->lastname;
				}
				else
				{
					return $GLOBALS['TL_LANG']['notification_center_plus']['salutationGeneric'];
				}
				break;
			default:
				// de
				if ($blnHasLastname)
				{
					$strSalutation = $GLOBALS['TL_LANG']['notification_center_plus'][
									 'salutation' . ($varEntity->gender == 'female' ? 'Female' : 'Male')
					];

					if ($blnHasTitle)
						$strSalutation .= ' ' . $varEntity->title;

					return $strSalutation .  ' ' . $varEntity->lastname;
				}
				else
				{
					return $GLOBALS['TL_LANG']['notification_center_plus']['salutationGeneric'];
				}
				break;
		}
	}

	public static function sendNotification($intId, $arrTokens)
	{
		if (($objNotification = Notification::findByPk($intId)) !== null)
		{
			$objNotification->send($arrTokens, $GLOBALS['TL_LANGUAGE']);
		}
	}
}

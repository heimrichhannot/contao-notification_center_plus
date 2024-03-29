<?php

namespace HeimrichHannot\NotificationCenterPlus;

use Contao\Controller;
use Contao\Environment;
use Contao\FilesModel;
use Contao\FrontendUser;
use Contao\StringUtil;
use Contao\System;
use Contao\Validator;
use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use HeimrichHannot\Haste\Util\Files;
use HeimrichHannot\Haste\Util\Salutations;
use HeimrichHannot\Haste\Util\Url;
use NotificationCenter\Model\Language;
use NotificationCenter\Model\Message;
use NotificationCenter\Model\Notification;

class NotificationCenterPlus
{
    const CSS_MODE_INLINE = 'inline';
    const CSS_MODE_HEADER = 'header';

    public static function addHeaderCss($strText, Message $objMessage)
    {
        $arrHeaderStylesheetContents = static::getStylesheetContents($objMessage, static::CSS_MODE_HEADER);

        if (!empty($arrHeaderStylesheetContents)) {
            $doc = \phpQuery::newDocumentHTML($strText);

            pq('html > head')->append(sprintf('<style type="text/css">%s</style>', implode(' ', $arrHeaderStylesheetContents)));

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
        $arrStylesheets = deserialize($strMode == static::CSS_MODE_INLINE ? $objMessage->inlineStylesheets : $objMessage->headerStylesheets, true);

        if (!empty($arrStylesheets)) {
            $arrStylesheetPaths = array_map(function ($strUuid) {
                $strPath = TL_ROOT . '/' . Files::getPathFromUuid($strUuid);

                return (file_exists($strPath) ? $strPath : '');
            }, $arrStylesheets);

            // remove non-found stylesheets
            return array_filter($arrStylesheetPaths);
        }

        return [];
    }

    public static function getStylesheetContents(Message $objMessage, $strMode)
    {
        return array_map('file_get_contents', static::getStylesheetPaths($objMessage, $strMode));
    }

    public function addTokens($objMessage, &$arrTokens, $strLanguage, $objGatewayModel)
    {
        if (version_compare(VERSION, '4.4', '>=') && !\defined('TL_FILES_URL')) {
            Controller::setStaticUrls();
        }
        if (!isset($arrTokens['salutation_user'])) {
            $arrTokens['salutation_user'] = Salutations::createSalutation($strLanguage, \FrontendUser::getInstance());
        }

        if (!isset($arrTokens['salutation_form'])) {
            $arrTokens['salutation_form'] = Salutations::createSalutation($strLanguage, [
                'gender'   => $arrTokens['form_value_gender'] ?? $arrTokens['form_salutation'] ?? '',
                'title'    => $arrTokens['form_title'] ?? $arrTokens['form_academicTitle'] ?? '',
                'lastname' => $arrTokens['form_lastname'] ?? '',
            ]);
        }

        if (in_array('isotope', \ModuleLoader::getActive())) {
            if (!isset($arrTokens['billing_address_form'])) {
                $arrTokens['salutation_billing_address'] = Salutations::createSalutation($strLanguage, [
                    'gender'   => $arrTokens['billing_address_gender'],
                    'title'    => $arrTokens['billing_address_title'],
                    'lastname' => $arrTokens['billing_address_lastname'],
                ]);
            }
        }

        $this->addContextTokens($objMessage, $arrTokens, $strLanguage);
        $this->addIcsAttachmentToken($objMessage, $arrTokens, $strLanguage);

        return true;
    }

    public static function getTokensFromEntity($objEntity, $strPrefix, $arrFields = [])
    {
        $arrTokens = [];

        foreach ($objEntity->row() as $strKey => $varValue) {
            if (empty($arrFields) || in_array($strKey, $arrFields)) {
                $arrTokens[$strPrefix . '_' . lcfirst($strKey)] = $varValue;
            }
        }

        return $arrTokens;
    }

    /**
     *
     * Add contao core tokens, as long as the cron job does not have these information
     * on sending mail in queue mode
     *
     * @param $arrTokens
     * @param $strLanguage
     *
     * @return bool false if context_tokens has been set already (required by cron)
     */
    protected function addContextTokens($objMessage, &$tokens, $strLanguage)
    {
        // add context tokens only once (queue will trigger this function again, and tokens might be overwritten)
        if (isset($tokens['context_tokens'])) {
            return false;
        }

        $contextTokens = [];

        $contextTokens['context_tokens'] = true;

        // add environment variables as token
        $contextTokens['env_host']         = \Idna::decode(\Environment::get('host'));
        $contextTokens['env_http_host']    = \Idna::decode(\Environment::get('httpHost'));
        $contextTokens['env_url']          = \Idna::decode(\Environment::get('url'));
        $contextTokens['env_path']         = \Idna::decode(\Environment::get('base'));
        $contextTokens['env_request']      = \Idna::decode(\Environment::get('indexFreeRequest'));
        $contextTokens['env_request_path'] = \Idna::decode(Url::removeAllParametersFromUri(\Environment::get('indexFreeRequest')));
        $contextTokens['env_ip']           = \Idna::decode(\Environment::get('ip'));
        $contextTokens['env_referer']      = \System::getReferer();
        $contextTokens['env_files_url']    = TL_FILES_URL;
        $assetUrl                      = TL_ASSETS_URL;
        $contextTokens['env_plugins_url']  = $assetUrl;
        $contextTokens['env_script_url']   = $assetUrl;


        // add date tokens
        $contextTokens['date']        = date(\Config::get('dateFormat'));
        $contextTokens['last_update'] = \Controller::replaceInsertTags('{{last_update}}', false);

        if (false === json_encode($contextTokens)) {
            System::log("Invalid value when adding context tokens (notification center plus).", __METHOD__, TL_ERROR);
        } else {
            $tokens = array_merge($tokens, $contextTokens);
        }

        $contextTokens = [];

        if (TL_MODE == 'FE') {
            // add current page as token
            global $objPage;

            if ($objPage !== null) {
                foreach ($objPage->row() as $key => $value) {
                    // skip fields leading to issues on json_encode
                    if (\json_encode($value) !== false) {
                        $tokens['user_'.$key] = $value;
                    }
                    $contextTokens['page_' . $key] = $value;
                }

                if ($objPage->pageTitle == '') {
                    $contextTokens['pageTitle'] = $objPage->title;
                } elseif ($objPage->parentPageTitle == '') {
                    $contextTokens['parentPageTitle'] = $objPage->parentTitle;
                } elseif ($objPage->mainPageTitle == '') {
                    $contextTokens['mainPageTitle'] = $objPage->mainTitle;
                }
            }

            // add user attributes as token
            if (FE_USER_LOGGED_IN) {
                Controller::loadDataContainer('tl_member');
                $arrUserData = FrontendUser::getInstance()->getData();

                if (is_array($arrUserData)) {
                    foreach ($arrUserData as $key => $value) {
                        if (is_array($value) && in_array(($GLOBALS['TL_DCA']['tl_member']['fields'][$key]['inputType'] ?? []), ['fileTree', 'multifileupload'])) {
                            $files = [];
                            foreach ($value as $uuid) {
                                if (null === ($path = $this->getFilePath($uuid))) {
                                    continue;
                                }
                                $files[] = $path;
                            }

                            $value = $files;
                        } elseif (!is_array($value) && Validator::isBinaryUuid($value)) {
                            $value = StringUtil::binToUuid($value);

                            if (null === ($path = $this->getFilePath($value))) {
                                continue;
                            }

                            $value = $path;
                        }

                        $contextTokens['user_' . $key] = $value;
                    }
                }
            }
        }

        if (false === json_encode($contextTokens)) {
            System::log("Invalid value when adding context tokens (notification center plus).", __METHOD__, TL_ERROR);
            return false;
        }

        $tokens = array_merge($tokens, $contextTokens);
    }

    public function getFilePath($uuid)
    {
        if(!Validator::isUuid($uuid)) {
            return null;
        }

        if(null === ($file = FilesModel::findByUuid($uuid))) {
            return null;
        }

        return $file->path;
    }


    public static function sendNotification($intId, $arrTokens)
    {
        if (($objNotification = Notification::findByPk($intId)) !== null) {
            $objNotification->send($arrTokens, $GLOBALS['TL_LANGUAGE']);
        }
    }

    public static function getNotificationsAsOptions($strType)
    {
        $arrChoices       = [];
        $objNotifications = \Database::getInstance()->execute("SELECT id,title FROM tl_nc_notification WHERE type='$strType' ORDER BY title");

        while ($objNotifications->next()) {
            $arrChoices[$objNotifications->id] = $objNotifications->title;
        }

        return $arrChoices;
    }

    public function getNotificationMessagesAsOptions()
    {
        $arrOptions = [];

        if (($objMessages = Message::findAll()) === null) {
            return $arrOptions;
        }

        while ($objMessages->next()) {
            if (($objNotification = $objMessages->getRelated('pid')) === null) {
                continue;
            }

            $arrOptions[$objNotification->title][$objMessages->id] = $objMessages->title;
        }

        return $arrOptions;
    }

    protected function addIcsAttachmentToken($message, &$tokens, $language)
    {
        // avoid running multiple times if used in a queue
        if (isset($tokens['ics_attachment_token']) && $tokens['ics_attachment_token']) {
            return;
        }

        // get the language
        if (null === ($languageModel = Language::findByMessageAndLanguageOrFallback($message, $language)) || !$languageModel->ics_attachment) {
            return;
        }

        // remove token hashes
        $titleField       = str_replace('#', '', $languageModel->ics_title_field);
        $descriptionField = str_replace('#', '', $languageModel->ics_description_field);
        $streetField      = str_replace('#', '', $languageModel->ics_street_field);
        $postalField      = str_replace('#', '', $languageModel->ics_postal_field);
        $cityField        = str_replace('#', '', $languageModel->ics_city_field);
        $countryField     = str_replace('#', '', $languageModel->ics_country_field);
        $locationField    = str_replace('#', '', $languageModel->ics_location_field);
        $urlField         = str_replace('#', '', $languageModel->ics_url_field);
        $startDateField   = str_replace('#', '', $languageModel->ics_start_date_field);
        $endDateField     = str_replace('#', '', $languageModel->ics_end_date_field);
        $addTimeField     = str_replace('#', '', $languageModel->ics_add_time_field);
        $startTimeField   = str_replace('#', '', $languageModel->ics_start_time_field);
        $endTimeField     = str_replace('#', '', $languageModel->ics_end_time_field);

        // prepare data
        $addTime = $languageModel->ics_add_time && $addTimeField && isset($tokens[$addTimeField]) && $tokens[$addTimeField];
        $end     = null;

        if ($addTime && $startTimeField && isset($tokens[$startTimeField]) && $tokens[$startTimeField]) {
            $start = (new \DateTime())->setTimestamp($tokens[$startTimeField]);
        } else {
            $start = (new \DateTime())->setTimestamp($tokens[$startDateField]);
            $start->setTime(0, 0, 0);
        }

        if ($endDateField && isset($tokens[$endDateField]) && $tokens[$endDateField]) {
            // workaround for allday events
            $end = (new \DateTime())->setTimestamp($tokens[$endDateField]);
            $end->setTime(0, 0, 0);
        }

        if ($addTime && $endTimeField && isset($tokens[$endTimeField]) && $tokens[$endTimeField]) {
            $end = (new \DateTime())->setTimestamp($tokens[$endTimeField]);
        }

        // create the ics event
        $event = new Event();

        $event->setNoTime(!$addTime);
        $event->setDtStart($start);

        if (null !== $end) {
            $event->setDtEnd($end);
        }

        if ($titleField && isset($tokens[$titleField])) {
            $event->setSummary(strip_tags($tokens[$titleField]));
        }

        if ($descriptionField && isset($tokens[$descriptionField])) {
            $description = preg_replace('@<br\s*/?>@i', "\n", html_entity_decode($tokens[$descriptionField]));
            $description = preg_replace('@</p>\s*<p>@i', "\n\n", $description);
            $description = str_replace(['<p>', '</p>'], '', $description);
            $description = html_entity_decode(StringUtil::restoreBasicEntities($description));
            $description = str_replace('&nbsp;', '', $description);

            // replace links
            $description = preg_replace('|<a[^h]+href\s?=\s?"([^"]+)"[^>]*>([^<]+)</a>|i', '$2 ($1)', $description);

            $description = strip_tags($description);

            $event->setDescription($description);
        }

        if ($urlField && isset($tokens[$urlField])) {
            $event->setUrl($tokens[$urlField]);
        }

        // compose location out of various fields
        $locationData = [];

        if ($locationField && !empty($tokens[$locationField])) {
            $locationData['location'] = $tokens[$locationField];
        }

        if ($streetField && !empty($tokens[$streetField])) {
            $locationData['street'] = $tokens[$streetField];
        }

        if ($postalField && !empty($tokens[$postalField])) {
            $locationData['postal'] = $tokens[$postalField];
        }

        if ($cityField && !empty($tokens[$cityField])) {
            $locationData['city'] = $tokens[$cityField];
        }

        if ($countryField && !empty($tokens[$countryField])) {
            $locationData['country'] = $tokens[$countryField];
        }

        if (!empty($locationData)) {
            $result = [];

            if (isset($locationData['location'])) {
                $result[] = $locationData['location'];
            }

            if (isset($locationData['street'])) {
                $result[] = $locationData['street'];
            }

            if (isset($locationData['postal']) && isset($locationData['city'])) {
                $result[] = $locationData['postal'] . ' ' . $locationData['city'];
            } elseif (isset($locationData['city'])) {
                $result[] = $locationData['city'];
            }

            if (isset($locationData['country'])) {
                $result[] = $locationData['country'];
            }

            $event->setLocation(implode(', ', $result));
        }

        // create the ics calendar
        $calendar = new Calendar(Environment::get('url'));

        $calendar->setTimezone(\Config::get('timeZone'));

        // HOOK: add custom logic
        if (isset($GLOBALS['TL_HOOKS']['modifyIcsFile']) && \is_array($GLOBALS['TL_HOOKS']['modifyIcsFile']))
        {
            foreach ($GLOBALS['TL_HOOKS']['modifyIcsFile'] as $callback)
            {
                System::importStatic($callback[0])->{$callback[1]}($calendar, $event, $tokens, $languageModel);
            }
        }

        $calendar->addComponent($event);

        $ics = $calendar->render();

        if (!$ics) {
            return;
        }

        $path = 'system/tmp/date-' . (md5(rand(0, 9999999999))) . '.ics';

        file_put_contents(TL_ROOT . '/' . $path, $ics);

        $tokens['ics_attachment_token'] = $path;
    }
}

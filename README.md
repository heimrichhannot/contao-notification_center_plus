# Notification Center Plus

Extends the notification center with additional features.

## General features
- header and inline css can be defined (automatic inlining support)
- additional salutation tokens for the currently logged in frontend user and the form (##salutation_user##, ##salutation_form##)
- the lost password module now has a separate jumpTo for changing the password

## Tokens

### context tokens

Notification center plus adds context tokens, like environment variables, and page information to the token array, as the 
are not available if sending later per cron.


| Token        | Example
| ------------- |:-------------:|
| ##context_tokens## | true
| ##env_host##      | www.example.com |
| ##env_http_host## | www.example.com  | 
| ##env_url## | https://www.example.com  | 
| ##env_path## | https://www.example.com  | 
| ##env_request## | https://www.example.com/en/testpage?query=test&submit=true  | 
| ##env_request_path## | https://www.example.com/en/testpage  |
| ##env_ip## | 216.58.212.131  |
| ##env_referer## | https://www.example.com/en/home |
| ##env_files_url## | TL_FILES_URL |
| ##env_plugins_url## | TL_ASSETS_URL |
| ##env_script_url## | TL_ASSETS_URL |
| ##date## | 30.03.2017 |
| ##last_update## | 29.03.2017 17:05 |
| ##page_id## | 2 |
| ##page_pid## | 1 |
| ##page_sorting## | 1056 |
| ##page_tstamp## | 1490106980 |
| ##page_title## | Home |
| ##page_alias## | /en/home |
| ##page_type## | regular |
| ##page_pageTitle## | Homepage |
| ##page_language## | en |
| ##page_robots## | index,follow |
| ##page_description## | null |
| ##page_redirect## | permanent |
| ##page_jumpTo## |  |
| ##page_url## |  |
| ##page_target## |  |
| ##page_dns## | www.example.com |
| ##page_staticFiles## |  |
| ##page_staticPlugins## |  |
| ##page_fallback## |  |
| ##page_adminEmail## |  |
| ##page_dateFormat## | d.m.Y |
| ##page_timeFormat## | H.i |
| ##page_datimFormat## | d.m.Y H.i |
| ##page_createSitemap## |  |
| ##page_sitemapName## |  |
| ##page_useSSL## | |
| ##page_autoforward## |  |
| ##page_protected## | false |
| ##page_groups## | false |
| ##page_includeLayout## |  |
| ##page_layout## | 1 |
| ##page_mobileLayout## | 0 |
| ##page_includeCache## |  |
| ##page_cache## | false |
| ##page_includeChmod## |  |
| ##page_cuser## | 0 |
| ##page_cgroup## | 0 |
| ##page_chmod## |  |
| ##page_noSearch## |  |
| ##page_cssClass## |  |
| ##page_sitemap## | map_default |
| ##page_hide## |  |
| ##page_guests## |  |
| ##page_tabindex## | 0 |
| ##page_accesskey## |  |
| ##page_published## | 1 |
| ##page_start## | |
| ##page_stop## |  |
| ##page_newsCategories_param## |  |
| ##page_youtube_template## |  |
| ##page_youtubePrivacy## |  |
| ##page_youtubePrivacyTemplate## |  |
| ##page_dlh_googlemaps_apikey## |  |
| ##page_mainAlias## | home |
| ##page_mainTitle## | Home |
| ##page_mainPageTitle## | Homepage |
| ##page_parentAlias## | home |
| ##page_parentTitle## | Homepage |
| ##page_parentPageTitle## | Homepage |
| ##page_folderUrl## | home/ |
| ##page_rootId## | 1 |
| ##page_rootAlias## | example-website |
| ##page_rootTitle## | Example Website |
| ##page_rootPageTitle## | Example Website |
| ##page_domain## |  |
| ##page_rootLanguage## | en |
| ##page_rootIsPublic## | true |
| ##page_rootIsFallback## | true |
| ##page_rootUseSSL## |  |
| ##page_rootFallbackLanguage## | en |
| ##page_trail## | [1]|
| ##page_hasJQuery## | 1 |
| ##page_hasMooTools## |  |
| ##page_isMobile## | false |
| ##page_template## | fe_page |
| ##page_templateGroup## | templates/example.com |
| ##page_outputFormat## | html5 |
| ##pageTitle## | Homepage |


### if, then, else

If you want to check for a condition inside your message, you can use the following example:

```
Association: {if form_association!=''}##form_association##
{else}unknown
{endif}
```

**Hints:**

{if form_association!=''}
- must use no quote for the L-Value
- must have no space between the L-Value and the oparator
- must have no space between the operator and the R-Value
- the L-Value or R-Value represent the key within the token array $arrData['form_association'] = 'heimrichhannot'

## Using overridable haste_plus properties

You now have the option to use overridable properties available in [heimrichhannot/contao-haste_plus](https://github.com/heimrichhannot/contao-haste_plus) also in this module. Simply add code similiar to the following to a "sendNotificationMessage"-Hook

```
public static function sendNotificationMessage(Message $objMessage, &$arrTokens, $strLanguage, $objGatewayModel)
{
    $arrTokens['overridableProperties'] = ['email_sender_address', 'email_sender_name', 'email_replyTo', 'email_subject'];
    $arrTokens['overridableEntities'] = [['tl_calendar_events', $intEventId]];
}
```

```$objEvent``` is the entity in whose dca you called General::addOverridableFields() in order to add overridable fields from other dca's. Please see ```General::addOverridableFields()``` and ```General::getOverridableProperty()``` in [heimrichhannot/contao-haste_plus](https://github.com/heimrichhannot/contao-haste_plus) for more details.
# Notification Center Plus

Extends the notification center with additional features.

## General features
- header and inline css can be defined (automatic inlining support)
- additional salutation tokens for the currently logged in frontend user and the form (##salutation_user##, ##salutation_form##)
- the lost password module now has a separate jumpTo for changing the password

## Tokens

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
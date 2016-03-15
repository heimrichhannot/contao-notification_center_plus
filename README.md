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
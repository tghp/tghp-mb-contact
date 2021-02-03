# TGHP MB Contact
Re-usable plugin using metabox.io and MB Frontend Submission to provide code-powerable and extensible forms. Forms are defined as in with metabox, with some extra features added.

## Extra Metabox Parameters
### `options`
Key | Value
--- | ---
`email->email` | Email address to send submisions to
`email->title` | Title of the form
`redirect` | URL to redirect to after submission
`class` | Extra CSS classes to add to the form wrapper element
`button_class` | Extra CSS classes to add to buttons
`submit_class` | Extra CSS classes to add to the submit button
`submit_text_sr_only` | Wrap submit button in a .sr-only span

## Shortcode

`[tghpcontact_form]`

Parameter | Description 
--- | ---
`id` | ID of form to output
`title` | Output this title
`hide` | Whether to hide the form initially or not
`hide_button_label` | Label for button that shows form. Default: 'Show Form'
`hide_button_class` | CSS classes added to button that shows form. Default: 'button button-secondary'
`background` | Applies a background colour to inputs which have a value (for fake placeholders, see Native)

## Available Filters

`tghpcontact_forms`

Parameter | Description 
--- | ---
`$contactForms` | Key/value array of forms, add to this to add a form. With your form ID as the key and metabox definition as the value. 

---

`tghpcontact_fields`

Parameter | Description 
--- | ---
`$contactFields` | Array of fields for this form
`$form` | The form passed to the filter

---

`tghpcontact_fields_{$formId}`

`$formId` is the ID of the form relevant (the key in the key/value array)

Parameter | Description 
--- | ---
`$contactFields` | Array of fields for this form
`$form` | The form passed to the filter

---

`tghpcontact_email_subject`

Parameter | Description 
--- | ---
`$subject` | The email subject
`$config` | Data about the relevant form (comes from MB Frontend Submission)
`$postId` | ID of the post created we are editing the email for

---

`tghpcontact_email_content`

Parameter | Description 
--- | ---
`$subject` | The email content
`$config` | Data about the relevant form (comes from MB Frontend Submission)
`$postId` | ID of the post created we are editing the email for

## Useful features
### Recaptcha
A new recaptcha field type is available provided by this plugin:

```
array(
    'id' => 'g-recaptcha',
    'type' => 'recaptcha',
),
```

However a site and secret key is provided. The plugin will look for these using getenv in the following format:

* `RECAPTCHA_KEY_SITE_{$formId}`
* `RECAPTCHA_KEY_SECRET_{$formId}`

So a recaptcha instance and environment variable for each form is required. This is important as only one site/secret key can be used once on page. So if we were to use just one pair, only one form of any ID would be placeable on a page. This way you can place more 

### JavaScript
#### Message event
When a message is trigged (confirmation or error) an event `tghpcontact:message-show` will get dispatched on the body element. This can be used to add scroll to message logic. This event is passed a single parameter of a jQuery object matching the message element.

Example usage:

```
var $header = $('#masthead'),
    $window = $(window);
$(body).on('tghpcontact:message-shown', function (e, el) {
    $window.scrollTop($(el).offset().top - $masthead.height() - 20);
});
```


It's important this plugin doesn't implement this as each site will need different offset positions due to header sizes. 
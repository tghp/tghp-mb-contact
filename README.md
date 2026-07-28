# TGHP MB Contact
Re-usable plugin using metabox.io and MB Frontend Submission to provide code-powerable and extensible forms. Forms are defined as in with metabox, with some extra features added.

## Extra Metabox Parameters
### `options`
Key | Value
--- | ---
`delete_after_processing` | After all hooks are fired, delete the post. Useful for facilitating transient submissions utilising an integration and with no desire to store locally (e.g. newsletter subscriptions).<br>Post will not be deleted in any case if `_tghpcontact_do_not_delete` metafield has a value of '1'. This can be used to prevent this deletion in case of a failure.
`email->email` | Email address to send submisions to
`email->title` | Title of the form
`redirect` | URL to redirect to after submission
`class` | Extra CSS classes to add to the form wrapper element
`button_class` | Extra CSS classes to add to buttons
`submit_class` | Extra CSS classes to add to the submit button
`submit_text_sr_only` | Wrap submit button in a .sr-only span
`ajax` | Boolean. Enable AJAX for instances of the form 
`confirmation` | Confirmation message

## Extra Metabox Field Parameters
Key | Value
--- | ---
`email` | Boolean. Whether the field is added to the generated email 

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

#### reCAPTCHA v3

v2 (checkbox) is the default. To use v3 (invisible, score-based) for a form, either set an environment variable:

* `RECAPTCHA_VERSION_{$formId}=3`

or set `'version' => 3` on the recaptcha field definition. The env var wins if both are set. Note the site/secret keys are version-specific — a form switched to v3 needs a key pair registered as v3 in the Google reCAPTCHA admin.

Optional score threshold, via env var or field parameter (env wins):

Env | Field parameter | Default | Description
--- | --- | --- | ---
`RECAPTCHA_SCORE_THRESHOLD_{$formId}` | `score_threshold` | `0.5` | Minimum score (0–1) for a submission to pass

##### How v3 works here

The client side is delegated to MB Frontend Submission's native v3 support: `tghpcontact_form()` passes the site key to the `[mb_frontend_form]` shortcode as `recaptcha_key`, and MBFS enqueues api.js, executes grecaptcha at submit time (with its hardcoded `mbfs` action) and posts the token as `mbfs_recaptcha_token`. No widget is rendered — the recaptcha field outputs nothing for v3.

Verification stays with this plugin's validator (`recaptcha_secret` is deliberately not passed to MBFS — its own check ignores the score, and siteverify tokens are single-use so only one side can verify). The validator checks success, the `mbfs` action and the score threshold.

Requirements/limitations:

* Requires an MB Frontend Submission version with native v3 support (`recaptcha_key` form config) — added in MBFS 2.1.0 (2020-01-21). Note the internals we rely on (the `mbfs_recaptcha_token` field name and hardcoded `mbfs` action) were verified against MBFS 4.4.2; very old MBFS builds may differ
* One v3 key pair per page — MBFS localises a single `mbFrontendForm.recaptchaKey` global, so multiple v3 forms on one page must share a key pair (v2 + v3 forms can coexist)
* Google's v3 badge appears bottom-right of the page; if you hide it with CSS you must include Google's disclosure text near the form

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
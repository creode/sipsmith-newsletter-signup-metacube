## Sipsmith Newsletter Signup

This document outlines the main functionality for this plugin. The aim is to create a way of talking to an API using the [Marketing Signup](https://gitlab.creode.co.uk/creode/libraries/marketing-signup) libraries as a base.

The goal is to make it easy to switch out which API to use when interacting with the Newsletter forms. There are talks about Sipsmith moving to Salesforce so building the plugin in this way should make it much smoother to move the library used.

## Used Actions

* `newsletter_consent_submit_action` - Trigger functionality to talk to the API when any forms are submitted containing the Newsletter Signup checkbox. Currently this is the User Register page and checkout but the initial plugin can be changed to take advantage of more at a later date.

## Used Filters

* `newsletter_consent_field_checkbox_copy` - Changes the copy of the Marketing checkbox on the website to make it specific to Sipsmith.

## Environment Variables

In order to pass an API key to the Marketing Signup libraries we check if we have a the following constants:

* `NEWSLETTER_API_KEY` - If this variable is not defined we don't submit any data to the library so it is important this is setup.
* `NEWSLETTER_LIST_ID` - If this variable is not defined we don't submit any data to the library so it is important this is setup.

These constants should be defined inside the `wp-config.php` file or the `local-config.php` file inside WordPress.

## Newsletter Submission App Wide

This plugin provides the functionality to submit newsletter configuration app wide. The goal is to ensure that all parts of the app that need to send signup data use this single place to do it. The class currently used for this is the `src/class-sipsmith-newsletter-signup-submission.php` file.

### Defined Filters

The plugin applies the following filters:

* `sipsmith_newsletter_signup_submission_amend_data($data)` - This filter is applied before sending data and will allow us to add extra dynamic fields in different places of the app. The goal is to give us the flexibility to amend information before it is passed to a third party.

### Defined Actions

The plugin does the following actions:

* `sipsmith_newsletter_signup_submission_post_submit($sent_data, $signup_response)` - This action is ran after submitting data to the third party service. This will allow us to carry out any actions like register someone onto a coupon or use the submitted data for other things.

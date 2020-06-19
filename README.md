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

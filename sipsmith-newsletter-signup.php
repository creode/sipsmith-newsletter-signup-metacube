<?php
/**
 * Plugin Name: Sipsmith Newsletter Signup
 * Plugin URI: https://www.creode.co.uk
 * Description: This plugin handles newsletter signup specific actions on the website.
 * Author: Creode

 * Author URI: https://www.creode.co.uk
 * Version: 1.0.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package CGB
 */

require_once WP_PLUGIN_DIR . '/sipsmith-newsletter-signup/src/class-sipsmith-newsletter-signup.php';

new Sipsmith_Newsletter_Signup();

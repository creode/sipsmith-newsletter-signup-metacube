<?php

use Creode\MarketingSignupMailchimp\MailchimpSignup;

/**
* Main entrypoint for the Plugin.
*/
class Sipsmith_Newsletter_Signup {
	/**
	 * Classes needed as dependencies of this plugin.
	 *
	 * @var array
	 */
	private $dependencies = array(
		ABSPATH . '../../vendor/creode/marketing-signup/src/MarketingSignupSenderInterface.php',
		ABSPATH . '../../vendor/creode/marketing-signup/src/MarketingSignupTypeInterface.php',
		ABSPATH . '../../vendor/creode/marketing-signup/src/MarketingSignupSenderBase.php',
		ABSPATH . '../../vendor/creode/marketing-signup/src/MarketingSignupTypeBase.php',
		ABSPATH . '../../vendor/drewm/mailchimp-api/src/Mailchimp.php',
		ABSPATH . '../../vendor/creode/marketing-signup-mailchimp/src/MailchimpSignup.php',
		ABSPATH . '../../vendor/creode/marketing-signup-mailchimp/src/MailchimpSignupSender.php',
	);

	/**
	 * Constructor for the plugin.
	 */
	public function __construct() {
		$this->register_actions();
		$this->register_filters();
	}

	/**
	 * Register any WordPress actions for this plugin.
	 */
	public function register_actions() {
		add_action( 'newsletter_consent_fields_handle_submission', array( $this, 'newsletter_consent_submit_action' ), 10, 3 );
	}

	/**
	 * Register any filters for this plugin.
	 */
	public function register_filters() {
		// Change the default newsletter consent copy.
		add_filter( 'newsletter_consent_field_checkbox_copy', array( $this, 'amend_newsletter_consent_checkbox_copy' ) );
	}

	/**
	 * Handles submitting the data to chosen newsletter service.
	 *
	 * @param int    $subscription_status Value of subscription box (1 for checked, 0 for not checked).
	 * @param string $email_address Email address of person subscribing.
	 * @param string $newsletter_source Source string of what form was filled out for the subscription.
	 */
	public function newsletter_consent_submit_action( $subscription_status, $email_address, $newsletter_source ) {
		// Don't add to newsletter if consent wasn't checked.
		if ( 1 !== $subscription_status ) {
			return;
		}

		// Check defined API key.
		if ( ! defined( 'NEWSLETTER_API_KEY' ) || NEWSLETTER_API_KEY === '' ) {
			return;
		}

		// Check defined List ID.
		if ( ! defined( 'NEWSLETTER_LIST_ID' ) || NEWSLETTER_LIST_ID === '' ) {
			return;
		}

		$data = array(
			'email_address' => $email_address,
			'status'        => $subscription_status ? 'subscribed' : 'unsubscribed',
			'merge_fields'  => array(
				'SOURCE' => $newsletter_source,
			),
		);

		// Load in any classes I need.
		$this->load_signup_dependencies();

		$newsletter = new MailchimpSignup( $data, array( 'api_key' => NEWSLETTER_API_KEY ), NEWSLETTER_LIST_ID );
		$newsletter->add();
	}
 
	/**
	 * Change the newsletter consent copy for the website.
	 *
	 * @param string $default_copy Default copy generated by the plugin.
	 */
	public function amend_newsletter_consent_checkbox_copy( string $default_copy ) {
		/* translators: %l: Privacy Policy Link */
		return sprintf(
			__( 'I agree to receive occasional updates from the Sipsmith Distillery in line with the <a href="%l">Privacy Policy</a>.', 'sipsmith-newsletter-signup-copy' ),
			'/privacy-policy'
		);
	}

	/**
	 * Loads in any dependency classes for this class.
	 */
	private function load_signup_dependencies() {
		foreach ( $this->dependencies as $dependency_path ) {
			require_once $dependency_path;
		}
	}
}

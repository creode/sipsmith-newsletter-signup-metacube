<?php
/**
 * Sipsmith Newsletter Signup
 *
 * Main entrypoint for the Signup Plugin that contains specific amends to send signup data.
 *
 * @category   Contains specific amends for plugin when sending signup data off to a third party.
 * @package    Sipsmith Newsletter Signup
 * @author     Creode
 * @link       https://www.creode.co.uk
 * @since      1.1.0
 */

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
		__DIR__ . '/class-sipsmith-newsletter-signup-submission.php',
	);

	/**
	 * Constructor for the plugin.
	 */
	public function __construct() {
		$this->register_actions();
	}

	/**
	 * Register any WordPress actions for this plugin.
	 */
	public function register_actions() {
		add_action( 'newsletter_consent_fields_handle_submission', array( $this, 'newsletter_consent_submit_action' ), 10, 3 );
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

		// Check defined API credentials.
		if (
			( ! defined( 'METACUBE_KEY' ) ) ||
			'' === METACUBE_KEY['client_id'] ||
			'' === METACUBE_KEY['client_secret']
		) {
				return;
		}
		if ( ! defined( 'METACUBE_NEWSLETTER_EVENT' ) || METACUBE_NEWSLETTER_EVENT === '' ) {
			return;
		}

		$data = array(
			'EmailAddress'   => $email_address,
			'MarketingOptIn' => 1 === $subscription_status ? true : false,
		);

		// Load in the classes required.
		$this->load_signup_dependencies();

		// Submit data off to Third Party.
		$newsletter_submission = new Sipsmith_Newsletter_Signup_Submission(
			$data,
			METACUBE_KEY,
			METACUBE_NEWSLETTER_EVENT
		);
		$newsletter_submission->submit();
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


<?php
/**
 * Sipsmith Newsletter Signup Submission
 *
 * Class used to talk to a third party service which should be used sitewide in order to keep it in one place.
 *
 * @category   Submission helper for sending signup data off to a third party.
 * @package    Sipsmith Newsletter Signup
 * @author     Creode
 * @link       https://www.creode.co.uk
 * @since      1.1.0
 */

use Creode\MarketingSignupMailchimp\MailchimpSignup;

/**
 * Signup Submission class.
 */
class Sipsmith_Newsletter_Signup_Submission {
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
		ABSPATH . '../../vendor/drewm/mailchimp-api/src/MailChimp.php',
		ABSPATH . '../../vendor/creode/marketing-signup-mailchimp/src/MailchimpSignup.php',
		ABSPATH . '../../vendor/creode/marketing-signup-mailchimp/src/MailchimpSignupSender.php',
	);

	/**
	 * Data used when submitting to API.
	 *
	 * @var array
	 */
	private $data;

	/**
	 * Data used when submitting to API.
	 *
	 * @var array
	 */
	private $api_arguments;

	/**
	 * Data used when submitting to API.
	 *
	 * @var string|bool
	 */
	private $list_id;

	/**
	 * Constructor for Newsletter Signup Submission.
	 *
	 * @param array   $data Data submitted to the Signup API.
	 * @param array   $api_arguments Arguments used by the Specific API.
	 * @param boolean $list_id List ID used to push data into the api.
	 */
	public function __construct( array $data, $api_arguments = array(), $list_id = false ) {
		$this->data          = $data;
		$this->api_arguments = $api_arguments;
		$this->list_id       = $list_id;
	}

	/**
	 * Submits data off to third party newsletter service.
	 */
	public function submit() {
		$this->submit_data();
	}

	/**
	 * Submit Newsletter Data.
	 *
	 * @throws Exception Throws error if API Key is not set.
	 */
	private function submit_data() {
		if ( ! isset( $this->api_arguments['api_key'] ) ) {
			throw new Exception( 'API Key must be provided as part of the api_arguments array.' );
		}

		// Load in any classes I need.
		$this->load_signup_dependencies();

		// Pre Submit filter to amend data.
		$data = apply_filters( 'sipsmith_newsletter_signup_submission_amend_data', $this->data );

		$newsletter      = new MailchimpSignup( $data, array( 'api_key' => $this->api_arguments['api_key'] ), $this->list_id );
		$signup_response = $newsletter->add();

		// Post Submit action.
		do_action( 'sipsmith_newsletter_signup_submission_post_submit', $data, $signup_response );
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

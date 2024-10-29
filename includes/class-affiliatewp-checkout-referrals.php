<?php
/**
 * Checkout Referrals Plugin Bootstrap
 *
 * @package     AffiliateWP Checkout Referrals
 * @subpackage  Core
 * @copyright   Copyright (c) 2021, Sandhills Development, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AffiliateWP_Checkout_Referrals' ) ) {

	/**
	 * Setup class.
	 *
	 * @since 1.1
	 */
	final class AffiliateWP_Checkout_Referrals {

		/**
		 * Holds the instance
		 *
		 * Ensures that only one instance of AffiliateWP_Checkout_Referrals exists in memory at any one
		 * time and it also prevents needing to define globals all over the place.
		 *
		 * TL;DR This is a static property property that holds the singleton instance.
		 *
		 * @var object
		 * @static
		 * @since 1.0
		 */
		private static $instance;

		/**
		 * The version number.
		 *
		 * @since AFFWPN This version is now automatically set by using the version in the
		 *               plugins header.
		 *
		 * @access private
		 * @since  1.1
		 * @var    string
		 */
		private $version = '0.0.0';

		/**
		 * Main plugin file.
		 *
		 * @since 1.1
		 * @var   string
		 */
		private $file = '';

		/**
		 * The integrations handler instance variable
		 *
		 * @since 1.0.0
		 * @var   \Affiliate_WP_Checkout_Referrals_Base
		 */
		public $integrations;

		/**
		 * Main AffiliateWP_Checkout_Referrals Instance
		 *
		 * Insures that only one instance of AffiliateWP_Checkout_Referrals exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 *
		 * @param string $file Main plugin file.
		 * @return \AffiliateWP_Checkout_Referrals The one true plugin instance.
		 */
		public static function instance( $file = null ) {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AffiliateWP_Checkout_Referrals ) ) {

				self::$instance = new AffiliateWP_Checkout_Referrals();

				self::$instance->file    = $file;
				self::$instance->version = get_plugin_data( self::$instance->file, false, false )['Version'] ?? '0.0.0';

				self::$instance->setup_constants();
				self::$instance->includes();
				self::$instance->hooks();
				self::$instance->setup_objects();
			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.0
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'affiliatewp-checkout-referrals' ), '1.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @since 1.0
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'affiliatewp-checkout-referrals' ), '1.0' );
		}

		/**
		 * Constructor Function
		 *
		 * @since 1.0
		 * @access private
		 */
		private function __construct() {
			self::$instance = $this;
		}

		/**
		 * Reset the instance of the class
		 *
		 * @since 1.0
		 * @access public
		 * @static
		 */
		public static function reset() {
			self::$instance = null;
		}

		/**
		 * Sets up plugin constants.
		 *
		 * @since 1.1
		 */
		private function setup_constants() {
			// Plugin version.
			if ( ! defined( 'AFFWP_CR_VERSION' ) ) {
				define( 'AFFWP_CR_VERSION', $this->version );
			}

			// Plugin Folder Path.
			if ( ! defined( 'AFFWP_CR_PLUGIN_DIR' ) ) {
				define( 'AFFWP_CR_PLUGIN_DIR', plugin_dir_path( $this->file ) );
			}

			// Plugin Folder URL.
			if ( ! defined( 'AFFWP_CR_PLUGIN_URL' ) ) {
				define( 'AFFWP_CR_PLUGIN_URL', plugin_dir_url( $this->file ) );
			}

			// Plugin Root File.
			if ( ! defined( 'AFFWP_CR_PLUGIN_FILE' ) ) {
				define( 'AFFWP_CR_PLUGIN_FILE', $this->file );
			}
		}

		/**
		 * Setup the default hooks and actions
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function hooks() {
			do_action( 'affwp_checkout_referrals_setup_actions' );
		}

		/**
		 * Setup all objects
		 *
		 * @since 1.1
		 */
		public function setup_objects() {
			self::$instance->integrations = new Affiliate_WP_Checkout_Referrals_Base();
		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function includes() {

			if ( is_admin() ) {
				require_once AFFWP_CR_PLUGIN_DIR . 'includes/class-admin.php';
			}

			require_once AFFWP_CR_PLUGIN_DIR . 'integrations/class-base.php';
			require_once AFFWP_CR_PLUGIN_DIR . 'includes/functions.php';

			// Load the class for each integration enabled.
			foreach ( affiliate_wp()->integrations->get_enabled_integrations() as $filename => $integration ) {

				if ( file_exists( AFFWP_CR_PLUGIN_DIR . 'integrations/class-' . $filename . '.php' ) ) {
					require_once AFFWP_CR_PLUGIN_DIR . 'integrations/class-' . $filename . '.php';
				}
			}
		}

		/**
		 * Modify plugin metalinks
		 *
		 * @access public
		 * @since  1.0.0
		 *
		 * @param array  $links The current links array.
		 * @param string $file  A specific plugin table entry.
		 *
		 * @return array $links The modified links array
		 */
		public function plugin_meta( $links, $file ) {
			if ( plugin_basename( __FILE__ ) == $file ) {
				$plugins_link = array(
						'<a title="' . __( 'Get more add-ons for AffiliateWP', 'affiliatewp-checkout-referrals' ) . '" href="http://affiliatewp.com/addons/" target="_blank">' . __( 'Get add-ons', 'affiliatewp-checkout-referrals' ) . '</a>'
				);

				$links = array_merge( $links, $plugins_link );
			}

			return $links;
		}

	}

}

/**
 * The main function responsible for returning the one true AffiliateWP_Checkout_Referrals
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $affiliatewp_checkout_referrals = affiliatewp_checkout_referrals(); ?>
 *
 * @since 1.0
 * @return object The one true AffiliateWP_Checkout_Referrals Instance
 */
function affiliatewp_checkout_referrals() {
	return AffiliateWP_Checkout_Referrals::instance();
}

<?php
/**
 * Core: Admin and Settings
 *
 * @package     AffiliateWP Checkout Referrals
 * @subpackage  Admin
 * @copyright   Copyright (c) 2021, Sandhills Development, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

/**
 * Sets up admin-specific logic for the add-on.
 *
 * @since 1.0.0
 */
class AffiliateWP_Checkout_Referrals_Admin {

	/**
	 * Registers hook callbacks for setting up admin logic.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// settings
		add_filter( 'affwp_settings_tabs', array( $this, 'setting_tab' ) );
		add_filter( 'affwp_settings',      array( $this, 'settings'    ) );
	}

	/**
	 * Registers the new settings tab.
	 *
	 * @since 1.0.9
	 *
	 * @return array Filtered list of settings tabs and labels.
	 */
	public function setting_tab( $tabs ) {
		$tabs['checkout_referrals'] = __( 'Checkout Referrals', 'affiliatewp-checkout-referrals' );
		return $tabs;
	}

	/**
	 * Registers the settings for Checkout Referrals tab.
	 *
	 * @since 1.0.9
	 *
	 * @return array Filtered list of settings.
	 */
	public function settings( $settings ) {

		$settings['checkout_referrals'] = array(
			'checkout_referrals_checkout_text' => array(
				'name' => __( 'Checkout Text', 'affiliatewp-checkout-referrals' ),
				'desc' => '<p class="description">' . __( 'Enter the field description to be displayed at checkout.', 'affiliatewp-checkout-referrals' ) . '</p>',
				'type' => 'text',
				'std'  => __( 'Who should be awarded commission for this purchase?', 'affiliatewp-checkout-referrals' )
			),
			'checkout_referrals_affiliate_selection' => array(
				'name' => __( 'Affiliate Selection Method', 'affiliatewp-checkout-referrals' ),
				'desc' => __( 'Choose how the customer will select the affiliate to award commission to.', 'affiliatewp-checkout-referrals' ),
				'type' => 'select',
				'options' => array(
					'select_menu' => __( 'Customer selects affiliate from list', 'affiliatewp-checkout-referrals'),
					'input'       => __( 'Customer enters affiliate&#8217;s ID or username', 'affiliatewp-checkout-referrals'),
				)
			),
			'checkout_referrals_require_affiliate' => array(
				'name' => __( 'Require Affiliate Selection', 'affiliatewp-checkout-referrals' ),
				'desc' => __( 'Customer must select an affiliate to award the referral to.', 'affiliatewp-checkout-referrals' ),
				'type' => 'checkbox'
			),
			'checkout_referrals_affiliate_display' => array(
				'name' => __( 'Affiliate Display', 'affiliatewp-checkout-referrals' ),
				'desc' => __( 'How the affiliates will be displayed at checkout.', 'affiliatewp-checkout-referrals' ),
				'type' => 'radio',
				'options' => array(
					'user_nicename' => __( 'User Nicename', 'affiliatewp-checkout-referrals' ),
					'display_name'  => __( 'Display Name', 'affiliatewp-checkout-referrals' ),
					'nickname'      => __( 'Nickname', 'affiliatewp-checkout-referrals' )
				),
				'std' => 'user_nicename'
			),
			'checkout_referrals_affiliates_sorting_order' => array(
				'name' => __( 'Affiliates Sorting Order', 'affiliatewp-checkout-referrals' ),
				'desc' => __( 'How the affiliates will be sorted on the checkout dropdown', 'affiliatewp-checkout-referrals' ),
				'type' => 'radio',
				'options' => array(
					'affiliate_id' 	=> __( 'Affiliate ID', 'affiliatewp-checkout-referrals' ),
					'alphabetical' 	=> __( 'Alphabetical', 'affiliatewp-checkout-referrals' )
				),
				'std' => 'affiliate_id'
			)
		);

		return $settings;
	}

}
$affiliatewp_admin = new AffiliateWP_Checkout_Referrals_Admin;

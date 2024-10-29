<?php
/**
 * Core: Functions
 *
 * @package     AffiliateWP Checkout Referrals
 * @subpackage  Functions
 * @copyright   Copyright (c) 2021, Sandhills Development, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get the checkout text.
 * Provides a default for when the settings have not yet been saved.
 *
 * @since  1.0.4
 *
 * @return string Checkout text
 */
function affwp_cr_checkout_text() {
	return affiliate_wp()->settings->get( 'checkout_referrals_checkout_text', __( 'Who should be awarded commission for this purchase?', 'affiliatewp-checkout-referrals' ) );
}

/**
 * Get the affiliate display option.
 * Provides a default for when the settings have not yet been saved.
 *
 * @since  1.0.4
 *
 * @return string display setting
 */
function affwp_cr_affiliate_display() {
	return affiliate_wp()->settings->get( 'checkout_referrals_affiliate_display', 'user_nicename' );
}

/**
 * Whether or not the affiliats must be entered/selected
 * Provides a default for when the settings have not yet been saved.
 *
 * @since  1.0.4
 *
 * @return bool false
 */
function affwp_cr_require_affiliate() {
	return affiliate_wp()->settings->get( 'checkout_referrals_require_affiliate', false );
}

/**
 * Gets the affiliates sorting order.
 * 
 * Provides a default for when the settings have not yet been saved.
 *
 * @since  1.0.9
 *
 * @return string Affiliates' sorting order setting.
 */
function affwp_cr_affiliates_sorting_order() {
	return affiliate_wp()->settings->get( 'checkout_referrals_affiliates_sorting_order', 'affiliate_id' );
}
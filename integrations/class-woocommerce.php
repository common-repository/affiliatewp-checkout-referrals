<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName -- Filename okay.
/**
 * Integrations: WooCommerce Integration
 *
 * @package     AffiliateWP Checkout Referrals
 * @subpackage  Integrations
 * @copyright   Copyright (c) 2021, Sandhills Development, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 *
 * phpcs:disable PEAR.Functions.FunctionCallSignature.EmptyLine
 */

/**
 * Integration class for WooCommerce
 *
 * @since 1.0.0
 *
 * @see Affiliate_WP_Checkout_Referrals_Base
 */
class AffiliateWP_Checkout_Referrals_WooCommerce extends Affiliate_WP_Checkout_Referrals_Base {

	/**
	 * The ID for the Affiliate Selector for WooCommerce Checkout Blocks
	 *
	 * @since AFFWPN
	 *
	 * @var string
	 */
	private string $woocommerce_checkout_block_field_id = 'affiliatewp/checkout_referrals/affiliate';

	/**
	 * Get things started.
	 *
	 * @access public
	 * @since  1.0
	 * @since  AFFWPN Hooks added for WooCommerce checkout blocks.
	 */
	public function init() {

		$this->context = 'woocommerce';

		add_action( 'woocommerce_after_order_notes', array( $this, 'affiliate_select_or_input' ) );
		add_action( 'woocommerce_checkout_process', array( $this, 'check_affiliate_field' ) );

		// Set selected affiliate.
		if ( version_compare( AFFILIATEWP_VERSION, '2.1.8', '>=' ) ) {

			// AffiliateWP v2.1.8 introduced woocommerce_checkout_update_order_meta which is used to insert a pending referral.
			add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'set_selected_affiliate' ), 0, 2 );
		} else {

			// AffiliateWP v2.1.7 and lower used woocommerce_checkout_order_processed.
			add_action( 'woocommerce_checkout_order_processed', array( $this, 'set_selected_affiliate' ), 0, 2 );
		}

		if ( is_admin() ) {
			return; // The below is frontend only.
		}

		// WooCommerce checkout block versions.
		add_action( 'woocommerce_init', [ $this, 'register_affiliate_select_or_input_for_checkout_block'] );
		add_action( 'woocommerce_set_additional_field_value', [ $this, 'save_affiliate_select_or_input_for_checkout_block'], 10, 4 );
	}

	/**
	 * Add Affiliate Selector (or Input) to checkout blocks.
	 *
	 * @since AFFWPN
	 *
	 * @link https://github.com/woocommerce/woocommerce/blob/trunk/plugins/woocommerce-blocks/docs/third-party-developers/extensibility/checkout-block/additional-checkout-fields.md#supported-field-types
	 */
	public function register_affiliate_select_or_input_for_checkout_block() {

		$shared_input_args = [
			'id'                         => $this->woocommerce_checkout_block_field_id,
			'label'                      => esc_html( affwp_cr_checkout_text() ),
			'location'                   => 'order',
			'required'                   => (bool) affwp_cr_require_affiliate(),
			'show_in_order_confirmation' => true,
		];

		if ( 'input' === $this->get_affiliate_selection() ) {

			// Text input.
			woocommerce_register_additional_checkout_field(
				array_merge( $shared_input_args, [ 'type' => 'text' ] )
			);

			return;
		}

		// Select dropdown (only other option).
		woocommerce_register_additional_checkout_field(
			array_merge(
				$shared_input_args,
				[
					'type'    => 'select',
					'options' => $this->get_woocommerce_checkout_block_affiliates_select_list(),
				]
			)
		);
	}

	/**
	 * Save Selected Affiliate (WooCommerce Checkout Blocks).
	 *
	 * @param string                                        $key       The key.
	 * @param [type]                                        $value     The value.
	 * @param string                                        $group     The group.
	 * @param \Automattic\WooCommerce\Admin\Overrides\Order $wc_object The checkout object.
	 *
	 * @since AFFWPN
	 *
	 * @link https://github.com/woocommerce/woocommerce/blob/trunk/plugins/woocommerce-blocks/docs/third-party-developers/extensibility/checkout-block/additional-checkout-fields.md#react-to-to-saving-fields
	 *
	 * @return void
	 */
	public function save_affiliate_select_or_input_for_checkout_block(
		string $key,
		$value,
		string $group,
		\Automattic\WooCommerce\Admin\Overrides\Order $wc_object
	) : void {

		if ( $this->woocommerce_checkout_block_field_id !== $key ) {
			return;
		}

		$affiliate = is_numeric( $value )
			? absint( $value )
			: $value;

		if ( is_numeric( $affiliate ) && $affiliate <= 0 ) {
			return;
		}

		// $this->set_affiliate_id() will override with this value when using the select dropdown.
		$_POST['woocommerce_affiliate'] = $affiliate;

		$this->set_selected_affiliate(
			$wc_object->get_order_number(),

			// $this->set_affiliate_id() will use this when using input.
			$affiliate
		);
	}

	/**
	 * Get a list of affiliates to select in a format for WooCommerce checkout blocks.
	 *
	 * @since AFFWPN
	 *
	 * @return array
	 */
	private function get_woocommerce_checkout_block_affiliates_select_list() : array {

		$affiliates = $this->get_affiliates_select_list( $this->get_affiliates() );

		return array_filter(
			array_map(
				function( $affiliate_name, $affiliate_id ) {

					return [
						'value' => absint( $affiliate_id ),
						'label' => (string) $affiliate_name,
					];
				},
				$affiliates,
				array_keys( $affiliates )
			),
			function( $affiliate ) {

				// Exclude the "Select Affiliate" default option.
				if ( 0 === intval( $affiliate['value'] ?? 0 ) ) {
					return false;
				}

				return true;
			}
		);
	}

	/**
	 * Set selected affiliate
	 *
	 * @param int   $order_id The Order ID.
	 * @param mixed $posted   Posted.
	 *
	 * @return  void
	 * @since  1.0.1
	 */
	public function set_selected_affiliate( $order_id = 0, $posted ) {

		if ( $this->already_tracking_referral() ) {
			return;
		}

		add_filter( 'affwp_was_referred', '__return_true' );
		add_filter( 'affwp_get_referring_affiliate_id', array( $this, 'set_affiliate_id' ), 10, 3 );
	}

	/**
	 * Check affiliate select menu.
	 *
	 * @since 1.0
	 */
	public function check_affiliate_field() {

		if ( $this->already_tracking_referral() ) {
			return;
		}

		// Check if there's any errors.
		if ( $this->get_error( $_POST[ $this->context . '_affiliate'] ) ) {
			wc_add_notice( $this->get_error( $_POST[ $this->context . '_affiliate'] ), 'error' );
		}
	}

	/**
	 * Affiliate Select/Input.
	 *
	 * @param object|null|string $checkout Checkout object where previous value might be stored,
	 *                                     string of type of checkout, or null.
	 *
	 * @since  1.0
	 */
	public function affiliate_select_or_input( $checkout = null ) {

		if ( $this->already_tracking_referral() ) {
			return;
		}

		$description    = affwp_cr_checkout_text();
		$affiliate_list = $this->get_affiliates();

		?>

		<?php if ( 'input' === $this->get_affiliate_selection() ) : ?>

			<?php if ( $description ) : ?>
				<label for="woocommerce-affiliate"><?php echo esc_attr( $description ); ?></label>
			<?php endif; ?>

			<input type="text" id="woocommerce-affiliate" name="woocommerce_affiliate" />

		<?php else : ?>

			<?php

			if ( $affiliate_list ) {

				woocommerce_form_field(
					'woocommerce_affiliate',
					array(
						'type'     => 'select',
						'class'    => array( 'form-row-wide' ),
						'label'    => $description,
						'options'  => $this->get_affiliates_select_list( $affiliate_list ),
						'required' => affwp_cr_require_affiliate(),
					),
					is_object( $checkout )
						? $checkout->get_value( 'woocommerce_affiliate' )
						: null
				);
			}

			?>

		<?php endif; ?>

		<?php
	}
}

new AffiliateWP_Checkout_Referrals_WooCommerce();

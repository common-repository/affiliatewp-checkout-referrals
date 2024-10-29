=== AffiliateWP Checkout Referrals ===
Plugin Name: AffiliateWP - Checkout Referrals
Contributors: sumobi, mordauk, aubreypwd
Tags: AffiliateWP, affiliate, Pippin Williamson, Andrew Munro, mordauk, pippinsplugins, sumobi, ecommerce, e-commerce, e commerce, selling, referrals, easy digital downloads, digital downloads, woocommerce, woo
Requires at least: 5.2
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allow customers to select who should receive a commission at checkout

== Description ==

> This plugin requires [AffiliateWP](https://affiliatewp.com/ "AffiliateWP") in order to function.

AffiliateWP Checkout Referrals allows a customer to award commission to an affiliate at checkout. This can be done via a select menu or input field. If an affiliate is already being tracked by the customer the affiliate select menu or input field is not shown at checkout.

**Currently supported integrations**

1. Easy Digital Downloads
2. WooCommerce
3. Restrict Content Pro

**Features:**

1. Shows a select menu or input field at checkout (but only when a referral link is not used) that allows a customer to select/enter an affiliate that their purchase will be credited to. The input field allows either an affiliate ID or username to be entered.
1. Adds a payment note to the order screen showing the referral ID, amount recorded for affiliate, and affiliate's name.
1. Optionally require that the customer select or enter an affiliate at checkout.
1. Select how the Affiliate's should be displayed in the select menu.
1. Select what text is shown above the select menu at checkout.

**What is AffiliateWP?**

[AffiliateWP](https://affiliatewp.com/ "AffiliateWP") provides a complete affiliate management system for your WordPress website that seamlessly integrates with all major WordPress e-commerce and membership platforms. It aims to provide everything you need in a simple, clean, easy to use system that you will love to use.

== Installation ==

1. Unpack the entire contents of this plugin zip file into your `wp-content/plugins/` folder locally
1. Upload to your site
1. Navigate to `wp-admin/plugins.php` on your site (your WP Admin plugin page)
1. Activate this plugin
1. Configure the options from Affiliates &rarr; Settings &rarr; Integrations

OR you can just install it with WordPress by going to Plugins &rarr; Add New &rarr; and type this plugin's name

== Screenshots ==

1. The add-ons's settings from Affiliates &rarr; Settings &rarr; Integrations
1. The select menu at checkout that a customer can use to award a commission to an affiliate

== Upgrade Notice ==
Fix: Tracked affiliate coupons were not working when checkout referrals was active

== Changelog ==

= 1.2.1 =
* Improved: Added support for WooCommerce checkout blocks

= 1.2 =
* New: Requires WordPress 5.2 minimum

= 1.1 =
* New: Enforce minimum dependency requirements checking
* New: Requires PHP 5.6 minimum
* New: Requires WordPress 5.0 minimum
* New: Requires AffiliateWP 2.6 minimum
* Improved: Use tracking cookie name getters in AffiliateWP 2.7.1+

= 1.0.9 =
* New: Add a setting for how to sort the affiliate list
* Improved: Test for WordPress 5.7 compatibility
* Improved: Move settings from Integrations to a dedicated Checkout Referrals tab
* Fixed: WooCommerce: Use built-in functionality for signifying a Woo checkout field as optional or required
* Fixed: Avoid a notice by only listing affiliates with valid corresponding user accounts
* Fixed: Remove bundled language files now that translations have been imported on WordPress.org

= 1.0.8 =
* Tweak: Make it possible to only allow specific affiliates to be selected at checkout with the `affwp_checkout_referrals_get_affiliates_args` filter
* Tweak: Allow language translations to be handled by WordPress.org
* Fix: RCP: Register form shows the affiliate drop-down even when visiting on an affiliate link

= 1.0.7 =
* New: Checkout Referrals is now compatible with the Lifetime Commissions add-on
* Tweak: Hide affiliate select/input on first page load if affiliate link is used

= 1.0.6 =
* Fix: Referrals not being generated in WooCommerce integration.

= 1.0.5 =
* New: Restrict Content Pro was added as an integration that Checkout Referrals supports
* Fix: Referrals not being generated in some instances where the username entered started with a number

= 1.0.4 =
* Fix: A scenario where if the AffiliateWP settings were not saved after installing Checkout Referrals, the select menu at checkout wouldn't show affiliates correctly.

= 1.0.3 =
* New: Affiliate Selection Method. An input field can now be shown instead of a select menu. This allows a customer to enter either an affiliate ID or username.

= 1.0.2 =
* Fix: Tracked affiliate coupons were not working when checkout referrals was active

= 1.0.1 =
* Tweak: Improved the way referrals are created

= 1.0 =
* Initial release

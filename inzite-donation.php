<?php
 /**
 * Plugin Name: Inzite Donation
 * Plugin URI:  https://example.com/plugins/the-basics/
 * Description: Basic WordPress Plugin Header Comment
 * Version:     20160911
 * Author:      WordPress.org
 * Author URI:  https://author.example.com/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wporg
 * Domain Path: /languages
 */

define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'MY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

function register_scripts_and_styles() {
 
	wp_register_style( 'bulma', MY_PLUGIN_URL . '/inc/bulma-prefixed.css', array(), '0.7.4', 'all');
  wp_register_style( 'donation-styles', MY_PLUGIN_URL . '/inc/donation.css', array('bulma'), '1.0', 'all');

	wp_register_script( 'donation-script', MY_PLUGIN_URL . '/inc/donation.js', array ( 'jquery', 'vue-js' ), '1.0', true);
	
	wp_register_script( 'crypto-js', 'https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js', array ( 'jquery' ), "3.1.9-1", true);
	wp_register_script( 'vue-js', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', array ( 'jquery', 'crypto-js' ), "2.6.7", true);
}
add_action( 'wp_enqueue_scripts', 'register_scripts_and_styles' );

require_once( MY_PLUGIN_PATH . '/QuickPay/Quickpay.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Client.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Form.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Traits/Variables.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Services/Service.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Services/Brandings.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Services/Payments.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Services/Subscriptions.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Exceptions/InvalidCallbackException.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Exceptions/NotFoundException.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Exceptions/QuickpayException.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Exceptions/UnauthorizedException.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Exceptions/ValidationException.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Entities/Entity.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Entities/Branding.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Entities/Link.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Entities/Payment.php' );
require_once( MY_PLUGIN_PATH . '/QuickPay/Entities/Subscription.php' );



require_once( MY_PLUGIN_PATH . '/inc/shortcode.php' );


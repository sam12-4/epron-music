<?php

/**
 * Plugin Name:       EasyOrder - B2B Plugin for WooCommerce
 * Plugin URI:        https://redqteam.com/b2b
 * Description:       EasyOrder - B2B is a WooCommerce extension. It allows the admin or vendor to create a custom price tier based on quantity.
 * Version:           1.0.2
 * Author:            redq
 * Author URI:        https://themeforest.net/user/redqteam
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       easyorder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class EasyOrder {
	/**
	 * Plugin data from get_plugins()
	 *
	 * @since 1.0
	 * @var object
	 */
	public $plugin_data;

	/**
	 * Plugin version
	 */
	public $plugin_version = '1.0.2';

	/**
	 * Includes to load
	 *
	 * @since 1.0
	 * @var array
	 */
	public $includes;

	/**
	 * Plugin Action and Filter Hooks
	 *
	 * @return null
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'easyorder_constants' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'easyorder_load_text_domain' ) );
		add_action( 'plugins_loaded', array( $this, 'easyorder_include_files' ), 1 );
	}

	/**
	 * Plugin constant define
	 *
	 * @return null
	 * @since 1.0.0
	 */
	public function easyorder_constants() {
		define( 'EASYORDER_VERSION', $this->plugin_version );

		define( 'EASYORDER_PATH', __DIR__ );
		define( 'EASYORDER_FILE', __FILE__ );
		define( 'EASYORDER_URL', plugins_url( '', EASYORDER_FILE ) );
		define( 'EASYORDER_ASSETS', EASYORDER_URL . '/assets' );

		define( 'EASYORDER_INC_DIR', 'includes' );
		define( 'EASYORDER_LANG_DIR', 'languages' );
		define( 'EASYORDER_ROOT_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		define( 'EASYORDER_TEMPLATE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' );
	}

	/**
	 * Support languages for inventory
	 *
	 * @return null
	 * @since 1.0.0
	 */
	public function easyorder_load_text_domain() {
		load_plugin_textdomain( 'easyorder', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Plugin includes files
	 *
	 * @return null
	 * @since 1.0.0
	 */
	public function easyorder_include_files() {
		include_once( 'includes/class-easyorder-assets.php' );
		include_once( 'includes/class-easyorder-front.php' );
		include_once( 'includes/class-easyorder-template.php' );
		include_once( 'includes/class-easyorder-admin.php' );
		include_once( 'includes/class-easyorder-ajax.php' );
		include_once( 'includes/class-easyorder-post-types.php' );
		include_once( 'includes/class-easyorder-message.php' );
		include_once( 'includes/class-easyorder-metabox.php' );
		include_once( 'includes/class-easyorder-email.php' );
		include_once( 'includes/functions.php' );
		include_once( 'includes/metabox-functions.php' );
		include_once( 'includes/dokan-dashboard.php' );
	}
}

// Initialize the plugin based on condition
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	// If WooCommerce and Dokan plugin installed then initialize easyorder
	new EasyOrder();

	// Flash rewrite
	register_deactivation_hook(__FILE__, 'flush_rewrite_rules');
	register_activation_hook(__FILE__, 'easyorder_flush_rewrites');
	function easyorder_flush_rewrites()
	{
		EasyOrderPostTypes::register_post_status();
		EasyOrderMessage::message_endpoints();
		flush_rewrite_rules();
	}

} else {
	// Deactivate the plugin
	add_action( 'admin_init', 'easyorder_deactivate_plugin' );
	function easyorder_deactivate_plugin() {
		deactivate_plugins( plugin_basename( plugin_basename( __FILE__ ) ) );
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}

	// Show notice
	add_action( 'admin_notices', 'easyorder_notice' );
	function easyorder_notice() {
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
		<div class="error">
			<p>
				<strong><?php esc_html_e('EasyOrder - B2B Plugin for WooCommerce', 'easyorder') ?></strong> <?php esc_html_e('requires', 'easyorder') ?> <a href="https://wordpress.org/plugins/woocommerce/" target="_blank"><?php esc_html_e('WooCommerce', 'easyorder') ?></a>
			</p>
		</div>
		<?php
		}
	}
}

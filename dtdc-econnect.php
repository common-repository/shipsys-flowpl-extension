<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://shipsy.io/
 * @since             1.0.0
 * @package           Shipsy_FlowPL_Econnect
 *
 * @wordpress-plugin
 * Plugin Name:       Shipsy's FlowPL Extension
 * Plugin URI:        https://shipsy-public-assets.s3-us-west-2.amazonaws.com/plugins/woocommerce/shipsy-flowpl-econnect.zip
 * Description:       This plugin would enable you to directly sync your Orders with FlowPL for shipment fulfillment.
 * Version:           1.0.0
 * Author:            shipsyplugins
 * Author URI:        https://shipsy.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       shipsy-flowpl-econnect
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DTDC_ECONNECT_VERSION', '1.0.0' );
define( 'DTDC_ECONNECT_URL', plugin_dir_url( __FILE__ ) );
define( 'DTDC_ECONNECT_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dtdc-econnect-activator.php
 */
function activate_dtdc_econnect() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dtdc-econnect-activator.php';
	$activator = new Dtdc_Econnect_Activator();
	$activator->activate();
	// Dtdc_Econnect_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dtdc-econnect-deactivator.php
 */
function deactivate_dtdc_econnect() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dtdc-econnect-activator.php';
	$activator = new Dtdc_Econnect_Activator();
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dtdc-econnect-deactivator.php';
	$deactivator = new Dtdc_Econnect_Deactivator( $activator );
	$deactivator->deactivate();

}

register_activation_hook( __FILE__, 'activate_dtdc_econnect' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dtdc-econnect.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dtdc_econnect() {
	$plugin = new Dtdc_Econnect();
	$plugin->run();

}
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	run_dtdc_econnect();
}

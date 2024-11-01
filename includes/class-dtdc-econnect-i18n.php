<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://shipsy.io/
 * @since      1.0.0
 *
 * @package    Dtdc_Econnect
 * @subpackage Dtdc_Econnect/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Dtdc_Econnect
 * @subpackage Dtdc_Econnect/includes
 * @author     shipsyplugins <pradeep.mishra@shipsy.co.in>
 */
class Dtdc_Econnect_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'dtdc-econnect',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

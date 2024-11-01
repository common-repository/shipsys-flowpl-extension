<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://shipsy.io/
 * @since      1.0.0
 *
 * @package    Dtdc_Econnect
 * @subpackage Dtdc_Econnect/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dtdc_Econnect
 * @subpackage Dtdc_Econnect/public
 * @author     shipsyplugins <pradeep.mishra@shipsy.co.in>
 */
class Dtdc_Econnect_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Can change it on setting page
	 *
	 * @var bool
	 */
	public $use_track_button = true;


	/**
	 * Can change it on setting page
	 *
	 * @var bool
	 */
	public $custom_domain = 'track.dtdc.com';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dtdc_Econnect_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dtdc_Econnect_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dtdc-econnect-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dtdc_Econnect_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dtdc_Econnect_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dtdc-econnect-public.js', array( 'jquery' ), $this->version, false );

	}

	public function get_tracking_items( $order_id ) {
		require_once DTDC_ECONNECT_PATH . 'utils/db/class-shipsydbconnector.php';
		$dbconnector = ShipsyDBConnector::get_instance();

		$table_name     = 'sync_track_order';
		$where          = array(
			'orderId' => $order_id,
		);
		$tracking_items = $dbconnector->reads( $table_name, $where );

		if ( is_array( $tracking_items ) ) {
			return $tracking_items;
		} else {
			return array();
		}
	}

	/**
	 * Display Shipment info in the frontend (order view/tracking page).
	 *
	 * @param  string $order_id
	 */
	public function display_tracking_info( $order_id ) {
		wc_get_template(
			'myaccount/view-order.php',
			array(
				'tracking_items'   => $this->get_tracking_items( $order_id ),
				'use_track_button' => $this->use_track_button,
				'domain'           => $this->custom_domain,
			),
			'',
			DTDC_ECONNECT_PATH . '/templates/'
		);
	}

}

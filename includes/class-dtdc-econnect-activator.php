<?php

/**
 * Fired during plugin activation
 *
 * @link       https://shipsy.io/
 * @since      1.0.0
 *
 * @package    Dtdc_Econnect
 * @subpackage Dtdc_Econnect/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Dtdc_Econnect
 * @subpackage Dtdc_Econnect/includes
 * @author     shipsyplugins <pradeep.mishra@shipsy.co.in>
 */
class Dtdc_Econnect_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function activate() {
		$this->create_sync_track_order_table();
	}

	public function create_sync_track_order_table() {
		require_once DTDC_ECONNECT_PATH . 'utils/db/class-shipsydbconnector.php';

		$table_name = 'sync_track_order';
		$columns    = array(
			'orderId'      => array( 'int(11)', 'NOT NULL', 'PRIMARY KEY' ),
			'shipsy_refno' => array( 'varchar(100)', 'DEFAULT NULL' ),
			'track_url'    => array( 'varchar(300)', 'DEFAULT NULL' ),
		);

		$dbconnector = ShipsyDBConnector::get_instance();
		$dbconnector->create( $table_name, $columns );
	}
}

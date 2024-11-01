<?php

require_once ABSPATH . 'wp-admin/includes/upgrade.php';

class ShipsyDBConnector {
	/*
	 * A Singleton class to interact with database
	 * It provides CRUD operations for database.
	 */
	private static ?ShipsyDBConnector $__instance = null;

	private function __constructor() {

	}

	public static function get_instance() {
		$cls = self::class;
		if ( is_null( self::$__instance ) ) {
			self::$__instance = new ShipsyDBConnector();
		}
		return self::$__instance;
	}

	public function create( $table, $columns ) {
		/*
		 * Function to create a table.
		 *
		 * Args:
		 * -----
		 * $table: string
		 *         The name of the table to create
		 * $columns: array(array())
		 *           An array of columns. Whose keys are the name of the columns and value is a list of constraint for
		 *           that column.
		 *
		 * Returns:
		 * --------
		 * None
		 */
		global $wpdb;
		// to create table if the table does not exists
		$table_name = $wpdb->prefix . $table;
		if ( ! $this->table_exists( $table ) ) {
			// dynamic table generating code while activating plugin
			$columns_query = '';
			foreach ( $columns as $col => $constraints ) {
				$columns_query = $columns_query . "`$col`" . ' ';
				$val           = '';
				foreach ( $constraints as $constraint ) {
					$val = $val . $constraint . ' ';
				}
				$columns_query = $columns_query . $val . ', ';
			}
			$columns_query = substr( $columns_query, 0, -3 );

			$sql = "CREATE TABLE `$table_name` ( $columns_query ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

			dbDelta( $sql );
		}
	}

	public function write( $table, $data, $format = null ) {
		/*
		 * Function to create a record in a table.
		 *
		 * Args:
		 * -----
		 * $table: string
		 *         The name of the table to create
		 * $data: array()
		 *           An array with key as the name of the column and value is the value for that column.
		 * $format: null or array()
		 *          Null or array of format of the data.
		 *
		 * Returns:
		 * --------
		 * None
		 */
		global $wpdb;
		$table_name = $wpdb->prefix . $table;
		$wpdb->insert( $table_name, $data, $format );
	}

	public function read( $table, $column, $where ) {
		/*
		 * Function to read a single value from table.
		 *
		 * Args:
		 * -----
		 * $table: string
		 *         The name of the table to create
		 * $column: string
		 *       Column whose value is to be returned.
		 * $where: array()
		 *         Conditions to extract value.
		 *
		 * Returns:
		 * --------
		 * $value: string | int | null
		 */
		global $wpdb;

		$table_name = $wpdb->prefix . $table;

		$condition = '';
		$vals      = array();
		foreach ( $where as $col => $val ) {
			$condition = $condition . $col . '= %s';
			array_push( $vals, $val );
		}

		$value = $wpdb->get_var(
			$wpdb->prepare(
            "SELECT `$column` FROM `$table_name` WHERE $condition", $vals)); // phpcs:ignore
		return $value;
	}

	public function reads( $table, $where ) {
		/*
		 * Function to read multiple values with all columns.
		 *
		 * Args:
		 * -----
		 * $table: string
		 *         The name of the table to create
		 * $where: array()
		 *         Conditions to extract value.
		 *
		 * Returns:
		 * --------
		 * $values: array | object | null
		 */
		global $wpdb;

		$table_name = $wpdb->prefix . $table;

		$condition = '';
		$vals      = array();
		foreach ( $where as $col => $val ) {
			$condition = $condition . $col . '= %s';
			array_push( $vals, $val );
		}

		$values = $wpdb->get_results(
			$wpdb->prepare(
            "SELECT * FROM `$table_name` WHERE $condition", $vals)); // phpcs:ignore
		return $values;
	}

	public function update( $table, $data, $where, $format = null, $where_format = null ) {
		/*
		 * Function to update a value in table.
		 *
		 * Args:
		 * -----
		 * $table: string
		 *         The name of the table to create
		 * $data: array()
		 *        An array with key as the name of the column and value is the value for that column.
		 * $where: array()
		 *         Conditions to extract value.
		 * $format: null or array()
		 *          Null or array of format of the data.
		 * $where_format: null or array()
		 *                Null or array of format of the where conditions.
		 *
		 * Returns:
		 * --------
		 * None
		 */
		global $wpdb;
		$table_name = $wpdb->prefix . $table;
		$wpdb->update( $table_name, $data, $where, $format, $where_format );
	}

	public function delete( $table, $where, $where_format = null ) {
		/*
		 * Function to delete a record in table.
		 *
		 * Args:
		 * -----
		 * $table: string
		 *         The name of the table to create
		 * $where: array()
		 *         Conditions to extract value.
		 * $where_format: null or array()
		 *                Null or array of format of the where conditions.
		 *
		 * Returns:
		 * --------
		 * None
		 */
		global $wpdb;
		$table_name = $wpdb->prefix . $table;
		$wpdb->delete( $table_name, $where, $where_format );
	}

	public function drop( $table ) {
		/*
		 * Function to drop a table.
		 *
		 * Args:
		 * -----
		 * $table: string
		 *         The name of the table to create
		 *
		 * Returns:
		 * --------
		 * None
		 */
		global $wpdb;
		$table_name = $wpdb->prefix . $table;
        $wpdb->query($wpdb->prepare("DROP TABLE IF EXISTS `$table_name`")); // phpcs:ignore
	}

	private function table_exists( $table ): bool {
		/*
		 * Function to check if table exists.
		 */
		global $wpdb;
		$table_name = $wpdb->prefix . $table;
        if ($wpdb->get_var($wpdb->prepare("SHOW tables like `$table_name`")) == $table_name) { // phpcs:ignore
			return true;
		}
		return false;
	}
}

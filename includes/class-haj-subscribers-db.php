<?php

/**
 * The shared database functinality
 *
 * @link 		http://zulicreative.com
 * @since 		1.0.0
 *
 * @package 		Haj_Subscribers
 * @subpackage 		Haj_Subscribers/includes
 * @author 		Matt Bateman
 */

// Prevent direct file access

if ( ! defined ( 'ABSPATH' ) ) { exit; }

class Haj_Subscribers_DB {
	private $plugin_name;
	private $version;
	private $opts;

	private $db_version = "0.1";
	private $db_disable = 0;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->opts = new Haj_Subscribers_Options( $this->plugin_name, $this->version );
		$this->check_db_version();
	}

	public function check_db_version() {
		$installed_version = $this->opts->get_option( 'db_version' );
		if ( $installed_version != $this->db_version ) {
			$this->create_dbs();
		}
	}

	private function create_dbs() {
		$installed_version = $this->opts->get_option( 'db_version' );
		$this->create_subscribers_db();
		if ($installed_ver) {
			$this->opts->update_option('db_version',$this->db_version);
		} else {
			$this->opts->add_option('db_version',$this->db_version);
		}
	}

	private function create_subscribers_db() {
		$table = 'haj_subscribers';
		$fields = "
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			create_date datetime NOT NULL,
			email tinytext NOT NULL,
			fname tinytext NOT NULL,
			level tinyint NOT NULL,
			UNIQUE KEY id (id)
		";
		$this->create_db($table,$fields);
	}

	private function create_db ($table,$fields) {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $this->get_table_name($table);
		$sql = "CREATE TABLE $table_name ($fields) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	/* Database updates */

	public function insert($table,$data) {
		if ( $this->db_disable ) return 0;
		global $wpdb;
		$table_name = $this->get_table_name($table);

		$wpdb->insert( $table_name, $data);
		return $wpdb->insert_id;
	}

	public function update($table,$data,$id) {
		if ( $this->db_disable ) return 0;
		global $wpdb;
		$table_name = $this->get_table_name($table);

		return $wpdb->update(
			$table_name,
			$data,
			array ('id' => $id)
		);
	}

	/* Functions */
	private function get_table_name($table) {
		global $wpdb;
		return $wpdb->prefix . $table;
	}

	public function is_valid($table,$id) {
		global $wpdb;
		$table_name = $this->get_table_name($table);
		$query="
			SELECT id
			FROM $table_name
			WHERE id=$id
			";
		$rows=$wpdb->get_results($query);
		return (count($rows));
	}

	public function get($table,$where) {
		global $wpdb;
		$table_name = $this->get_table_name($table);
		$query = "
			SELECT *
			FROM $table_name
			WHERE $where
			";
		$rows=$wpdb->get_results($query);

		if (count($rows)) {
			$row=(array)$rows[0];
			return $row;
		} else {
			return 0;
		}
	}

}

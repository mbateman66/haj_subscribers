<?php

/**
 * The public & admin-facing shared functionality of the plugin.
 *
 * @link 		http://zulicreative.com
 * @since 		1.0.0
 *
 * @package 		Haj_Subscribers
 * @subpackage 		Haj_Subscribers/public
 * @author 		Matt Bateman
 */

// Prevent direct file access

if ( ! defined ( 'ABSPATH' ) ) { exit; }

class Haj_Subscribers_Forms {
	private $plugin_name;
	private $version;
	private $opts;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->opts = new Haj_Subscribers_Options($this->plugin_name,$this->version);
	}


	public function build_modal_forms () {
		$button_text_signup = $this->opts->get_option('submit_button_text_signup');
		$button_text_download = $this->opts->get_option('submit_button_text_download');
		include_once 'partials/haj-subscribers-modal-form.php';
	}

	function add_widget_areas() {
		register_sidebar( array(
			'name' => __( 'Hajjoo Subscribe Form Top - Signup', 'haj' ),
			'id' => 'haj-subscribe-form-top-signup',
			'description' => __( 'Widget will show up above the subscribe form on signups', 'haj' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
		) );
		register_sidebar( array(
			'name' => __( 'Hajjoo Subscribe Form Top - Download', 'haj' ),
			'id' => 'haj-subscribe-form-top-download',
			'description' => __( 'Widget will show up above the subscribe form on downloads', 'haj' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
		) );
		register_sidebar( array(
			'name' => __( 'Hajjoo Subscribe Form Bottom', 'haj' ),
			'id' => 'haj-subscribe-form-bottom',
			'description' => __( 'Widget will show up below the subscribe form', 'haj' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
		) );
	}
}

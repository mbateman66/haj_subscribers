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

class Haj_Subscribers_Shortcodes {
	private $plugin_name;
	private $version;
	private $opts;
	private $subscriber;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->opts = new Haj_Subscribers_Options($this->plugin_name,$this->version);
		$this->subscriber = new Haj_Subscribers_Subscriber($this->plugin_name,$this->version);
	}

	public function register() {
		add_shortcode( 'haj_signup_button', array( $this, 'sc_signup_button' ) );
		add_shortcode( 'haj_show', array( $this, 'sc_show' ) );
		add_shortcode( 'haj_download', array( $this, 'sc_download' ) );
		// Override download monitor shortcode
		remove_shortcode('download');
		add_shortcode( 'download', array( $this, 'sc_download' ) );
	}

	private function get_attribute($atts,$att,$default=null) {
		if (isset($atts) && isset($atts[$att])) {
			return $atts[$att];
		} else {
			return $default;
		}
	}

	//
	// Shortcode handlers
	//
	public function sc_signup_button($atts, $content=null) {
		$signup_button_text = $this->get_attribute($atts,'signup_button_text',
			$this->opts->get_option('signup_button_text'));

			$html=  '<button type="button" id="show_form" class="btn-signup" href="" onclick="return do_button(\'show_form\')">'
				.$signup_button_text.'</button>';
		return $html;
	}

	public function sc_show($atts, $content=null) {
		$level=$this->get_attribute($atts,'level');
		$match=$this->get_attribute($atts,'match');
		$s_level=$this->subscriber->get_level();
		$hide="";
		if (! $this->subscriber->check_level($level,$s_level,$match)) { $hide = 'haj_hide'; }
		 $html='<div class="haj_subscribe_show '.$hide.'"'
			.' haj_subscriber_level="'.$level.'"'
			.' haj_subscriber_match="'.$match.'"'
			.'>';
		$html .= 	do_shortcode($content);
		$html .= "</div>";
		return $html;
	}
	public function sc_download($atts, $content=null) {
	}
}

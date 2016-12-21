<?php

/**
 * The Options for this plugin
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

class Haj_Subscribers_Options {
	private $plugin_name;
	private $version;
	private $option_base;

	private	$options_defaults=array(

			'menu_signup_enable'		=> 0,
			'menu_signup_icon_enable'	=> 0,
			'menu_signup_icon'		=> 'icon-pencil',
			'menu_signup_text'		=> 'Sign Up',

			'download_signup_text'		=> "Sign up now to access the download",

			'signup_button_text'		=> "Get it now",
			'download_button_text'		=> "Download",
			'submit_button_text'		=> "Go",

			'mailchimp_enable'		=> 0,
			'mailchimp_double_optin'	=> 0,
			'mailchimp_apikey'		=> "",
			'mailchimp_listid'		=> "",
		);

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->set_option_base($plugin_name);
	}

	public function get_option($option) {
		$option_default=$this->options_defaults[$option];
		$option_name = $this->option_base.$option;
		$val=get_option($option_name,$option_default);
		return $val;
	}
	public function add_option($option,$val) {
		$option_name = $this->get_option_name ($option);
		add_option($option_name,$val);
	}
	public function update_option($option,$val) {
		$option_name = $this->get_option_name ($option);
		update_option($option_name,$val);
	}

	public function get_option_name($option) {
		return $this->option_base.$option;
	}

	private function set_option_base($plugin_name) {
		$pi = str_replace('-','_',$plugin_name);
		$this->option_base = $pi.'_';
	}
}

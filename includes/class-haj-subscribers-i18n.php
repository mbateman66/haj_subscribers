<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.zulicreative.com/
 * @since      1.0.0
 *
 * @package    Haj_Subscribers
 * @subpackage Haj_Subscribers/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Haj_Subscribers
 * @subpackage Haj_Subscribers/includes
 * @author     Matt Bateman <mbateman@laughingsky.com>
 */
class Haj_Subscribers_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'haj-subscribers',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

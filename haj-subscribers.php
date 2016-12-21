<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.zulicreative.com/
 * @since             1.0.0
 * @package           Haj_Subscribers
 *
 * @wordpress-plugin
 * Plugin Name:       Hajjoo Subscribers
 * Plugin URI:        http://www.zulicreative.com/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Matt Bateman
 * Author URI:        http://www.zulicreative.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       haj-subscribers
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-haj-subscribers-activator.php
 */
function activate_haj_subscribers() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-haj-subscribers-activator.php';
	Haj_Subscribers_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-haj-subscribers-deactivator.php
 */
function deactivate_haj_subscribers() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-haj-subscribers-deactivator.php';
	Haj_Subscribers_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_haj_subscribers' );
register_deactivation_hook( __FILE__, 'deactivate_haj_subscribers' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-haj-subscribers.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_haj_subscribers() {

	$plugin = new Haj_Subscribers();
	$plugin->run();

}
run_haj_subscribers();

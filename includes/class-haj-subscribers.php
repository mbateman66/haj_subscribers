<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.zulicreative.com/
 * @since      1.0.0
 *
 * @package    Haj_Subscribers
 * @subpackage Haj_Subscribers/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Haj_Subscribers
 * @subpackage Haj_Subscribers/includes
 * @author     Matt Bateman <mbateman@laughingsky.com>
 */
class Haj_Subscribers {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Haj_Subscribers_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'haj-subscribers';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Haj_Subscribers_Loader. Orchestrates the hooks of the plugin.
	 * - Haj_Subscribers_i18n. Defines internationalization functionality.
	 * - Haj_Subscribers_Admin. Defines all hooks for the admin area.
	 * - Haj_Subscribers_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-haj-subscribers-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-haj-subscribers-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-haj-subscribers-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-haj-subscribers-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-haj-subscribers-shared.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-haj-subscribers-options.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-haj-subscribers-db.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-haj-subscribers-subscriber.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-haj-subscribers-shortcodes.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-haj-subscribers-forms.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-haj-subscribers-ajax.php';

		$this->loader = new Haj_Subscribers_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Haj_Subscribers_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Haj_Subscribers_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Haj_Subscribers_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Haj_Subscribers_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts',	$plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts',	$plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init',			$plugin_public, 'register_shortcodes' );
		$this->loader->add_filter( 'dlm_can_download',		$plugin_public, 'can_download', 10, 2 );
		$this->loader->add_filter( 'wp_nav_menu_items',		$plugin_public, 'add_menu_signup', 10, 2 );

		$plugin_forms = new Haj_Subscribers_Forms( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_footer',			$plugin_forms, 'build_modal_signup_form' );
		$plugin_ajax = new Haj_Subscribers_Ajax( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_ajax_haj_subscribers_do_ajax_request',	$plugin_ajax, 'do_ajax_request' );
		$this->loader->add_action( 'wp_ajax_nopriv_haj_subscribers_do_ajax_request',	$plugin_ajax, 'do_ajax_request' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Haj_Subscribers_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}

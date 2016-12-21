<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.zulicreative.com/
 * @since      1.0.0
 *
 * @package    Haj_Subscribers
 * @subpackage Haj_Subscribers/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Haj_Subscribers
 * @subpackage Haj_Subscribers/public
 * @author     Matt Bateman <mbateman@laughingsky.com>
 */
class Haj_Subscribers_Public {

	private $plugin_name;
	private $version;
	private $opts;
//	private $db;
	private $shortcodes;
	private $subscriber;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->opts = new Haj_Subscribers_Options( $this->plugin_name, $this->version );
//		$this->db = new Haj_Subscribers_DB( $this->plugin_name, $this->version );
		$this->shortcodes = new Haj_Subscribers_Shortcodes( $this->plugin_name, $this->version );
		$this->subscriber = new Haj_Subscribers_Subscriber( $this->plugin_name, $this->version );
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
		 * defined in Haj_Subscribers_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Haj_Subscribers_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/haj-subscribers-public.css',
			array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'_font_awesome',
			'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"');

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
		 * defined in Haj_Subscribers_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Haj_Subscribers_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/haj-subscribers-public.js',
			array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'_cookies', plugin_dir_url( __FILE__ ) . 'js/haj-subscribers-cookies.js',
			array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'params', $this->get_params() );

	}


	private function get_params() {
		$params = array();
		$protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
		$params['ajax_url']=admin_url('admin-ajax.php',$protocol );
		$s_id=$_COOKIE['haj_subscriber_id'];
		if ($s_id) {
			$info=$this->subscriber->get_by_id($s_id);
			$params['id'] = $info['id'];
			$params['level'] = $info['level'];
			$params['fname'] = $info['fname'];
		}
		return($params);
	}

	/* Action and Filter Callbacks */

	public function register_shortcodes() {
		$this->shortcodes->register();
	}

	public function can_download($can_download,$download) {
		$s_id=$_COOKIE['haj_subscriber_id'];
		if ($s_id) {
			return $can_download;
		} else {
			return false;
		}
	}
	public function add_menu_signup($items,$args) {
		if ($this->opts->get_option('menu_signup_enable')) {
			$s_id=$_COOKIE['haj_subscriber_id'];
			if (!$s_id && $args->theme_location == 'primary_navigation') {
				$menu_text=$this->opts->get_option('menu_signup_text');
				if ($this->opts->get_option('menu_signup_icon_enable')) {
					$menu_icon= '<i class="'
						.$this->opts->get_option('menu_signup_icon')
						.'"></i>';
				} else {
					$menu_icon="";
				}
				$items.='<li id="menu_signup">'
					.'<a>'
					.$menu_icon
					.$menu_text
					.'</a>'
					.'</li>';
			}
		}
		return($items);
	}

}

<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.zulicreative.com/
 * @since      1.0.0
 *
 * @package    Haj_Subscribers
 * @subpackage Haj_Subscribers/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Haj_Subscribers
 * @subpackage Haj_Subscribers/admin
 * @author     Matt Bateman <mbateman@laughingsky.com>
 */
class Haj_Subscribers_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	private $opts;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->opts = new Haj_Subscribers_Options( $this->plugin_name, $this->version );
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ )
			. 'css/haj-subscribers-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) .
			'js/haj-subscribers-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
	
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Hajjoo Subscribers Settings', 'haj-subscribers' ),
			__( 'Hajjoo Subscribers', 'haj-subscribers' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	
	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/haj-subscribers-admin-display.php';
	}


	/**
	 * Create all the sections and settings
	 *
	 * @since  1.0.0
	 */
	public function register_settings() {
		/* General */
		$section='general'; $section_label='General';
		$this->create_section($section,$section_label);

		/* Menu Signup */
		$section='menu-signup'; $section_label='Menu Signup';
		$this->create_section($section,$section_label);

		$option='menu_signup_enable'; $option_label='Menu Signup Enable'; $option_type='checkbox';
			$this->create_and_register_option($option,$option_label,$section,$option_type);
		$option='menu_signup_text'; $option_label='Menu Signup Text'; $option_type='text';
			$this->create_and_register_option($option,$option_label,$section,$option_type);
		$option='menu_signup_icon_enable'; $option_label='Menu Signup Icon Enable'; $option_type='checkbox';
			$this->create_and_register_option($option,$option_label,$section,$option_type);
		$option='menu_signup_icon'; $option_label='Menu Signup Icon'; $option_type='text';
			$this->create_and_register_option($option,$option_label,$section,$option_type);

		/* Messages */
		$section='messages'; $section_label='Messages';
		$this->create_section($section,$section_label);

		$option='download_signup_text'; $option_label='Download Signup Text'; $option_type='textarea';
			$this->create_and_register_option($option,$option_label,$section,$option_type);

		/* Buttons */
		$section='buttons'; $section_label='Buttons';
		$this->create_section($section,$section_label);

		$option='submit_button_text'; $option_label='Submit Button Text'; $option_type='text';
			$this->create_and_register_option($option,$option_label,$section,$option_type);
		$option='signup_button_text'; $option_label='Signup Button Text'; $option_type='text';
			$this->create_and_register_option($option,$option_label,$section,$option_type);
		$option='download_button_text'; $option_label='Download Button Text'; $option_type='text';
			$this->create_and_register_option($option,$option_label,$section,$option_type);

		/* Mailchimp */
		$section='mailchimp'; $section_label='Mailchimp';
		$this->create_section($section,$section_label);

		$option='mailchimp_enable'; $option_label='Mailchimp Enable'; $option_type='checkbox';
			$this->create_and_register_option($option,$option_label,$section,$option_type);
		$option='mailchimp_double_optin'; $option_label='Mailchimp Double Opt-in'; $option_type='checkbox';
			$this->create_and_register_option($option,$option_label,$section,$option_type);
		$option='mailchimp_apikey'; $option_label='Mailchimp API Key'; $option_type='text';
			$this->create_and_register_option($option,$option_label,$section,$option_type);
		$option='mailchimp_listid'; $option_label='Mailchimp List ID'; $option_type='text';
			$this->create_and_register_option($option,$option_label,$section,$option_type);

	}

	/**
	 * Generic function for creating and registering option section
	 *
	 * @since  1.0.0
	 */
	function create_section ($section,$section_label) {
		add_settings_section(
			$this->option_base.$section,
			__( $section_label, $this->plugin_name ),
			array( $this, 'display_section_cb'),
			$this->plugin_name
		);
	}

	/**
	 * Generic function for creating and registering options
	 *
	 * @since  1.0.0
	 */
	function create_and_register_option ($option,$label,$section,$type,$args=null) {
		$option_name = $this->opts->get_option_name($option);
		$section_name = $this->option_base.$section;
		if ($args == null) { $args = array(); }
		/* Register it */
		register_setting( $this->plugin_name, $option_name, array($this, 'validate_option'));
		/* Setup Args */
		$args['label_for']=$option_name;
		if (!isset($args['name'])) { $args['name']=$option; }
		if (!isset($args['type'])) { $args['type']=$type; }
		/* Create the form field */
		add_settings_field(
			$option_name,
			__( $label, $this->plugin_name ),
			array($this, 'display_setting_cb'),
			$this->plugin_name,
			$section_name,
			$args
		);
        }

	/**
	 * Create all the sections and settings
	 *
	 * @since  1.0.0
	 */
        public function display_section_cb($args){
        }

	public function display_setting_cb($args){
		$option_type=$args['type'];
		$option=$args['name'];
		$option_name=$this->opts->get_option_name($option);
		$val=$this->opts->get_option($option);
                if ($option_type=='text') {
                        if (isset($args['size'])) { $size=$args['size']; } else {$size='20'; }
                        echo "<input name='$option_name'"
                                ." type='text'"
                                ." size='$size'"
                                ." value='"
                                .$val
                                ."'"
                                ." />";
                } else if ($option_type=='textarea') {
                        if (isset($args['rows'])) { $rows=$args['rows']; } else {$rows=6; }
                        if (isset($args['size'])) { $size=$args['size']; } else {$size='40'; }
                        echo "<textarea name='$option_name'"
                                ." rows='$rows'"
                                ." cols='$size'"
                                ." >"
                                .$val
                                ."</textarea>";
                } else if ($option_type=='checkbox') {
                        if ($val) { $checked="checked"; } else { $checked=""; }
                        echo "<input name='$option_name'"
                                ." type='checkbox'"
                                ." value='1'"
                                ." xxx=".$val
                                ." ".$checked
                                ." />";
                } else {
                        echo "Unknown field type";
                }

        }

        public function validate_option($opt){
                return($opt);
        }
}

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
		$button_text = $this->get_attribute($atts,'signup_button_text',
			$this->opts->get_option('signup_button_text'));
		$flavor=$this->get_attribute($atts,'flavor','signup');
		$type='signup';
		$id='show_form';
		$classes='';
		$action="return do_button('show_form_".$flavor."')";
		$html=$this->build_button($button_text,$type,$action,null,$id,$classes);

		return $html;
	}

	public function sc_show($atts, $content=null) {
		$level=$this->get_attribute($atts,'level',1);
		$match=$this->get_attribute($atts,'match','eq');
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
		$level=$this->get_attribute($atts,'level',1);
		$match=$this->get_attribute($atts,'match','eq');
		$s_level=$this->subscriber->get_level();
		$ok_to_download= $this->subscriber->check_level($level,$s_level,$match);
		$invert_match = $this->subscriber->invert_match($match);
		$download_signup_text = $this->opts->get_option('download_signup_text');
		$download_button_text = $this->opts->get_option('download_button_text');
		$signup_button_text = $this->opts->get_option('signup_button_text');
		$download_id=$this->get_attribute($atts,'id',0);
		$atts['flavor'] = 'download';

		if ($ok_to_download) {
			$hide = '';
			$invert_hide = 'haj_hide';
		} else {
			$hide = 'haj_hide';
			$invert_hide = '';
		}
		// Wrapper
		$html  ='<div class="haj_download">';

		// Download
		$html .='<div class="haj_subscribe_show '.$hide.'"'
			.' haj_subscriber_level="'.$level.'"'
			.' haj_subscriber_match="'.$match.'"'
			.'>';
		$html .= $this->build_button($download_button_text,'download',null,'/download/'.$download_id,null,null);
		$html .= "</div>";

		// Signup
		$html .='<div class="haj_subscribe_show '.$invert_hide.'"'
			.' haj_subscriber_level="'.$level.'"'
			.' haj_subscriber_match="'.$invert_match.'"'
			.'>';
		$html .= '<div class="haj_download_signup_text">';
		$html .= $download_signup_text;
		$html .= "</div>";
		$html .= '<div class="haj_download_signup_button">';
		$html .= $this->sc_signup_button($atts);
		$html .= "</div>";
		$html .= "</div>";

		// End Wrapper
		$html .= "</div>";
		return $html;
	}


        public function build_button($text,$type,$action=null,$href=null,$id=null,$classes=null) {
		$class = "btn-haj";
		if ($type) $class.=' btn-'.$type;
		if ($classes) $class.=' '.$classes;

		$html = '<a';
		if ($class) $html .= ' class="'.$class.'"';
		if ($id) $html .= ' id="'.$id.'"';
		if ($action) $html .= ' onclick="'.$action.'"';
		if ($href) $html .= ' href="'.$href.'"';
		$html .='>';
		if ($text) $html .= $text;
		$html .='</a>';

		return $html;
        }
}

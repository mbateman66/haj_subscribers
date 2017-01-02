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

	public function build_subscribe_form ($button_name,$button_text_option_name,$top_sidebar_name) {
		$button_text = $this->opts->get_option($button_text_option_name);
		?>
			<div class="haj_subscribe_form_wrapper">
				<div class="widgets widgets_top">
					<?php dynamic_sidebar($top_sidebar_name); ?>
				</div>
				<form class="haj_subscribe_form" name="haj_subscribe_form" action="#"  onsubmit="return do_button('<?php echo $button_name; ?>')" >
					<div class="info_question">
						<div class="label">First Name:*</div>
						<input type="text"
							id="fname"
							name="fname"
							prompt="First Name"
							placeholder="First Name"
							required
						>
					</div>
					<div class="info_question">
						<div class="label">Email:*</div>
						<input type="email"
							id="email"
							name="email"
							prompt="Email Address"
							placeholder="Email Address"
							required
						>
					</div>
					<button type="submit" href=""
						class="btn-haj btn-submit"
						><?php echo $button_text?></button>
				</form>
				<div class="widgets widgets_bottom">
					<?php dynamic_sidebar('haj-subscribe-form-bottom'); ?>
				</div>
			</div>
		<?php
	}

	public function build_modal_subscribe_form ($modal_name) {
		$container_name = 'haj_modal_subscribe_form_'.$modal_name;
		$button_text_option_name = 'submit_button_text_'.$modal_name;
		$button_name = 'submit_subscribe_'.$modal_name;
		$top_sidebar_name = 'haj-subscribe-form-top-'.$modal_name;
		?>
		<div id="<?php echo $container_name; ?>" class="haj_modal_wrapper" onclick="do_button('hide_form_<?php echo $modal_name; ?>')">
			<div class="haj_modal_content" onclick="noop(event)">
				<div class="haj_modal_before_form"></div>
				<?php $this->build_subscribe_form($button_name,$button_text_option_name,$top_sidebar_name); ?>
				<i class="haj_modal_close fa fa-times" onclick="do_button('hide_form_<?php echo $modal_name; ?>')"></i>
				<div class="haj_modal_after_form"></div>
			</div>
		</div>
		<?php
	}

	public function build_modal_forms () {
		$this->build_modal_subscribe_form('signup');
		$this->build_modal_subscribe_form('download');
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

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

	public function build_signup_form () {
		$button_text = $this->opts->get_option('submit_button_text');
                include_once 'partials/haj-subscribers-signup-form.php';
	}

	public function build_modal_signup_form () {
		?>
		<div id="haj_signup_form_modal" class="haj_modal_wrapper">
			<div class="haj_modal_content">
				<?php $this->build_signup_form(); ?>
				<i class="haj_modal_close fa fa-times" onclick="do_button('hide_form')"></i>
			</div>
		</div>
		<?php
	}
}

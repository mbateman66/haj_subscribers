<?php

/**
 * Create a modal subscription form
 *
 * @link       http://www.zulicreative.com/
 * @since      1.0.0
 *
 * @package    Haj_Subscribers
 * @subpackage Haj_Subscribers/public/partials
 */
?>
<div id="haj_subscribe_modal" class="haj_modal_wrapper" onclick="do_button('hide_form')">
	<div class="haj_modal_content" onclick="noop(event)">
		<div class="haj_modal_before_form"></div>
		<div class="haj_subscribe_form_wrapper">
			<div class="widgets widgets_top">
				<div class="flavor flavor-signup">
					<?php dynamic_sidebar('haj-subscribe-form-top-signup'); ?>
				</div>
				<div class="flavor flavor-download">
					<?php dynamic_sidebar('haj-subscribe-form-top-download'); ?>
				</div>
			</div>
			<form class="haj_subscribe_form" name="haj_subscribe_form" action="#"
				onsubmit="return do_button('submit_subscribe')" >
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
					class="btn-haj btn-submit flavor flavor-signup"
					><?php echo $button_text_signup?></button>
				<button type="submit" href=""
					class="btn-haj btn-submit flavor flavor-download"
					><?php echo $button_text_download?></button>
			</form>
			<div class="widgets widgets_bottom">
				<?php dynamic_sidebar('haj-subscribe-form-bottom'); ?>
			</div>
		</div>
		<i class="haj_modal_close fa fa-times"
			onclick="do_button('hide_form')"></i>
		<div class="haj_modal_after_form"></div>
	</div>
</div>

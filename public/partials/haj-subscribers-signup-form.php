<?php

/**
 * Provide a signup form that can stand alone or be wrapped in a modal box
 *
 * @link       http://www.zulicreative.com/
 * @since      1.0.0
 *
 * @package    Haj_Subscribers
 * @subpackage Haj_Subscribers/public/partials
 */
?>
<div id="haj_signup_form">
	<form id="haj_signup_form" name="haj_signup_form" action=""
		onsubmit="return do_button('submit_signup')">
		<div class="info_question">
			<div class="label">First Name:*</div>
			<input type="text"
				id="fname"
				name="fname"
				prompt="First Name"
				required
			>
		</div>
		<div class="info_question">
			<div class="label">Email:*</div>
			<input type="text"
				id="email"
				name="email"
				prompt="Email Address"
				required
			>
		</div>
		<div id="haj_signup_form_message">&nbsp;</div>
		<button type="button" href=""
			class="btn-submit"
			onclick="return do_button('submit_signup')"><?php echo $button_text?></button>
	</form>
</div>

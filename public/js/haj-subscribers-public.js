(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	/* Intialize */
	$( document ).ready(function() {
		s_id=haj_subscriber_get_cookie('haj_subscriber_id');
		s_level=haj_subscriber_get_cookie('haj_subscriber_level');
		if (s_level === null) { s_level = 0; console.log("Setting level to 0");}
		refresh_show();

		// Setup action and cursor for menu signup
		var signup_menu_a = $('#menu_signup a');
		signup_menu_a.css( 'cursor', 'pointer');
		signup_menu_a.click(function($) {
			do_button('show_form');
		});
	});
})( jQuery );

/* Globals */
var info=null;
var s_id=null;
var s_level=null;


/* Make an AJAX call */
function do_ajax(what_to_do,contents) {
	contents.s_id=s_id;
	document.body.style.cursor = 'wait';
	jQuery.ajax({
		url:                    params.ajax_url,
		data:                   {
			action:         'haj_subscribers_do_ajax_request',
			what_to_do:     what_to_do,
			contents:       contents,
		},
		dataType:               'JSON',
		type:                   'POST',
		success:                function(data) {
			document.body.style.cursor = 'default';
			what_was_done = data.what_was_done;
			set_message(data.message,0);
			if (what_was_done == 'subscribe') {
				cookie_timeout = 24*180;
				s_id=data.contents.s_id;
				s_level=data.contents.s_level;
				haj_subscriber_set_cookie('haj_subscriber_id',s_id,cookie_timeout);
				haj_subscriber_set_cookie('haj_subscriber_level',s_level,cookie_timeout);
				do_hide_form();
				refresh_show();
			}
		},
		error:          function(errorThrown) {
			document.body.style.cursor = 'default';
			set_message(errorThrown,1);
		}
	});
}

/* Do Button */
var do_button=function(button) {
	set_message("");
	if (jQuery('#'+button).attr('disabled')) { return; }
	if (button==            'submit_signup') {
		do_subscribe();
	} else if (button==     'show_form') {
		do_show_form();
	} else if (button==     'hide_form') {
		do_hide_form();
	} else if (url=jQuery('#'+button).attr('href')) {
		window.location.href=url;
	}
	do_google_analytics(button);
};

/* Show and hide signup form */
function do_show_form() {
	jQuery('#haj_signup_form_modal').fadeIn(500);
}
function do_hide_form() {
	jQuery('#haj_signup_form_modal').fadeOut(200);
}

/* Process a new subscription */
function do_subscribe() {
	if (collect_info() ){
		contents=new Object();
		contents.info=info;
		do_ajax('subscribe',contents);
	} else {
		return(0);
	}
	do_hide_form();
	return(1);
}


/* Check to see if level matches */
function check_level(level,s_level,match) {
	if (level === null) { return false; }
	if (s_level === null) { s_level = 0;}
	if (match === null) { match = 'eq';}
	if (match === '') { match = 'eq';}
	var ret=false;
	if (match === 'eq') {
		if (s_level == level) { ret=true; }
	} else if (match === 'ne') {
		if (s_level != level) { ret=true; }
	} else if (match === 'ge') {
		if (s_level >= level) { ret=true; }
	} else if (match === 'le') {
		if (s_level <= level) { ret=true; }
	} else if (match === 'gt') {
		if (s_level > level) { ret=true; }
	} else if (match === 'lt') {
		if (s_level < level) { ret=true; }
	}
	return (ret);
}

/* Refresh what is shown and what is hidden */
function refresh_show() {
	jQuery('.haj_subscribe_show').each(function(index){
		var obj=jQuery(this);
		var level = obj.attr('haj_subscriber_level');
		var match = obj.attr('haj_subscriber_match');
		if (check_level(level,s_level,match)) {
			obj.removeClass("haj_hide");
		} else {
			obj.addClass("haj_hide");
		}
	});
	if (s_id) {
		jQuery('#menu_signup').addClass("haj_hide");
	}
}

/* Collect info from signup form */
function collect_info() {
	info=new Object();
	msg="";
	ret=1;
	jQuery('#haj_signup_form input, #haj_signup_form select').each(function(index){
		var input = jQuery(this);
		var key=input.attr('name');
		var val=input.val();
		var required=input.attr('required');
		if (! validate_info_field(key,val,required)) {
			$msg="Please enter a valid "+input.attr('prompt')
			set_signup_form_message($msg,1);
			ret=0;
			return(false);
		} else {
			set_signup_form_message("Success!",0);
		}
		info[key]=val;
	});
	return (ret);
}

/* Validate info field */
function validate_info_field(key,val,required) {
	if (required && !val) {
		return(false);
	} else if (key=="email") {
		var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(val);
	} else if (key=="first_name" || key=="last_name") {
		var re = /^[A-Za-z][A-Za-z '\.\-]+$/
		return re.test(val);
	} else {
		return(true);
	}
}

/* Set signup form message */
function set_signup_form_message(message,error) {
	jQuery('#haj_signup_form_message').text(message);
	if (error) {
		jQuery('#haj_signup_form_message').addClass('error');
	} else {
		jQuery('#haj_signup_form_message').removeClass('error');
	}
}


/* Send tracking info to Google */
function do_google_analytics(button) {
//		_gaq.push(['_trackEvent', 'Print Intent', document.location.pathname]); //for classic GA
	ga('send', 'event', button, document.location.pathname); //for Universal GA
}

/* Set toaster message */
function set_message(message,error) {
}













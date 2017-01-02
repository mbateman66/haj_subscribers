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
		if (! params) {
		}
		if (! params.id) { params.id = 0; console.log("Setting id to 0");}
		if (! params.level) { params.level = 0; console.log("Setting level to 0");}
		update_cookies();
		refresh_show();

		// Setup action and cursor for menu signup
		var signup_menu_a = $('#menu_signup a');
		signup_menu_a.css( 'cursor', 'pointer');
		signup_menu_a.click(function($) {
			do_button('show_form_signup');
		});
	});
})( jQuery );

/* Globals */
var info=null;


/* Make an AJAX call */
function do_ajax(what_to_do,contents) {
	contents.s_id=params.id;
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
			if (what_was_done == 'subscribe') {
				form_modal_basename = 'haj_modal_subscribe_form_';
				params.id=data.contents.id;
				params.level=data.contents.level;
				params.fname=data.contents.fname;
				update_cookies();
				do_hide_form(form_modal_basename+'signup');
				do_hide_form(form_modal_basename+'download');
				refresh_show();
			}
		},
		error:          function(errorThrown) {
			document.body.style.cursor = 'default';
		}
	});
}

function update_cookies () {
	cookie_timeout = 24*180;	// 180 days
	haj_subscriber_set_cookie('haj_subscriber_id',params.id,cookie_timeout);
}

/* Do Button */
function do_button(button) {
	form_modal_basename = 'haj_modal_subscribe_form_';
	if (jQuery('#'+button).attr('disabled')) { return; }
	if (button==            'submit_subscribe_signup') {
		modal_name='signup';
		do_subscribe(form_modal_basename+modal_name);
	} else if (button==     'show_form_signup') {
		modal_name='signup';
		do_show_form(form_modal_basename+modal_name);
	} else if (button==     'hide_form_signup') {
		modal_name='signup';
		do_hide_form(form_modal_basename+modal_name);
	} else if (button==     'submit_subscribe_download') {
		modal_name='download';
		do_subscribe(form_modal_basename+modal_name);
	} else if (button==     'show_form_download') {
		modal_name='download';
		do_show_form(form_modal_basename+modal_name);
	} else if (button==     'hide_form_download') {
		modal_name='download';
		do_hide_form(form_modal_basename+modal_name);
	} else if (url=jQuery('#'+button).attr('href')) {
		window.location.href=url;
	}
	do_google_analytics(button);
	return false;
};

/* Show and hide subscribe forms*/
function do_show_form(form_container_name) {
	jQuery('#'+form_container_name).fadeIn(500);
}
function do_hide_form(form_container_name) {
	jQuery('#'+form_container_name).fadeOut(200);
}
function noop(e) {
	if (!e) var e = window.event; 
	e.cancelBubble = true;
	if (e.stopPropagation) e.stopPropagation();
}

/* Process a new subscription */
function do_subscribe(form_container_name) {
	if (collect_info(form_container_name) ){
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
function check_level(level,match) {
	var s_level=params.level;
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
		if (check_level(level,match)) {
			obj.removeClass("haj_hide");
		} else {
			obj.addClass("haj_hide");
		}
	});
	if (params.id) {
		jQuery('#menu_signup').addClass("haj_hide");
	}
}

/* Collect info from signup form */
function collect_info(form_container_name) {
	info=new Object();
	msg="";
	ret=1;
	jQuery('#'+form_container_name+' input').each(function(index){
		var input = jQuery(this);
		var key=input.attr('name');
		var val=input.val();
		info[key]=val;
	});
console.log(JSON.stringify(info));
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


/* Send tracking info to Google */
function do_google_analytics(button) {
console.log("GA", button, document.location.pathname);
//		_gaq.push(['_trackEvent', 'Print Intent', document.location.pathname]); //for classic GA
//	ga('send', 'event', button, document.location.pathname); //for Universal GA
	__gaTracker('send', 'event','button', button); // Use Universal GA from Monster Insights
}















<?php

/**
 * Mailchimp Interface
 *
 * @link 		http://zulicreative.com
 * @since 		1.0.0
 *
 * @package 		Haj_Subscribers
 * @subpackage 		Haj_Subscribers/includes
 * @author 		Matt Bateman
 */

// Prevent direct file access

if ( ! defined ( 'ABSPATH' ) ) { exit; }

class Haj_Subscribers_Mailchimp {
	private $plugin_name;
	private $version;
	private $opts;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->opts = new Haj_Subscribers_Options($this->plugin_name,$this->version);
	}


	public function subscribe($info) {
		$apikey		= $this->opts->get_option('mailchimp_apikey');
		$mc_enable	= $this->opts->get_option('mailchimp_enable');
		$list_id	= $this->opts->get_option('mailchimp_listid');
		$double_optin	= $this->opts->get_option('mailchimp_double_optin');

		// Bail if everything is not enabled and ready
		if (! $mc_enable) { return 1; }
		if (! $apikey) { return 0; }
		if (! $list_id) { return 0; }
		if (! $info) { return 0; }
		$email=$info['email'];
		if (! $email) { return 0; }

		// Setup
		$email_type='html';
		$update_existing = true;
		
		// Fix up vars
		$merge_vars = array();
		if ($info['fname']) { $merge_vars['FNAME'] = $info['fname'];}
		if ($info['lname']) { $merge_vars['LNAME'] = $info['lname'];}

		// Load Mailchimp API
		$mcapi=new MCAPI($apikey);
		$retval - $mcapi->listSubscribe($list_id,$email,$merge_vars,$email_type,$double_optin,$update_existing);

		if ($mcapi->errorCode) { 
			$msg="Error: ".$mcapi->errorCode;
			return 0;
		} else {
			return 1;
		}
	}
}

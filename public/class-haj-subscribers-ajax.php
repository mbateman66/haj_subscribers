<?php

/**
 * Supports all incoming Ajax requests
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

class Haj_Subscribers_Ajax {
	private $plugin_name;
	private $version;
	private $subscriber;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->subscriber = new Haj_Subscribers_Subscriber($this->plugin_name,$this->version);
	}

	// Parse the incoming request //
	public function do_ajax_request() {
		$what_to_do=$_REQUEST['what_to_do'];
		$req_contents=$_REQUEST['contents'];
		$message=null;
		$results=null;
		$s_id=$req_contents['s_id'];
		if ($what_to_do == 'subscribe') {
			$info=$req_contents['info'];
			$level=1;
			$results = $this->subscriber->subscribe($info['email'],$info['fname'],$level);
		} else {
			$results="Nice try";
			$message="Stay out of my code";
		}
		if ($results!=null) {
			$status=1;
			$info=$req_contents['info'];
		} else {
			$status=0;
			$results=null;
			$message=$haj_subscriber_error;
		}
		$response=array(
			'what_was_done' => $what_to_do,
			'status'        => $status,
			'contents'      => $results,
			'message'       => $message,
		);
		wp_send_json($response);
		exit();
	}
}

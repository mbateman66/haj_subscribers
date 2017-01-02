<?php

/**
 * Hooks into woocommerce
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

class Haj_Subscribers_Woocommerce {
	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function order_status_changed($id,$status='new', $new_status = 'pending') {
		$order = $this->get_order($id);
		$subscriber = new Haj_Subscribers_Subscriber($this->plugin_name,$this->version);
		$info=$subscriber->subscribe($order->billing_email,$order->billing_first_name);
		if ($info) {
			$s_id = $info['id'];
			$timeout = time()+60*60*24*180;	// 180 days
			setcookie('haj_subscriber_id',$s_id,$timeout,'/');
		}

	}

	private function get_order($order_id) {
		// Provide support for older WC versions if needed 
		if ( function_exists( 'wc_get_order' ) ) {
			return wc_get_order( $order_id );
		} else {
			return new WC_Order( $order_id );
		}
	}
}

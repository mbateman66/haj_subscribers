<?php

/**
 * All functions related to a subscriber
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

class Haj_Subscribers_Subscriber {
	private $plugin_name;
	private $version;
	private $table = 'haj_subscribers';
	private $opts;
	private $db;
	private $id;
	private $level;
	private $info;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->id = $_COOKIE['haj_subscriber_id'];
		$this->level = $_COOKIE['haj_subscriber_level'];

		$this->opts = new Haj_Subscribers_Options($this->plugin_name, $this->version);
		$this->db = new Haj_Subscribers_DB($this->plugin_name, $this->version);
	}

	public function create($data) {
		$now=current_time('mysql');
		$tmp_data=$this->sanitize($data);
		$tmp_data['create_date']=$now;
		$id=$this->db->insert($this->table,$tmp_data);
		return $id;
	}

	public function update($id,$data) {
		$tmp_data=$this->sanitize($data);
		$this->db->update($this->table,$tmp_data,$id);
	}

	public function get_by_id($id) {
		$where = "id=$id";
		return $this->db->get($this->table,$where);
	}

	public function get_by_email($email) {
		$email = strtolower($email);
		$where = 'email="'.$email.'"';
		return $this->db->get($this->table,$where);
	}

	public function is_valid($id) {
		if ($id) {
			return $this->db->is_valid($this->table,$id);
		} else {
			return false;
		}
	}

	private function sanitize($data) {
		// Only pass on defined fields
		$fields= array(
			'fname'         => 255,
			'email'         => 255,
			'level'         => 255,
		);
		foreach ($fields as $field=>$size) {
			if (isset($data[$field])) {
				$tmp_data[$field]=$this->sanitize_string($data[$field],1,$size);
			}
		}
		// Set email to lowercase
		if ( isset($tmp_data) && isset($tmp_data['email']) ) {
			$tmp_data['email'] = strtolower($tmp_data['email']);
		}
		return ($tmp_data);
	}

	private function sanitize_string($string, $min='', $max='') {
		$string = str_replace('\\\'','\'',$string);
// return $string;
		// no piping, passing possible environment variables ($),
		// seperate commands, nested execution, file redirection,
		// background processing, special commands (backspace, etc.), quotes
		// newlines, or some other special characters
		$pattern = '/(;|\||`|>|<|^|"|'."\n|\r|".'|{|}|[|]|\)|\()/i';
		$string = preg_replace($pattern, '', $string);

		//make sure this is only interpreted as ONE argument
		//      $string = '"'.preg_replace('/\$/', '\\\$', $string).'"';
		$len = strlen($string);
		if((($min != '') && ($len < $min)) || (($max != '') && ($len > $max))) {
			return FALSE;
		} else {
			return $string;
		}
	}

	public function subscribe($email=null,$fname=null,$level=1) {
		$tmp_data = null;
		if ($email) { $email = strtolower($email);}
		$id = $_COOKIE['haj_subscriber_id'];
		// See if it exists 
		if ($id) {
			$tmp_data = $this->get_by_id($id);
		} else if ($email) {
			$tmp_data = $this->get_by_email($email);
			$id = $tmp_data['id'];
		}
		if (! $tmp_data) {
			// Could not find one, so create it 
			$tmp_data= array();
			$tmp_data['email']=$email;
			$tmp_data['fname']=$fname;
			$tmp_data['level']=$level;
			$id = $this->create($tmp_data);
		} else {
			// Found one. See if we need to update it
			$update = 0;
			if ($tmp_data['email']!=$email) { $update=1; $tmp_data['email']=$email;}
			if ($tmp_data['fname']!=$fname) { $update=1; $tmp_data['fname']=$fname;}
			if ($tmp_data['level']<$level) { $update=1; $tmp_data['level']=$level; }
			if ($update) {
				$this->update($id,$tmp_data);
			}
		}
		/* Send data externally */
		$this->do_external($tmp_data);
		/* Return info */
		$res=array();
		$res['id']=$id;
		$res['email']=$tmp_data['email'];
		$res['level']=$tmp_data['level'];
		$res['fname']=$tmp_data['fname'];
		return ($res);
	}

	/* Update all external databases */
	public function do_external($info) {
		if ($this->opts->get_option('mailchimp_enable')) {
			$mc=new Haj_Subscribers_Mailchimp($this->plugin_name,$this->version);
			$mc->subscribe($info);
		}
	}

	public function get_id() {
		return $this->id;
	}

	public function get_level() {
		if ($this->id) {
			$info = $this->get_by_id($this->id);
		}
		if ($info['level']) { return $info['level']; } else { return 0; }
	}

	public function check_level($level,$s_level,$match) {
		if ($level == null ) { return 0; }
		if ($s_level == null ) { $s_level = 0; }
		if ($match == null ) { $match = 'eq'; }
		if ($match == '' ) { $match = 'eq'; }
		$ret=0;
		if ($match == 'eq') {
			if ($s_level == $level) { $ret = 1; }
		} else if ($match == 'ne') {
			if ($s_level != $level) { $ret = 1; }
		} else if ($match == 'ge') {
			if ($s_level >= $level) { $ret = 1; }
		} else if ($match == 'le') {
			if ($s_level <= $level) { $ret = 1; }
		} else if ($match == 'gt') {
			if ($s_level > $level) { $ret = 1; }
		} else if ($match == 'lt') {
			if ($s_level < $level) { $ret = 1; }
		}
		return $ret;
	}
	public function invert_match ($match) {
		if ($match == null ) { $match = 'eq'; }
		if ($match == '' ) { $match = 'eq'; }
		if ($match == 'eq') { return 'ne'; }
		if ($match == 'ne') { return 'eq'; }
		if ($match == 'gt') { return 'le'; }
		if ($match == 'lt') { return 'ge'; }
		if ($match == 'ge') { return 'lt'; }
		if ($match == 'le') { return 'gt'; }
	}
}

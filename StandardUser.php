<?php

class StandardUser {

	protected $user_obj;

	public function __construct(WP_User $user){
		$this->user_obj = $user;
	}

	public function get_id(){
		return $this->user_obj->ID;
	}

	public function get_meta($field){
		return get_user_meta($this->get_id(), $field, true);
	}

	public function get_email(){
		return $this->user_info->user_email;
	}

	public function get_username(){
		return $this->user_info->user_login;
	}

	public function get_registration_date($format = 'c'){

		$raw_date = $this->user_info->user_registered;
		$time = strtotime($raw_date);

		if ($time){

			return date($format, $time);

		}

	}

}
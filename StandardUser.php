<?php

class StandardUser extends IceskatingUphillBase {

	protected $user_obj;

	public function __construct(WP_User $user){
		$this->user_obj = $user;
	}

	/**
	 * Get user's WP ID
	 * @return string WordPress user ID
	 */
	public function get_id(){
		return $this->wp_user_obj->ID;
	}

	/**
	 * Get email address associated with the user's WP account
	 * @return string Email address
	 */
	public function get_email(){
		return $this->wp_user_obj->user_email;
	}

	/**
	 * Get WP username.
	 * @return string WordPress user name
	 */
	public function get_username(){
		return $this->wp_user_obj->user_login;
	}

	/**
	 * Get date that the user created their WordPress account
	 * @return string Date
	 */
	public function get_registration_date($format = 'c'){

		$raw_date = $this->wp_user_obj->user_registered;

		return self::format_date_string($raw_date, $format);

	}

	/**
	 * Get value of user's meta data
	 * @param  string $meta_key The key of the meta data
	 * @return mixed            The value of the meta data
	 */
	public function get_meta($meta_key){
		return get_user_meta($this->get_id(), $meta_key, true);
	}

	public function set_meta($meta_key, $meta_value){
		update_user_meta($this->get_id(), $meta_key, $meta_value);
	}

}


<?php

class StandardUser extends IceskatingUphillBase {

	protected $wp_user_obj;

	public function __construct(WP_User $user){
		$this->wp_user_obj = $user;
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

	/**
	 * Set the value of a user's meta data
	 * @param string $meta_key   The data's key
	 * @param mixed  $meta_value Data to be stored
	 */
	public function set_meta($meta_key, $meta_value){
		return update_user_meta($this->get_id(), $meta_key, $meta_value);
	}

	/**
	 * Create object from WP user ID
	 * @param  string $user_id WordPress user ID
	 * @return StandardUser    User object
	 */
	public static function create_from_id($user_id){

		$user = get_user_by('id', $user_id);

		if (is_a($user, 'WP_User')){

			$class = get_called_class();

			return new $class($user);

		}

	}

}


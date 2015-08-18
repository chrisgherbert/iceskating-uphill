<?php

class UserTests extends WP_UnitTestCase {

	function setUp(){

		parent::setUp();

		// Create basic user
		$this->user_id = $this->factory->user->create();
		$this->wp_user = get_user_by('id', $this->user_id);
		$this->user = new StandardUser($this->wp_user);

	}

	///////////
	// Tests //
	///////////

	/**
	 * @covers StandardUser::__construct()
	 */
	function test_instantiation_type(){
		$this->assertInstanceOf('StandardUser', $this->user);
	}

	/**
	 * __construct() should create an error when passed a value that isn't a 
	 * WP_Post object
	 * @covers            StandardUser::__construct()
	 * @expectedException PHPUnit_Framework_Error
	 */
	function test_invalid_constructor_parameter_int(){
		new StandardUser('123');
	}

	/**
	 * get_id() should return an integer
	 * @covers StandardUser::get_id()
	 */
	function test_get_id_is_integer(){
		$this->assertInternalType('int', $this->user->get_id());
	}

	/**
	 * get_id() should return a valid user ID
	 * @covers StandardUser::get_id()
	 */
	function test_get_id_returns_valid_user_id(){
		$this->assertNotEquals(false, get_user_by('id', $this->user->get_id()));
	}

	/**
	 * get_email() should return a valid email address
	 * @covers StandardUser::get_email()
	 */
	function test_get_email_valid_email_address(){

		$valid_email = filter_var($this->user->get_email(), FILTER_VALIDATE_EMAIL);

		$this->assertNotEquals(false, $valid_email);

	}

	/**
	 * get_username() should return a string
	 * @covers StandardUser::get_username()
	 */
	function test_get_username_is_string(){
		$this->assertInternalType('string', $this->user->get_username());
	}

	/**
	 * get_username() should return a valid username that matches the user 
	 * that the object represents
	 * @covers StandardUser::get_username()
	 */
	function test_get_username_valid(){

		$wp_user = get_user_by('login', $this->user->get_username());

		$this->assertEquals($wp_user->user_login, $this->user->get_username());

	}

	/**
	 * get_meta() should properly return the stored meta data.
	 * @covers StandardUser::get_meta()
	 */
	function test_get_meta_returns_proper_data(){

		$string_data = 'This is a string.';
		$array_data = array('this', 'is', 'an', 'array');
		$int_data = 1234;
		$bool_data = false;

		update_user_meta($this->user_id, 'string_data', $string_data);
		update_user_meta($this->user_id, 'array_data', $array_data);
		update_user_meta($this->user_id, 'int_data', $int_data);
		update_user_meta($this->user_id, 'bool_data', $bool_data);

		$this->assertEquals($string_data, $this->user->get_meta('string_data'));
		$this->assertEquals($array_data, $this->user->get_meta('array_data'));
		$this->assertEquals($int_data, $this->user->get_meta('int_data'));
		$this->assertEquals($bool_data, $this->user->get_meta('bool_data'));

	}

	/**
	 * get_meta() should return false when passed a nonexistent meta key
	 * @covers StandardUser::get_meta()
	 */
	function test_get_meta_returns_false_for_nonexistent_meta_key(){
		$this->assertEquals(false, $this->user->get_meta('this_key_does_not_exist'));
	}

	/**
	 * returns integer for new meta data
	 * @covers StandardUser::set_meta()
	 */
	function test_set_meta_returns_integer_for_new_data(){
		$this->assertInternalType('int', $this->user->set_meta('new_key', 'This is new data!'));
	}

	/**
	 * Returns false for existing data (key and value exist and are the same)
	 * @covers StandardUser::set_meta()
	 */
	function test_set_meta_returns_false_for_unchanged_data(){

		$this->user->set_meta('new_key', 'new_value');

		$this->assertEquals(false, $this->user->set_meta('new_key', 'new_value'));

	}

	/**
	 * Sets user meta data
	 * @covers StandardUser::set_meta()
	 */
	function test_set_meta_sets_meta_data(){

		$key = 'new_key';
		$value = 'new_value';

		$this->user->set_meta($key, $value);

		$retrieved_value = get_user_meta($this->wp_user->ID, $key, true);

		$this->assertEquals($value, $retrieved_value);

	}

	/**
	 * get_registration_date() should return a valid date string
	 * @covers StandardUser::get_registration_date()
	 */
	function test_get_registration_date_is_valid_date(){

		$time = strtotime($this->user->get_registration_date());

		$this->assertNotEquals(false, $time);

	}

	/**
	 * get_registration_date() should format the date
	 * @covers StandardUser::get_registration_date()
	 */
	function test_get_registration_date_respects_format(){

		$default_format = $this->user->get_registration_date();
		$custom_format = $this->user->get_registration_date('Y');

		$this->assertNotEquals($default_format, $custom_format);

	}

}


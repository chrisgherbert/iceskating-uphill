<?php

class StandardQueriesTest extends WP_UnitTestCase {

	function setUp(){

		parent::setUp();

		$this->factory->post->create_many(10);

	}

	/**
	 * Returns a StandardPost instance
	 * @covers StandardQueries::get_post()
	 */
	function test_get_post_returns_StandardPost_instance(){

		$wp_post = $this->factory->post->create_and_get();

		$this->assertInstanceOf('StandardPost', StandardQueries::get_post($wp_post));

	}

	/**
	 * Throws error when not passed a WP_Post instance
	 * @covers StandardQueries::get_post()
	 * @expectedException PHPUnit_Framework_Error
	 */
	function test_get_post_throws_error_without_WP_Post(){
		StandardQueries::get_post('string');
	}

	/**
	 * Returns a StandardPost instance
	 * @covers StandardQueries::get_post_from_id()
	 */
	function test_get_post_from_id_returns_StandardPost_instance(){

		$post_id = $this->factory->post->create();

		$this->assertInstanceOf('StandardPost', StandardQueries::get_post_from_id($post_id));

	}

	/**
	 * Returns null with an invalid post ID
	 * @covers StandardQueries::get_post_from_id()
	 */
	function test_get_post_from_id_returns_null_with_invalid_post_id(){



	}

}
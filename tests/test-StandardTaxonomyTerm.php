<?php

class StandardTaxonomyTermTest extends WP_UnitTestCase {

	function setUp(){

		parent::setUp();

		$this->wp_term = $this->factory->term->create_and_get();

		$this->term = new StandardTaxonomyTerm($this->wp_term);

	}

	/**
	 * Throws error when not passed a stdClass object
	 * @expectedException PHPUnit_Framework_Error
	 * @covers StandardTaxonomyTerm::__construct()
	 */
	function test_construct_throws_error_with_wrong_type(){
		$bad_term = new StandardTaxonomyTerm('this should not work');
	}

	/**
	 * Returns an integer
	 * @covers StandardTaxonomyTerm::get_id()
	 */
	function test_get_id_returns_int(){
		$this->assertInternalType('int', $this->term->get_id());
	}

	/**
	 * Returns the term ID
	 * @covers StandardTaxonomyTerm::get_id()
	 */
	function test_get_id_returns_term_id(){
		$this->assertEquals($this->wp_term->term_id, $this->term->get_id());
	}

	/**
	 * Returns a string
	 * @covers StandardTaxonomyTerm::get_name()
	 */
	function test_get_name_returns_string(){
		$this->assertInternalType('string', $this->term->get_name());
	}

	/**
	 * Returns an integer
	 * @covers StandardTaxonomyTerm::get_taxonomy_id()
	 */
	function test_get_taxonomy_id_returns_int(){
		$this->assertInternalType('int', $this->term->get_taxonomy_id());
	}

	/**
	 * Returns a string
	 * @covers StandardTaxonomyTerm::get_taxonomy()
	 */
	function test_get_taxonomy_returns_string(){
		$this->assertInternalType('string', $this->term->get_taxonomy());
	}

	/**
	 * Returns a string
	 * @covers StandardTaxonomyTerm::get_description()
	 */
	function test_get_description_returns_string(){
		$this->assertInternalType('string', $this->term->get_description());
	}

	/**
	 * Returns an integer
	 * @covers StandardTaxonomyTerm::get_parent_id()
	 */
	function test_get_parent_id_returns_int(){
		$this->assertInternalType('int', $this->term->get_parent_id());
	}

	/**
	 * Returns null when no parent term exists
	 * @covers StandardTaxonomyTerm::get_parent()
	 */
	function get_parent_returns_null_if_no_parent_exists(){
		$this->assertEquals(null, $this->get_parent());
	}

	/**
	 * Returns instance of StandardTaxonomyTerm if parent exists
	 * @covers StandardTaxonomyTerm::get_parent()
	 */
	function get_parent_returns_StandardTaxonomyTerm_if_parent_exists(){

		$wp_term_with_parent = $this->factory->term->create_and_get(array(
			'parent' => $this->wp_term->term_id
		));

		$this->assertInstanceOf('StandardTaxonomyTerm', $wp_term_with_parent->get_parent());

	}

	/**
	 * Returns null when no posts are associated with the term
	 * @covers StandardTaxonomyTerm::get_posts()
	 */
	function get_posts_returns_null_when_no_associated_posts_exist(){
		$this->assertEquals(null, $this->term->get_posts());
	}

	function get_posts_returns_array(){
		$this->assertInternalType('array', $this->term->get_posts());
	}

}


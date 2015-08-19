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
	function test_get_parent_returns_null_if_no_parent_exists(){
		$this->assertEquals(null, $this->term->get_parent());
	}

	/**
	 * Returns instance of StandardTaxonomyTerm if parent exists
	 * @covers StandardTaxonomyTerm::get_parent()
	 */
	function test_get_parent_returns_StandardTaxonomyTerm_if_parent_exists(){

		$wp_term_with_parent = $this->factory->term->create_and_get(array(
			'parent' => $this->wp_term->term_id
		));

		$term_with_parent = new StandardTaxonomyTerm($wp_term_with_parent);

		$this->assertInstanceOf('StandardTaxonomyTerm', $term_with_parent->get_parent());

	}

	/**
	 * Returns array
	 * @covers StandardTaxonomyTerm::get_posts()
	 */
	function test_get_posts_returns_array(){
		$this->assertInternalType('array', $this->term->get_posts());
	}

	function test_get_posts_not_empty_when_associated_posts_exist(){

		$post_ids = $this->factory->post->create_many(10);

		foreach ($post_ids as $post_id){
			wp_set_post_terms($post_id, array($this->wp_term->term_id, $this->wp_term->taxonomy));
		}

		$this->assertNotEmpty($this->term->get_posts());

	}

	/**
	 * Returns empty value when no posts are associated with the term
	 * @covers StandardTaxonomyTerm::get_posts()
	 */
	function test_get_posts_returns_empty_when_no_associated_posts_exist(){
		$this->assertEmpty($this->term->get_posts());
	}

	function test_get_posts_returns_StandardPosts_associated_posts_exist(){

		$post_ids = $this->factory->post->create_many(10);

		foreach ($post_ids as $post_id){
			wp_set_post_terms($post_id, array($this->wp_term->term_id, $this->wp_term->taxonomy));
		}

		$this->assertContainsOnlyInstancesOf('StandardPost', $this->term->get_posts());

	}

	function test_get_child_terms_returns_array(){

		$this->markTestIncomplete('This test has not been implemented yet.');

		$child_term = $this->factory->term->create_and_get(array(
			'parent' => $this->wp_term->term_id
		));

		$post = $this->factory->post->create();

		wp_set_object_terms($post, $child_term->term_id, $child_term->taxonomy);

		$this->assertInternalType('array', $this->term->get_child_terms());

	}

	/**
	 * Returns a valid URL
	 * @covers StandardTaxonomyTerm::get_url()
	 */
	function test_get_url_returns_valid_url(){
		$valid_url = filter_var($this->term->get_url(), FILTER_VALIDATE_URL);
		$this->assertNotEquals(false, $valid_url);
	}

	/**
	 * Returns boolean
	 * @covers StandardTaxonomyTerm::is_child()
	 */
	function test_is_child_returns_boolean(){
		$this->assertInternalType('bool', $this->term->is_child());
	}

	/**
	 * Returns false when term has no parent
	 * @covers StandardTaxonomyTerm::is_child()
	 */
	function test_is_child_returns_false_when_term_has_no_parent(){

		$wp_term = $this->factory->term->create_and_get();

		$orphan_term = new StandardTaxonomyTerm($wp_term);

		$this->assertEquals(false, $orphan_term->is_child());

	}

	/**
	 * Returns true when term has parent
	 * @covers StandardTaxonomyTerm::is_child()
	 */
	function test_is_child_returns_true_when_term_has_parent(){

		$wp_term = $this->factory->term->create_and_get(array(
			'parent' => $this->wp_term->term_id
		));

		$child_term = new StandardTaxonomyTerm($wp_term);

		$this->assertEquals(true, $child_term->is_child());

	}

}



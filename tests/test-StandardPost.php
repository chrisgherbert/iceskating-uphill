<?php

class PostTests extends WP_UnitTestCase {

	function setUp(){

		parent::setUp();

		// Create basic instance
		$this->wp_post_id = $this->factory->post->create();
		$this->wp_post = get_post($this->wp_post_id);
		$this->standard_post = new StandardPost($this->wp_post);

		// Create post with long content
		$post_id = $this->factory->post->create(array(
			'post_content' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.',
			'post_excerpt' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.'
		));
		$this->long_content = new StandardPost(get_post($post_id));

		// Create post with a custom excerpt
		$post_id = $this->factory->post->create(array(
			'post_content' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.',
			'post_excerpt' => 'This is an excerpt!'
		));
		$this->custom_excerpt = new StandardPost(get_post($post_id));

		// Create post with custom post type
		$post_id = $this->factory->post->create(array(
			'post_type' => 'test_unit_post_type'
		));
		$this->custom_post_type = new StandardPost(get_post($post_id));

		// Create post with image tags in post content
		$post_id = $this->factory->post->create(array(
			'post_content' => 'This is post content. First image: <img src="https://farm6.staticflickr.com/5771/20016320504_3488ddbe3d_b.jpg"> Second Image: <img src="http://cdn.macrumors.com/images-new/logo.png">'
		));
		$this->post_with_images_in_content = new StandardPost(get_post($post_id));

	}

	///////////
	// Tests //
	///////////

	/**
	 * @covers StandardPost::__construct
	 */
	function test_instantiation_type(){
		$this->assertInstanceOf('StandardPost', $this->standard_post);
	}

	/**
	 * __construct should create an error when passed a value that isn't a 
	 * WP_Post object
	 * @covers            StandardPost::__construct()
	 * @expectedException PHPUnit_Framework_Error
	 */
	function test_invalid_constructor_parameter_int(){
		new StandardPost('123');
	}

	/**
	 * get_id() should return an integer
	 * @covers StandardPost::get_id()
	 */
	function test_get_id_return_type(){
		$this->assertInternalType('int', $this->standard_post->get_id());
	}

	/**
	 * get_id() should return the post ID
	 * @covers StandardPost::get_id()
	 */
	function test_get_id_equals_post_id(){
		$this->assertEquals($this->wp_post_id, $this->standard_post->get_id());
	}

	/**
	 * get_wp_post_obj() should return the WP_Post object
	 * @covers StandardPost::get_wp_post_obj()
	 */
	function test_get_wp_post_obj_is_post_object(){
		$this->assertSame($this->wp_post, $this->standard_post->get_wp_post_obj());
	}

	/**
	 * get_wp_post_obj() should return an instance of WP_Post
	 * @covers StandardPost::get_wp_post_obj()
	 */
	function test_get_wp_post_obj_type(){
		$this->assertInstanceOf('WP_Post', $this->standard_post->get_wp_post_obj());
	}

	/**
	 * get_title() should return a string
	 * @covers StandardPost::get_title()
	 */
	function test_get_title_type(){
		$this->assertInternalType('string', $this->standard_post->get_title());
	}

	/**
	 * get_post_slug() should return the post slug (post_name)
	 * @convers StandardPost::get_post_slug()
	 */
	function test_get_post_slug(){
		$this->assertEquals($this->wp_post->post_name, $this->standard_post->get_post_slug());
	}


	/**
	 * get_date() should return a string
	 * @covers StandardPost::get_date()
	 */
	function test_get_date_type(){
		$this->assertInternalType('string', $this->standard_post->get_date());
	}

	/**
	 * Returns a string
	 * @covers StandardPost::get_date_since()
	 */
	function test_get_date_since_returns_string(){
		$this->assertInternalType('string', $this->standard_post->get_date_since());
	}

	/**
	 * Returns a string when a date is present
	 * @covers StandardPost::get_time()
	 */
	function test_get_time_returns_string_when_date_present(){
		$this->assertInternalType('string', $this->standard_post->get_time());
	}

	/**
	 * get_date() should return a valid date string.  Pass it through 
	 * strtotime(), which should not equal false.
	 * @covers StandardPost::get_date()
	 */
	function test_get_date_valid_format(){

		$time = strtotime($this->standard_post->get_time());

		$this->assertNotEquals(false, $time);

	}

	/**
	 * get_date() should return formatted date based on passed format string.
	 * Passing two different date formats result in different strings.
	 * @covers StandardPost::get_date()
	 */
	function test_get_date_uses_format_parameter(){

		$first_format = $this->standard_post->get_date('Y');
		$second_format = $this->standard_post->get_date('c');

		$this->assertNotEquals($first_format, $second_format);

	}

	/**
	 * get_content_plain_text() should not contain HTML.
	 * @covers StandardPost::get_content_plain_text()
	 */
	function test_get_content_plain_text(){

		// Create post with HTML content
		$post_id = $this->factory->post->create(array(
			'post_content' => '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna nibh, viverra non, semper suscipit, posuere a, pede.</p>'
		));

		$post = new StandardPost(get_post($post_id));

		$output = $post->get_content_plain_text();
		$filtered_output = strip_tags($output);

		$this->assertEquals($output, $filtered_output);

	}

	/**
	 * Returns null when the post has no content
	 * @covers StandardPost::get_content_plain_text()
	 */
	function test_get_content_plain_text_returns_null_without_post_content(){

		$wp_post = $this->factory->post->create_and_get(array(
			'post_content' => ''
		));

		$post = new StandardPost($wp_post);

		$this->assertEquals(null, $post->get_content_plain_text());

	}

	/**
	 * get_excerpt() should return string that is 30 words long, when the post 
	 * content contains at least 30 words, by default
	 * @covers  StandardPost::get_excerpt()
	 */
	function test_get_excerpt_default_length(){

		$words = explode(' ', $this->long_content->get_excerpt());

		$this->assertEquals(30, count($words));

	}

	/**
	 * get_excerpt() should add &hellip; (ellipsis) to shortened content by default
	 * @covers StandardPost::get_excerpt()
	 */
	function test_get_excerpt_default_suffix(){

		$last_eight_chars = substr($this->long_content->get_excerpt(), -8);

		$this->assertEquals('&hellip;', $last_eight_chars);

	}

	/**
	 * get_excerpt() should add the specified suffix to the end of shortened content
	 * @content StandardPost::get_excerpt()
	 */
	function test_get_excerpt_custom_suffix(){

		$suffix = 'this-is-the-suffix';
		$suffix_length = strlen($suffix);

		$last_x_chars = substr($this->long_content->get_excerpt(30, $suffix), -$suffix_length);

		$this->assertEquals($last_x_chars, $suffix);

	}

	/**
	 * get_excerpt() should create a string with the specified length in words,
	 * for shortened content
	 * @covers StandardPost::get_excerpt()
	 */
	function test_get_excerpt_custom_word_count(){

		$words = 6;

		$excerpt = $this->long_content->get_excerpt($words);

		$excerpt_word_length = count(explode(' ', $excerpt));

		$this->assertEquals($words, $excerpt_word_length);

	}

	/**
	 * get_excerpt() should not return a shortened version of the post content 
	 * when a custom excerpt exists
	 * @covers StandardPost::get_excerpt()
	 */
	function test_get_excerpt_do_not_modify_custom_excerpt(){

		$excerpt_from_wp_post = $this->custom_excerpt->get_wp_post_obj()->post_excerpt;
		$filtered_excerpt_from_wp_post = apply_filters('the_excerpt', $excerpt_from_wp_post);
		$excerpt_from_standard_post = $this->custom_excerpt->get_excerpt(4, 'suffix that should not appear');

		$this->assertEquals($filtered_excerpt_from_wp_post, $excerpt_from_standard_post);

	}

	/**
	 * returns null when the post has no content
	 * @covers StandardPost::get_excerpt()
	 */
	function test_get_excerpt_returns_null_when_no_post_content(){

		$post_id = wp_insert_post(array(
			'post_title' => 'Title'
		));

		$post = new StandardPost(get_post($post_id));

		$this->assertEquals(null, $post->get_excerpt());

	}

	/**
	 * get_post_type() should return a string
	 * @covers StandardPost::get_post_type()
	 */
	function test_get_post_type_return_type(){
		$this->assertInternalType('string', $this->standard_post->get_post_type());
	}

	/**
	 * get_post_type() should return a string when the post is a custom post type
	 * @covers StandardPost::get_post_type()
	 */
	function test_get_post_type_cpt_return_type(){
		$this->assertInternalType('string', $this->custom_post_type->get_post_type());
	}

	/**
	 * get_post_type() should return the post's type
	 * @covers StandardPost::get_post_type()
	 */
	function test_get_post_type_is_posts_type(){
		$this->assertEquals($this->wp_post->post_type, $this->standard_post->get_post_type());
	}

	/**
	 * get_post_type() should return the post's type for custom post types
	 * @covers StandardPost::get_post_type()
	 */
	function test_get_post_type_cpt_is_posts_type(){
		$this->assertEquals($this->custom_post_type->get_wp_post_obj()->post_type, $this->custom_post_type->get_post_type());
	}

	/**
	 * get_post_type_labels() should return a stdClass instance
	 * @covers StandardPost::get_post_type_labels()
	 */
	function test_get_post_type_labels_returns_StdClass_when_label_exist(){
		$this->assertInstanceOf('stdClass', $this->standard_post->get_post_type_labels());
	}

	/**
	 * Returns null when post's type doesn't have labels set
	 * @covers StandardPost::get_post_type_labels()
	 */
	function test_get_post_type_labels_returns_null_when_labels_do_not_exist(){

		$post = new StandardPost($this->factory->post->create_and_get(array(
			'post_type' => 'video'
		)));

		$this->assertEquals(null, $post->get_post_type_labels());

	}

	function test_get_post_type_labels_contains_certain_things(){

		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);

		$labels = $this->standard_post->get_post_type_labels();

		$this->assertEquals(true, isset($labels->name));
		$this->assertEquals(true, isset($labels->singular_name));
		$this->assertEquals(true, isset($labels->add_new));
		$this->assertEquals(true, isset($labels->add_new_item));
		$this->assertEquals(true, isset($labels->edit_item));
		$this->assertEquals(true, isset($labels->new_item));
		$this->assertEquals(true, isset($labels->view_item));
		$this->assertEquals(true, isset($labels->search_items));
		$this->assertEquals(true, isset($labels->not_found));
		$this->assertEquals(true, isset($labels->not_found_in_trash));
		$this->assertEquals(true, isset($labels->parent_item_colon));
		$this->assertEquals(true, isset($labels->all_items));
		$this->assertEquals(true, isset($labels->menu_name));
		$this->assertEquals(true, isset($labels->name_admin_bar));

	}

	/**
	 * get_url() should return a valid URL
	 * @covers StandardPost::get_url()
	 */
	function test_get_url_returns_valid_url(){

		$valid_url = filter_var($this->standard_post->get_url(), FILTER_VALIDATE_URL);

		$this->assertNotEquals(false, $valid_url);

	}

	/**
	 * get_first_content_image_url() should return a valid URL when at least 
	 * one image is present in the content.
	 * @covers StandardPost::get_first_content_image_url()
	 */
	function test_get_first_content_image_url_returns_valid_url(){

		$image = $this->post_with_images_in_content->get_first_content_image_url();

		$this->assertNotEquals(false, filter_var($image, FILTER_VALIDATE_URL));

	}

	/**
	 * get_first_content_image_url() returns false when no content images are 
	 * present
	 * @covers StandardPost::get_first_content_image_url()
	 */
	function test_get_first_content_image_url_returns_false_when_no_image_exists(){

		$image =  $this->standard_post->get_first_content_image_url();

		$this->assertEquals(false, $image);

	}

	/**
	 * get_edit_url() returns a valid URL for a logged in admin user
	 * @covers StandardPost::get_edit_url()
	 */
	function test_get_edit_url_returns_valid_url_for_logged_in_admin_user(){

		// log in admin user
		wp_set_current_user(1);

		$edit_url = $this->standard_post->get_edit_url();

		$this->assertNotEquals(false, filter_var($edit_url, FILTER_VALIDATE_URL));

		wp_set_current_user(0);

	}

	/**
	 * get_edit_url() returns false without a logged in user
	 * @covers StandardPost::get_edit_url()
	 */
	function test_get_edit_url_returns_false_for_logged_out_user(){

		$edit_url = $this->standard_post->get_edit_url();

		$this->assertEquals(false, $edit_url);

	}

	/**
	 * get_edit_url() returns false for user who cannot edit posts
	 * @covers StandardPost::get_edit_url()
	 */
	function test_get_edit_url_returns_false_for_user_who_cannot_edit_posts(){

		// Create user
		$user_id = $this->factory->user->create();

		// Set user as logged in
		wp_set_current_user($user_id);

		// Ensure the user cannot edit posts
		$this->assertEquals(false, current_user_can('edit_posts'));

		$this->assertEquals(false, $this->standard_post->get_edit_url());

		wp_set_current_user(0);

	}

	/**
	 * get_meta() should properly return the stored meta data.
	 * @covers StandardPost::get_meta()
	 */
	function test_get_meta_returns_proper_data(){

		$string_data = 'This is a string.';
		$array_data = array('this', 'is', 'an', 'array');
		$int_data = 1234;
		$bool_data = false;

		// Create different types of meta data
		$this->standard_post->set_meta('string_data', $string_data);
		$this->standard_post->set_meta('array_data', $array_data);
		$this->standard_post->set_meta('int_data', $int_data);
		$this->standard_post->set_meta('bool_data', $bool_data);

		$this->assertEquals($string_data, $this->standard_post->get_meta('string_data'));
		$this->assertEquals($array_data, $this->standard_post->get_meta('array_data'));
		$this->assertEquals($int_data, $this->standard_post->get_meta('int_data'));
		$this->assertEquals($bool_data, $this->standard_post->get_meta('bool_data'));

	}

	/**
	 * get_meta() should return false when passed a nonexistent meta key
	 * @covers StandardPost::get_meta()
	 */
	function test_get_meta_returns_false_for_nonexistent_meta_key(){
		$this->assertEquals(false, $this->standard_post->get_meta('this_key_does_not_exist'));
	}

	/**
	 * Returns an array
	 * @covers StandardPost::get_template_data()
	 */
	function test_get_template_date_returns_array(){
		$this->assertInternalType('array', $this->standard_post->get_template_data());
	}

	/**
	 * Returns the meta ID (integer) when the meta key isn't already set
	 * @covers StandardPost::set_meta()
	 */
	function test_set_meta_returns_integer_when_meta_is_new(){
		$return_value = $this->standard_post->set_meta('test_key_2', 'test_value');
		$this->assertInternalType('int', $return_value);
	}

	/**
	 * Returns true when the meta key already existed and the value was updated
	 * @covers StandardPost::set_meta()
	 */
	function test_set_meta_returns_true_when_meta_is_updated(){

		$this->standard_post->set_meta('test_key', 'test_value');

		$return_value = $this->standard_post->set_meta('test_key', 'new_value');

		$this->assertEquals(true, $return_value);

	}

	/**
	 * Returns an instance of stdClass
	 * @covers StandardPost::get_post_type_labels()
	 */
	function test_get_post_type_labels_returns_stdClass(){
		$this->assertInstanceOf('stdClass', $this->standard_post->get_post_type_labels());
	}

	/**
	 * Returns a string
	 * @covers StandardPost::get_post_type_singular()
	 */
	function test_get_post_type_singular_returns_string(){
		$this->assertInternalType('string', $this->standard_post->get_post_type_singular());
	}

	/**
	 * Returns null when the post type has no singular label set
	 * @covers StandardPost::get_post_type_singular()
	 */
	function test_get_post_type_singular_returns_null_when_post_type_has_no_singular_label(){

		$wp_post = $this->factory->post->create_and_get(array(
			'post_type' => 'video'
		));

		$post = new StandardPost($wp_post);

		$this->assertEquals(null, $post->get_post_type_singular());

	}

	/**
	 * Returns an array when tags exist
	 * @covers StandardPost::get_tags()
	 */
	function test_get_tags_returns_array_when_tags_exist(){

		$wp_post = $this->factory->post->create_and_get();

		$post = new StandardPost($wp_post);

		wp_set_post_tags($wp_post->ID, array('tag1', 'tag2'));

		$this->assertInternalType('array', $post->get_tags());

	}

	/**
	 * Returns array of StandardTaxonomyTerm objects when tags exist
	 * @covers StandardPost::get_tags()
	 */
	function test_get_tags_returns_StandardTaxonomyTerm_objects_when_tags_exist(){

		$wp_post = $this->factory->post->create_and_get();

		$post = new StandardPost($wp_post);

		wp_set_post_tags($wp_post->ID, array('tag1', 'tag2'));

		$this->assertContainsOnlyInstancesOf('StandardTaxonomyTerm', $post->get_tags());

	}

	/**
	 * Returns an empty array when no tags exist
	 * @covers StandardPost::get_tags()
	 */
	function test_get_tags_returns_empty_array_when_no_tags_exist(){

		$post = new StandardPost(get_post($this->factory->post->create_and_get()));

		$this->assertEquals(0, count($post->get_tags()));

	}

	function test_get_thumbnail_url_returns_null_when_no_featured_image_is_set(){
		$post = new StandardPost(get_post($this->factory->post->create_and_get()));
		$this->assertEquals(null, $post->get_thumbnail_url());
	}

}



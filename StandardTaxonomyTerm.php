<?php

class StandardTaxonomyTerm extends IceskatingUphillBase {

	protected $term_obj;

	public function __construct($term_obj){
		$this->term_obj = $term_obj;
	}

	/**
	 * Get the term ID
	 * @return int Term ID
	 */
	public function get_id(){
		return $this->term_obj->term_id;
	}

	/**
	 * Get the term slug
	 * @return string The term slug
	 */
	public function get_slug(){
		return $this->term_obj->slug;
	}

	/**
	 * Get the term full human readable name
	 * @return string Term's full name
	 */
	public function get_name(){
		return $this->term_obj->name;
	}

	/**
	 * Get the term's taxonomy's ID
	 * @return int Taxonomy's ID
	 */
	public function get_taxonomy_id(){
		return $this->term_obj->term_taxonomy_id;
	}

	/**
	 * Get the term's taxonomy's slug
	 * @return string Taxonomy slug
	 */
	public function get_taxonomy(){
		return $this->term_obj->taxonomy;
	}

	/**
	 * Get the term's description field
	 * @return string Term's description
	 */
	public function get_description(){
		return $this->term_obj->description;
	}

	/**
	 * Get the term's parent term ID, if present;
	 * @return int Parent term ID
	 */
	public function get_parent_id(){
		return $this->term_obj->parent;
	}

	/**
	 * Get the parent term
	 * @return StandardTaxonomyTerm Parent taxonomy term
	 */
	public function get_parent(){

		if ($this->get_parent_id()){
			$parent = get_term($this->get_parent_id(), $this->get_taxonomy());
			return StandardQueries::get_term($parent);
		}

	}

	/**
	 * Get posts with term
	 * @param  string  $post_type        The desired post type
	 * @param  integer $number           The number of posts to retrieve
	 * @param  boolean $include_children If true, inlude posts that belong to the term's children
	 * @return array                     Array of posts
	 */
	public function get_posts($post_type = 'post', $number = -1, $include_children = false){

		$wp_posts = get_posts(array(
			'post_type' => $post_type,
			'posts_per_page' => $number,
			'tax_query' => array(
				array(
					'taxonomy' => $this->get_taxonomy(),
					'terms' => array($this->get_id()),
					'include_children' => $include_children
				)
			)
		));

		$posts = array();

		foreach ($wp_posts as $wp_post){
			$posts[] = new StandardPost($wp_post);
		}

		return $posts;

	}

	/**
	 * Check if taxonomy term has child terms
	 * @return boolean True if term has children
	 */
	public function has_children(){
		if ($this->get_child_terms()){
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Check if the term is a child
	 * @return boolean True is the term is a child
	 */
	public function is_child(){
		if ($this->get_parent_id()){
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Get child terms 
	 * @return array Array of StandardTaxonomyTerm objects
	 */
	public function get_child_terms(){

		$args = array(
			'child_of' => $this->get_id(),
			'hide_empty' => 0
		);

		$terms = get_terms($this->get_taxonomy(), $args);

		if ($terms){

			$tax_terms_array = array();

			foreach ($terms as $term){

				$tax_terms_array[] = StandardQueries::get_term($term);

			}

			return $tax_terms_array;

		}

	}

	/**
	 * Get term meta data
	 * @param  string $key Term meta key
	 * @return [type]      [description]
	 */
	public function get_meta($key){
		return get_tax_meta($this->get_id(), $key);
	}

	/**
	 * Get the term's archive URL
	 * @return string The term archive URL
	 */
	public function get_url(){

		$link = get_term_link($this->term_obj);

		if (!is_a($link, 'WP_Error')){
			return $link;
		}
		else {
			return $link->get_error_message();
		}

	}

}
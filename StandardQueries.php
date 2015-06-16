<?php

class StandardQueries {

	public static function get_posts(array $wp_posts){

		$posts = array();

		foreach ($wp_posts as $post_obj){
			$post = static::get_post($post_obj);
			$posts[] = $post;
		}

		return $posts;

	}

	public static function get_post(WP_Post $wp_post){

		return new StandardPost($wp_post);

	}

	public static function get_term(StdClass $term){

		return new StandardTaxonomyTerm($term);

	}

}
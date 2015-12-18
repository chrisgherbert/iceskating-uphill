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

	public static function get_term($term){

		return new StandardTaxonomyTerm($term);

	}

	public static function get_post_from_id($post_id){

		$wp_post = get_post($post_id);

		if ($wp_post){
			return static::get_post($wp_post);
		}

	}

}
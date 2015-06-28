<?php

class StandardPost {

	protected $wp_post_obj;
	protected $labels;

	////////////
	// Public //
	////////////

	public function __construct(WP_Post $wp_post_obj){
		$this->wp_post_obj = $wp_post_obj;
	}

	/**
	 * Get the post's WordPress post ID
	 * @return int Post ID
	 */
	public function get_id(){
		return $this->wp_post_obj->ID;
	}

	public function get_title(){
		return apply_filters('the_title', $this->wp_post_obj->post_title);
	}

	public function get_post_slug(){
		return $this->post_obj->post_name;
	}

	public function get_content(){
		return apply_filters('the_content', $this->wp_post_obj->post_content);
	}

	/**
	 * Get the post's content in plain text, with all tags removed
	 * @return string Plain text post content
	 */
	public function get_content_plain_text(){
		if ($this->wp_post_obj->post_content){
			return strip_tags($this->wp_post_obj->post_content);
		}
	}

	/**
	 * Get post's excerpt or a shortened version of the post's content
	 * @param  integer $length_in_words Length of the excerpt in words
	 * @param  string  $suffix          String to append to the end of the excerpt
	 * @return string                   Post excerpt
	 */
	public function get_excerpt($length_in_words = 30, $suffix = '&hellip;'){

		if ($this->wp_post_obj->post_excerpt()){
			return apply_filters('the_excerpt', $this->$this->wp_post_obj->post_excerpt());
		}
		else if ($this->get_content()){
			$stripped_content = strip_tags($this->get_content());
			return self::shorten_string_by_words($stripped_content, $length_in_words, $suffix);
		}

	}

	/**
	 * Get post's WP post type slug
	 * @return string Post's type
	 */
	public function get_post_type(){
		return $this->post_obj->post_type;
	}

	public function get_post_type_labels(){

		if (!$this->labels){
			$post_type_object = get_post_type_object($this->get_post_type());
			$this->labels = $post_type_object->labels;
		}

		if ($this->labels){
			return $this->labels;
		}

	}

	public function get_post_type_singular(){

		if ($this->get_post_type_labels()){
			return $this->get_post_type_labels()->singular_name;
		}

	}

	/**
	 * Get post's publication date
	 * @return string Post publication date
	 */
	public function get_date($format='F j, Y'){
		$date = get_the_date($format, $this->get_id());
		return $date;
	}

	public function get_time($format='g:i A'){
		return $this->get_date($format);
	}

	public function get_url(){
		return get_the_permalink($this->get_id());
	}

	/**
	 * Get URL of post featured image
	 * @param  string $size Wordpress image size handle
	 * @return string       Image URL
	 */
	public function get_thumbnail_url($size='large'){

		$attachment_id = get_post_thumbnail_id($this->get_id());

		return self::get_image_attachment_url($attachment_id, $size);
	}

	public function get_tags(){

		$wp_tags = wp_get_post_tags($this->get_id());

		$tags = array();

		foreach ($wp_tags as $wp_tag){
			$tags[] = StandardQueries::get_term($wp_tag);
		}

		if ($tags){
			return $tags;
		}

	}

	///////////////
	// Protected //
	///////////////

	/**
	 * Get URL of attachment image
	 * @param  int    $attachment_id Post ID of the attachment
	 * @param  string $size          Desired size (wp image size handle)
	 * @return string                Image URL
	 */
	protected static function get_image_attachment_url($attachment_id=false, $size='large'){

		if ($attachment_id){
			$image_array = wp_get_attachment_image_src($attachment_id, $size, false);
			return $image_array[0];
		}

	}

	protected static function get_file_attachment_url($attachment_id){
		return wp_get_attachment_url($attachment_id);
	}

	protected function format_date_string($date_string, $format='f j, Y'){
		$time = strtotime($date_string);
		return date($format, $time);
	}

	protected function get_meta($key){
		return get_post_meta($this->get_id(), $key, true);
	}

	/**
	 * Shorten a string by words. If the original string is already that short,
	 * just return it.
	 * @param  string  $string String that needs shortening
	 * @param  integer $words  The number of words in the string to return
	 * @param  string  $suffix The string to append to the shortened string
	 * @return string          Shortened string, followed by suffix.
	 */
	protected static function shorten_string_by_words($string, $words = 20, $suffix = '&hellip;'){

		$words_array = explode(' ', $string);

		if (count($words_array) > $words){

			$words_array = array_slice($words_array, 0, $words);

			return implode(' ', $words_array) . $suffix;

		}
		else {
			return $string;
		}

	}

	protected function set_meta($key, $value){

		$updated = update_post_meta($this->get_id(), $key, $value);

		if ($updated){
			return true;
		}

	}

}

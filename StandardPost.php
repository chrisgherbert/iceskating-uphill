<?php

class StandardPost extends IceskatingUphillBase {

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

	/**
	 * Get the post's WP_Post object
	 * @return WP_Post WordPress post object
	 */
	public function get_wp_post_obj(){
		return $this->wp_post_obj;
	}

	public function get_title(){
		return apply_filters('the_title', $this->wp_post_obj->post_title);
	}

	public function get_post_slug(){
		return $this->wp_post_obj->post_name;
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

		// If the excerpt exists and isn't the same as the content, use it
		if ($this->wp_post_obj->post_excerpt && $this->wp_post_obj->post_content != $this->wp_post_obj->post_excerpt){
			return apply_filters('the_excerpt', $this->wp_post_obj->post_excerpt);
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
		return $this->wp_post_obj->post_type;
	}

	public function get_post_type_labels(){

		$post_type_object = get_post_type_object($this->get_post_type());

		if ($post_type_object){
			return $post_type_object->labels;
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

	public function get_date_since(){
		return self::format_date_since($this->get_date());
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

		return $tags;

	}

	/**
	 * Get post's first embedded image URL
	 * @return string URL of the first image embedded in the post
	 */
	public function get_first_content_image_url(){

		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $this->get_content(), $matches);

		if ($matches && $matches[1] && $matches[1][0]){
			$first_img = $matches[1][0];
			return $first_img;
		}

	}

	/**
	 * Get the edit post URL (will only work for users allowed to edit posts)
	 * @return string URL to edit the post
	 */
	public function get_edit_url(){
		return get_edit_post_link($this->get_id());
	}

	/**
	 * Get meta data
	 * @param  string $key Meta key
	 * @return string      Meta value
	 */
	public function get_meta($key){
		return get_post_meta($this->get_id(), $key, true);
	}

	/**
	 * Set meta date
	 * @param  string $key   Meta key
	 * @param  string $value Meta value
	 * @return bool          True on success
	 */
	public function set_meta($key, $value){

		$updated = update_post_meta($this->get_id(), $key, $value);

		return $updated;

	}

	public function get_template_data(){

		$data = array();

		$data['id'] = $this->get_id();
		$data['title'] = $this->get_title();
		$data['url'] = $this->get_url();
		$data['slug'] = $this->get_post_slug();
		$data['content'] = $this->get_content();
		$data['post_type'] = $this->get_post_type();
		$data['date'] = $this->get_date();
		$data['date_since'] = $this->get_date_since();
		$data['thumbnail_url'] = $this->get_thumbnail_url();

		return $data;

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
	protected static function get_image_attachment_url($attachment_id = null, $size = 'large'){

		if ($attachment_id){

			$image_array = wp_get_attachment_image_src($attachment_id, $size, false);

			if ($image_array !== false){
				return $image_array[0];
			}

		}

	}

	protected static function get_file_attachment_url($attachment_id){
		return wp_get_attachment_url($attachment_id);
	}

}

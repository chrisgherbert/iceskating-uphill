<?php

/**
 * Plugin Name:       Ice Skating Uphill - Object Oriented Posts
 * Plugin URI:        https://github.com/chrisgherbert/iceskating-uphill/
 * Description:       Provides classes to work with WordPress posts and taxonomy terms in simpler, more object-oriented way
 * Version:           1.0.1
 * Author:            Chris Herbert
 * Author URI:        http://chrisgherbert.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if (!defined('WPINC')){
	die;
}

$iceskating_uphill_plugin_path = plugin_dir_path(__FILE__);

$iceskating_uphill_classes = array(
	'StandardPost',
	'StandardTaxonomyTerm',
	'StandardQueries',
	'StandardUser'
);

foreach ($iceskating_uphill_classes as $class){

	$full_path = $iceskating_uphill_plugin_path . $class . '.php';

	if (!class_exists($class) && file_exists($full_path)){
		require_once($full_path);
	}

}

add_action('after_setup_theme', function(){
	do_action('iceskating_uphill_loaded');
});
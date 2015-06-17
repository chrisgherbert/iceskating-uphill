<?php

/**
 * Plugin Name:       Ice Skating Uphill - Object Oriented Posts
 * Plugin URI:        https://github.com/chrisgherbert/iceskating-uphill/
 * Description:       Provides classes to work with WordPress posts and taxonomy terms in simpler, more object-oriented way
 * Version:           1.0.0
 * Author:            Chris Herbert
 * Author URI:        http://chrisgherbert.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$plugin_path = plugin_dir_path(__FILE__);

if (!class_exists('StandardPost')){
	require_once($plugin_path . '/classes/StandardPost.php');
}

if (!class_exists('StandardTaxonomyTerm')){
	require_once($plugin_path . '/classes/StandardTaxonomyTerm.php');
}

if (!class_exists('StandardQueries')){
	require_once($plugin_path . '/classes/StandardQueries.php');
}
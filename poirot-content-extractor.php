<?php
/*
Plugin Name: Poirot Content Extractor
Description: A WordPress plugin to extract content and images from a given URL.
Version: 1.0
Author: Hamid Safari
*/

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Define plugin constants for easy access to plugin paths and URLs
define('POIROT_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('POIROT_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required autoload files
require_once POIROT_PLUGIN_PATH . "autoload.php";


// Register WordPress hooks
add_action('add_meta_boxes', ['Poirot\MetaBox', 'register']); 
add_action('admin_enqueue_scripts', ['Poirot\Assets', 'enqueue']);
add_action('wp_ajax_poirot_extract_content', ['Poirot\AjaxHandler', 'extract']);
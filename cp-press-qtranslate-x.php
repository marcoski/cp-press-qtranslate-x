<?php
/**
 * Plugin Name: CpPress QTranslateX Integration
 * Plugin URI: https://github.com/marcoski/cp-press-qtranslate-x
 * Description: CpPress Plugin QtranslateX Integration plugin 
 * Version: 0.6.7-dev
 * Author: Marco Trognoni
 * Author URI: http://www.commonhelp.it
 * License: MIT
 */
if(!defined('ABSPATH')) exit;

define('CPPRESS_VERSION', '0.6.7-dev');

function cpq_init_language($url_info){
	if(!$url_info['doing_front_end']){
		require_once(dirname(__FILE__)."/cpq-admin.php");
	}else{
		require_once(dirname(__FILE__)."/cpq-front.php");
	}
}

add_action('qtranslate_init_language', 'cpq_init_language');

function cpq_style() {
	wp_enqueue_style( 'cppress-qtranslate-x', plugins_url('cppress-qtranslate-x.css', __FILE__));
}

add_action('admin_init', 'cpq_style');
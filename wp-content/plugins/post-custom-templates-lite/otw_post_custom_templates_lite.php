<?php
/**
 * Plugin Name: Post Custom Templates Lite
 * Plugin URI: http://OTWthemes.com 
 * Description: Post Custom Templates will help you in changing your theme's default demplate for single posts. Create custom templates - fast & easy.
 * Author: OTWthemes
 * Version: 1.12
 * Author URI: https://codecanyon.net/user/otwthemes/portfolio?ref=OTWthemes
 */
load_plugin_textdomain('otw_pctl',false,dirname(plugin_basename(__FILE__)) . '/languages/');

$otw_pctl_plugin_id = 'b62ddbae05a670aa3bed2c2a49965068';
$otw_pctl_plugin_url = plugin_dir_url( __FILE__);
$otw_pctl_js_version = '0.1';
$otw_pctl_css_version = '0.1';

$otw_pctl_dispatcher = false;

//include functions
require_once( plugin_dir_path( __FILE__ ).'/include/otw_pctl_functions.php' );

if( !defined( 'OTW_PLUGIN_POST_CUSTOM_TEMPLATES_PRO' ) ){
	define( 'OTW_PLUGIN_POST_CUSTOM_TEMPLATES_PRO', 1 );
}

$otw_pctl_custom_css_path = '';
$otw_pctl_custom_css_url = '';

$upload_dir = wp_upload_dir();

if( isset( $upload_dir['basedir'] ) ){
	$otw_pctl_custom_css_path = $upload_dir['basedir'].'/otwpct/custom.css';
	$otw_pctl_custom_css_url =  set_url_scheme( $upload_dir['baseurl'] ).'/otwpct/custom.css';
}

//load core component functions
include_once( 'include/otw_components/otw_functions/otw_functions.php' );

if( !function_exists( 'otw_register_component' ) ){
	wp_die( 'Please include otw components' );
}

otw_set_up_memory_limit( '512M' );

$otw_pctl_grid_manager_component = false;
$otw_pctl_grid_manager_object = false;
$otw_pctl_shortcode_component = false;
$otw_pctl_shortcode_object = false;
$otw_pctl_factory_component = false;
$otw_pctl_factory_object = false;
$otw_pctl_content_sidebars_component = false;
$otw_pctl_form_component = false;
$otw_pctl_form_object = false;
$otw_pctl_image_component = false;
$otw_pctl_image_object = false;
$otw_pctl_image_profile = false;
	

//register grid manager component
otw_register_component( 'otw_post_template_grid_manager', dirname( __FILE__ ).'/include/otw_components/otw_post_template_grid_manager_light/', $otw_pctl_plugin_url.'include/otw_components/otw_post_template_grid_manager_light/' );

//register shortcode component
otw_register_component( 'otw_post_template_shortcode', dirname( __FILE__ ).'/include/otw_components/otw_post_template_shortcode/', $otw_pctl_plugin_url.'include/otw_components/otw_post_template_shortcode/' );

//register factory component
otw_register_component( 'otw_factory', dirname( __FILE__ ).'/include/otw_components/otw_factory/', $otw_pctl_plugin_url.'include/otw_components/otw_factory/' );

//register form component
otw_register_component( 'otw_form', dirname( __FILE__ ).'/include/otw_components/otw_form/', $otw_pctl_plugin_url.'include/otw_components/otw_form/' );

//register content sidebars component
otw_register_component( 'otw_content_sidebars', dirname( __FILE__ ).'/include/otw_components/otw_content_sidebars/', $otw_pctl_plugin_url.'include/otw_components/otw_content_sidebars/' );

//register image component
otw_register_component( 'otw_image', dirname( __FILE__ ).'/include/otw_components/otw_image/', '/include/otw_components/otw_image/' );

/** 
 *call init plugin function
 */
add_action('init', 'otw_pctl_init', 10000 );

if( is_admin() ){
	add_action( 'wp_ajax_otw_pctl_category_template_table', 'otw_pctl_category_template_table' );
	add_action( 'wp_ajax_otw_pctl_social_share', 'otw_pctl_social_share' );
	add_action( 'wp_ajax_nopriv_otw_pctl_social_share', 'otw_pctl_social_share' );
	add_action( 'wp_ajax_otw_pctl_get_video', 'otw_pctl_get_video' );
	add_action( 'wp_ajax_nopriv_otw_pctl_get_video', 'otw_pctl_get_video' );
	add_action( 'wp_ajax_otw_cpp', 'otw_pctl_count_post_previews' );
	add_action( 'wp_ajax_nopriv_otw_cpp', 'otw_pctl_count_post_previews' );
}else{
	add_action( 'template_redirect', 'otw_pctl_post_template', 1000000 );
}
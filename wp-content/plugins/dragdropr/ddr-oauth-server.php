<?php
/**
 * Plugin Name: DragDropr - Visual Drag & Drop Page Builder
 * Description: DragDropr is a What-You-See-Is-What-You-REALLY-Get visual editor. 
 * Version: 1.0.4
 * Author: atipso
 * Author URI: https://nnpro.at/
 *
 * Hello adventurer, and welcome to the DragDropr OAuth Server codebase!
 *
 * The codebase has three main parts:
 *   - OAuth token handling (lib/ddr-class-wp-rest-oauth1.php)
 *   - Frontend UI (lib/ddr-class-wp-rest-oauth1-ui.php and theme/ddr-oauth1-authorize.php)
 *   - Management and admin UI (everything else)
 *
 * Be very careful changing anything in the token handling; everything else is
 * up for grabs!
 *
 * Thanks for being fantastic. <3
 */

include_once( dirname( __FILE__ ) . '/lib/ddr-class-wp-rest-oauth1.php' );
include_once( dirname( __FILE__ ) . '/lib/ddr-class-wp-rest-oauth1-ui.php' );
include_once( dirname( __FILE__ ) . '/lib/ddr-class-wp-rest-client.php' );
include_once( dirname( __FILE__ ) . '/lib/ddr-class-wp-rest-oauth1-client.php' );

include_once( dirname( __FILE__ ) . '/ddr_admin.php' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	include_once( dirname( __FILE__ ) . '/lib/ddr-class-wp-rest-oauth1-cli.php' );

	WP_CLI::add_command( 'oauth1', 'WP_REST_OAuth1_CLI' );
}

/**
 * Register our rewrite rules for the API
 */
function ddr_rest_oauth1_init() {
	ddr_rest_oauth1_register_rewrites();

	global $wp;
	$wp->add_query_var('rest_oauth1');
}
add_action( 'init', 'ddr_rest_oauth1_init' );

function ddr_rest_oauth1_register_rewrites() {
	add_rewrite_rule( '^oauth1/authorize/?$','index.php?rest_oauth1=authorize','top' );
	add_rewrite_rule( '^oauth1/request/?$','index.php?rest_oauth1=request','top' );
	add_rewrite_rule( '^oauth1/access/?$','index.php?rest_oauth1=access','top' );
}

function ddr_rest_oauth1_setup_authentication() {
	register_post_type( 'json_consumer', array(
		'labels' => array(
			'name' => __( 'Consumer', 'rest_oauth1' ),
			'singular_name' => __( 'Consumers', 'rest_oauth1' ),
		),
		'public' => false,
		'hierarchical' => false,
		'rewrite' => false,
		'delete_with_user' => true,
		'query_var' => false,
	) );
}
add_action( 'init', 'ddr_rest_oauth1_setup_authentication' );

/**
 * Register the authorization page
 *
 * Alas, login_init is too late to register pages, as the action is already
 * sanitized before this.
 */
function ddr_rest_oauth1_load() {
	global $wp_json_authentication_oauth1;

	$wp_json_authentication_oauth1 = new DDR_WP_REST_OAuth1();
	add_filter( 'determine_current_user', array( $wp_json_authentication_oauth1, 'authenticate' ) );
	add_filter( 'rest_authentication_errors', array( $wp_json_authentication_oauth1, 'get_authentication_errors' ) );
}
add_action( 'init', 'ddr_rest_oauth1_load' );

/**
 * Force reauthentication after we've registered our handler
 *
 * We could have checked authentication before OAuth was loaded. If so, let's
 * try and reauthenticate now that OAuth is loaded.
 */
function ddr_rest_oauth1_force_reauthentication() {
	if ( is_user_logged_in() ) {
		// Another handler has already worked successfully, no need to
		// reauthenticate.

		return;
	}

	// Force reauthentication
	global $current_user;
	$current_user = null;

	wp_get_current_user();
}
add_action( 'init', 'ddr_rest_oauth1_force_reauthentication', 100 );

/**
 * Load the JSON API
 */
function ddr_rest_oauth1_loaded() {
	if ( empty( $GLOBALS['wp']->query_vars['rest_oauth1'] ) )
		return;

	rest_send_cors_headers( null );
	header( 'Access-Control-Allow-Headers: Authorization' );

	if ( $_SERVER['REQUEST_METHOD'] === 'OPTIONS' ) {
		die();
	}

	$authenticator = new DDR_WP_REST_OAuth1();
	$response = $authenticator->dispatch( $GLOBALS['wp']->query_vars['rest_oauth1'] );

	if ( is_wp_error( $response ) ) {
		$error_data = $response->get_error_data();
		if ( is_array( $error_data ) && isset( $error_data['status'] ) ) {
			$status = $error_data['status'];
		}
		else {
			$status = 500;
		}

		status_header( $status );
		echo $response->get_error_message();
		die();
	}

	header( 'Content-Type: application/x-www-form-urlencoded; charset=utf-8' );
	$response = http_build_query( $response, '', '&' );

	echo $response;

	// Finish off our request
	die();
}
add_action( 'template_redirect', 'ddr_rest_oauth1_loaded', -100 );

/**
 * Register v2 API routes
 *
 * @param object $response_object WP_REST_Response Object
 * @return object Filtered WP_REST_Response object
 */
function ddr_rest_oauth1_register_routes( $response_object ) {
	if ( empty( $response_object->data['authentication'] ) ) {
		$response_object->data['authentication'] = array();
	}

	$response_object->data['authentication']['oauth1'] = array(
		'request' => home_url( 'oauth1/request' ),
		'authorize' => home_url( 'oauth1/authorize' ),
		'access' => home_url( 'oauth1/access' ),
		'version' => '0.1',
	);
	return $response_object;
}
add_filter( 'rest_index', 'ddr_rest_oauth1_register_routes' );

/**
 * Register the authorization page
 *
 * Alas, login_init is too late to register pages, as the action is already
 * sanitized before this.
 */
function ddr_rest_oauth1_load_authorize_page() {
	$authorizer = new DDR_WP_REST_OAuth1_UI();
	$authorizer->register_hooks();
}
add_action( 'init', 'ddr_rest_oauth1_load_authorize_page' );

/**
 * Register routes and flush the rewrite rules on activation.
 */
function ddr_rest_oauth1_activation( $network_wide ) {
	if ( function_exists( 'is_multisite' ) && is_multisite() && $network_wide ) {

		$mu_blogs = wp_get_sites();

		foreach ( $mu_blogs as $mu_blog ) {

			switch_to_blog( $mu_blog['blog_id'] );
			ddr_rest_oauth1_register_rewrites();
			flush_rewrite_rules();
		}

		restore_current_blog();

	} else {

		ddr_rest_oauth1_register_rewrites();
		flush_rewrite_rules();

        $user = wp_get_current_user();
        $authorized = get_option('dragdropr_authorized');
        $token      = get_option('consumer_token');
        if ($authorized && $token) {
            try {
                //API Url
                $url =  DDR_WP_REST_OAuth1_Client::get_app_url() . 'wordpress/lock';

                //Initiate cURL.
                $ch = curl_init($url);

                //The JSON data.
                $jsonData = array(
                    'locked' => false,
                    'token'  => $token
                );

                //Encode the array into JSON.
                $jsonDataEncoded = json_encode($jsonData);

                //Tell cURL that we want to send a POST request.
                curl_setopt($ch, CURLOPT_POST, 1);

                //Attach our encoded JSON string to the POST fields.
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

                //Set the content type to application/json
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

                //Execute the request
                $result = curl_exec($ch);

            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }

        }
	}
}
register_activation_hook( __FILE__, 'ddr_rest_oauth1_activation' );

/**
 * Flush the rewrite rules on deactivation
 */
function ddr_rest_oauth1_deactivation( $network_wide ) {
	if ( function_exists( 'is_multisite' ) && is_multisite() && $network_wide ) {

		$mu_blogs = wp_get_sites();

		foreach ( $mu_blogs as $mu_blog ) {

			switch_to_blog( $mu_blog['blog_id'] );
			flush_rewrite_rules();
		}

		restore_current_blog();

	} else {

		flush_rewrite_rules();
	}

    $user = wp_get_current_user();
    $authorized = get_option('dragdropr_authorized');
    $token      = get_option('consumer_token');
    if ($authorized && $token) {
        try {
            //API Url
            $url =  DDR_WP_REST_OAuth1_Client::get_app_url() . 'wordpress/lock';

            //Initiate cURL.
            $ch = curl_init($url);

            //The JSON data.
            $jsonData = array(
                'locked' => true,
                'token'  => $token
            );

            //Encode the array into JSON.
            $jsonDataEncoded = json_encode($jsonData);

            //Tell cURL that we want to send a POST request.
            curl_setopt($ch, CURLOPT_POST, 1);

            //Attach our encoded JSON string to the POST fields.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

            //Set the content type to application/json
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

            //Execute the request
            $result = curl_exec($ch);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
register_deactivation_hook( __FILE__, 'ddr_rest_oauth1_deactivation' );

<?php
/**
 * Authorization page handler
 *
 * Takes care of UI and related elements for the authorization step of OAuth.
 *
 * @package WordPress
 * @subpackage JSON API
 */

class DDR_WP_REST_OAuth1_UI {
	/**
	 * Request token for the current authorization request
	 *
	 * @var array
	 */
	protected $token;

	/**
	 * Consumer post object for the current authorization request
	 *
	 * @var WP_Post
	 */
	protected $consumer;

	/**
	 * Register required actions and filters
	 */
	public function register_hooks() {
		add_action( 'login_form_oauth1_authorize', array( $this, 'handle_request' ) );
		add_action( 'oauth1_authorize_form', array( $this, 'page_fields' ) );
	}

	/**
	 * Handle request to authorization page
	 *
	 * Handles response from {@see render_page}, then exits to avoid output from
	 * default wp-login handlers.
	 */
	public function handle_request() {
		if ( ! is_user_logged_in() ) {
			wp_safe_redirect( wp_login_url( $_SERVER['REQUEST_URI'] ) );
			exit;
		}

		$response = $this->render_page();
		if ( is_wp_error( $response ) ) {
			$this->display_error( $response );
		}
		exit;
	}

	/**
	 * Render authorization page
	 *
	 * @return null|WP_Error Null on success, error otherwise
	 */
	public function render_page() {


		// Check required fields
		if ( empty( $_REQUEST['oauth_token'] ) ) {
			return new WP_Error( 'json_oauth1_missing_param', sprintf( __( 'Missing parameter %s', 'rest_oauth1' ), 'oauth_token' ), array( 'status' => 400 ) );
		}

		// Set up fields
		$token_key = wp_unslash( $_REQUEST['oauth_token'] );
		$scope = '*';
		if ( ! empty( $_REQUEST['wp_scope'] ) ) {
			$scope = wp_unslash( $_REQUEST['wp_scope'] );
		}



		$authenticator = new DDR_WP_REST_OAuth1();
		$errors = array();
		$this->token = $authenticator->get_request_token( $token_key );
		if ( is_wp_error( $this->token ) ) {
			return $this->token;
		}

		if ( ! empty( $_REQUEST['oauth_callback'] ) ) {
			$resp = $authenticator->set_request_token_callback( $this->token['key'], $_REQUEST['oauth_callback'] );
			if ( is_wp_error( $resp ) ) {
				return $resp;
			}
		}

        $verifier = $authenticator->authorize_request_token( $this->token['key'] );

//		if ( $this->token['authorized'] === true ) {
			$url = $this->request_access($verifier);
            header("Location: ". $url);
            exit;
//		}

//		// Fetch consumer
//		$this->consumer = $consumer = get_post( $this->token['consumer'] );
//
//		if ( ! empty( $_POST['wp-submit'] ) ) {
//			check_admin_referer( 'json_oauth1_authorize' );
//
//			switch ( $_POST['wp-submit'] ) {
//				case 'authorize':
//					$verifier = $authenticator->authorize_request_token( $this->token['key'] );
//					if ( is_wp_error( $verifier ) ) {
//						return $verifier;
//					}
//
//					return $this->handle_callback_redirect( $verifier );
//
//				case 'cancel':
//					exit;
//
//				default:
//					return new WP_Error( 'json_oauth1_invalid_action', __( 'Invalid authorization action', 'rest_oauth1' ), array( 'status' => 400 ) );
//			}
//		}
//
//		$file = locate_template( 'oauth1-authorize.php' );
//		if ( empty( $file ) ) {
//			$file = dirname( dirname( __FILE__ ) ) . '/theme/oauth1-authorize.php';
//		}
//
//		include $file;
	}

	/**
	 * Output required hidden fields
	 *
	 * Outputs the required hidden fields for the authorization page, including
	 * nonce field.
	 */
	public function page_fields() {
		echo '<input type="hidden" name="consumer" value="' . absint( $this->consumer->ID ) . '" />';
		echo '<input type="hidden" name="oauth_token" value="' . esc_attr( $this->token['key'] ) . '" />';
		wp_nonce_field( 'json_oauth1_authorize' );
	}

	/**
	 * Handle redirecting the user after authorization
	 *
	 * @param string $verifier Verification code
	 * @return null|WP_Error Null on success, error otherwise
	 */
	public function handle_callback_redirect( $verifier ) {
		if ( empty( $this->token['callback'] ) || $this->token['callback'] === 'oob' ) {
			// No callback registered, display verification code to the user
			login_header( __( 'Access Token', 'rest_oauth1' ) );
			echo '<p>' . sprintf( __( 'Your verification token is <code>%s</code>', 'rest_oauth1' ), $verifier ) . '</p>';
			login_footer();

			return null;
		}

		$callback = $this->token['callback'];

		// Ensure the URL is safe to access
		$authenticator = new DDR_WP_REST_OAuth1();
		if ( ! $authenticator->check_callback( $callback, $this->token['consumer'] ) ) {
			return new WP_Error( 'json_oauth1_invalid_callback', __( 'The callback URL is invalid', 'rest_oauth1' ), array( 'status' => 400 ) );
		}

		$args = array(
			'oauth_token' => $this->token['key'],
			'oauth_verifier' => $verifier,
			'wp_scope' => '*',
		);
		$args = apply_filters( 'json_oauth1_callback_args', $args, $this->token );
		$args = urlencode_deep( $args );
		$callback = add_query_arg( $args, $callback );

		// Offsite, so skip safety check
		wp_redirect( $callback );

		return null;
	}

	/**
	 * Display an error using login page wrapper
	 *
	 * @param WP_Error $error Error object
	 */
	public function display_error( WP_Error $error ) {
		login_header( __( 'Error', 'rest_oauth1' ), '', $error );
		login_footer();
	}

    /**
     * Redirects user to SignUp page
     *
     * @param $verifier
     * @return string
     */
	public function request_access( $verifier ) {
	    return DDR_WP_REST_OAuth1_Client::get_app_url()."/wordpress-callback?domain=".self::get_site_url()."&oauth_verifier=".$verifier;
    }


    public static function get_site_url( $blog_id = null, $path = '', $scheme = null ) {
        if ( empty( $blog_id ) || !is_multisite() ) {
            $url = get_option( 'siteurl' );
        } else {
            switch_to_blog( $blog_id );
            $url = get_option( 'siteurl' );
            restore_current_blog();
        }

        $url = set_url_scheme( $url, $scheme );

        if ( $path && is_string( $path ) )
            $url .= '/' . ltrim( $path, '/' );

        /**
         * Filters the site URL.
         *
         * @since 2.7.0
         *
         * @param string      $url     The complete site URL including scheme and path.
         * @param string      $path    Path relative to the site URL. Blank string if no path is specified.
         * @param string|null $scheme  Scheme to give the site URL context. Accepts 'http', 'https', 'login',
         *                             'login_post', 'admin', 'relative' or null.
         * @param int|null    $blog_id Site ID, or null for the current site.
         */

        return apply_filters( 'site_url', $url, $path, $scheme, $blog_id );
    }
}

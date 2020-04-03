<?php

class DDR_WP_REST_OAuth1_Admin {
	const BASE_SLUG = 'dragdropr';


	/**
	 * Register the admin page
	 */
	public static function register() {
		/**
		 * Include anything we need that relies on admin classes/functions
		 */
		include_once dirname( __FILE__ ) . '/ddr-class-wp-rest-oauth1-listtable.php';

		$hook = add_menu_page(
			// Page title
			__( 'DragDropr', 'dragdropr_page' ),

			// Menu title
			_x( 'DragDropr', 'menu title', 'dragdropr_menu' ),

			// Capability
			'list_users',

			// Menu slug
			self::BASE_SLUG,

			// Callback
			array( get_class(), 'dispatch' )
		);

		add_action( 'load-' . $hook, array( get_class(), 'load' ) );
	}

	/**
	 * Get the URL for an admin page.
	 *
	 * @param array|string $params Map of parameter key => value, or wp_parse_args string.
	 * @return string Requested URL.
	 */
	protected static function get_url( $params = array() ) {
		$url = admin_url( 'users.php' );
		$params = array( 'page' => self::BASE_SLUG ) + wp_parse_args( $params );
		return add_query_arg( urlencode_deep( $params ), $url );
	}

	/**
	 * Get the current page action.
	 *
	 * @return string One of 'add', 'edit', 'delete', or '' for default (list)
	 */
	protected static function current_action() {
		return isset( $_GET['action'] ) ? $_GET['action'] : '';
	}

	/**
	 * Load data for our page.
	 */
	public static function load() {
		switch ( self::current_action() ) {
			case 'edit':
                return;
			default:
				return;
		}

	}

	public static function dispatch() {
		switch ( self::current_action() ) {
			case 'add':
			case 'edit':
			case 'delete':
				return;

			default:
				return self::render();
		}
	}

	/**
	 * Render the main page.
	 */
	public static function render() {

        $wpUser = self::get_current_user();

        $activate_action = '';
        $activate_woo_commerce_action = '';

        if ( empty($wpUser->ID) ) {
            return;
        }

        $authorized = get_option('dragdropr_authorized');

        $hasWooCommerceActive = get_option('dragdropr_woo_commerce_active');

        if (!$hasWooCommerceActive) {
            $store_url = self::get_site_url();
            $endpoint = '/wc-auth/v1/authorize';
            $params = [
                'app_name' => 'DragDropr',
                'scope' => 'read',
                'user_id' => $wpUser->ID,
                'return_url' => self::get_site_url() . '/wp-admin/admin.php?page=dragdropr',
                'callback_url' => DDR_WP_REST_OAuth1_Client::get_app_url() . 'wordpress-woo-commerce-activate?domain='.self::get_site_url().'&XDEBUG_SESSION_START=14230'
            ];
            $query_string = http_build_query( $params );

            $activate_woo_commerce_action = $store_url . $endpoint . '?' . $query_string;
        }

//        if (!$authorized) {
            $consumerKey = substr(str_shuffle(MD5(microtime())), 0, 20);
            $consumerSecret = substr(str_shuffle(MD5(microtime())), 0, 20);
            $activate_action = DDR_WP_REST_OAuth1_Client::get_app_url()."wordpress-install?domain=".self::get_site_url()."&oauth_consumer_key=".$consumerKey."&oauth_consumer_secret=".$consumerSecret."&wordpress_user_id=".$wpUser->ID.'&wordpress_consumer_id='.$wpUser->ID;
//        }

        ?>
            <style>
                .ddr-btn {
                    font-size: 17px;
                    border: none;
                    padding: 5px 15px;
                    background-color: rgba(226, 118, 37, 1);
                    color: rgba(250, 250, 250, 1);
                    cursor: pointer;
                }

                .ddr-woo {
                    background-color: rgba(155, 92, 143, 1);
                }

                .ddr-btn:hover {
                    transition: background-color 0.5s ease;
                    background-color: rgba(239, 56, 27, 1);
                    border-color: rgba(239, 56, 27, 1);
                }

                .ddr-btn.disabled {
                    pointer-events: none;
                    cursor: default;
                    background-color: hsla(0,0%,71%,.5);
                    color: hsla(0,0%,100%,.5);
                }
            </style>
            <form method="post" target="_blank" action="<?php echo esc_url( $activate_action ) ?>">
                <?php

                if (!$authorized) {
                    ?>   <button id="submitButton" class="ddr-btn" type="submit" style="margin: 20px;">Authorize</button> <?php
                } else {
                    ?>    <button class="ddr-btn" type="submit" style="margin: 20px;">Reauthorize</button>  <?php
                }

                ?>

            </form>

        <form method="post" action="<?php echo esc_url( $activate_woo_commerce_action ) ?>">
            <?php

            if (class_exists( 'WooCommerce' )) {
                if ($authorized && !$hasWooCommerceActive) {
                    ?>
                    <button id="submitButton" class="ddr-btn ddr-woo" type="submit" style="margin: 20px;">Activate WooCommerce
                    </button> <?php
                } else if ($authorized && $hasWooCommerceActive) {
                    ?>
                    <button class="ddr-btn ddr-woo disabled" type="submit" disabled="disabled" style="margin: 20px;">Activate
                        WooCommerce
                    </button>  <?php
                }
            }
            ?>

        </form>

        <div class="wrap">
            <form method="POST" action="options.php">
                <?php
                    settings_fields( 'dragdropr' );
                    do_settings_sections( 'dragdropr' );
                    submit_button();
                ?>
            </form>
        </div>

        <?php
	}

	protected static function validate_parameters( $params ) {
		$valid = array();

		if ( empty( $params['name'] ) ) {
			return new WP_Error( 'rest_oauth1_missing_name', __( 'Consumer name is required', 'rest_oauth1' ) );
		}
		$valid['name'] = wp_filter_post_kses( $params['name'] );

		if ( empty( $params['description'] ) ) {
			return new WP_Error( 'rest_oauth1_missing_description', __( 'Consumer description is required', 'rest_oauth1' ) );
		}
		$valid['description'] = wp_filter_post_kses( $params['description'] );

		if ( empty( $params['callback'] ) ) {
			return new WP_Error( 'rest_oauth1_missing_description', __( 'Consumer callback is required and must be a valid URL.', 'rest_oauth1' ) );
		}
		if ( ! empty( $params['callback'] ) ) {
			$valid['callback'] = $params['callback'];
		}

		return $valid;
	}

	/**
	 * Handle submission of the add page
	 *
	 * @return array|null List of errors. Issues a redirect and exits on success.
	 */
	protected static function handle_edit_submit( $consumer ) {
		$messages = array();
		if ( empty( $consumer ) ) {
			$did_action = 'add';
			check_admin_referer( 'rest-oauth1-add' );
		}
		else {
			$did_action = 'edit';
			check_admin_referer( 'rest-oauth1-edit-' . $consumer->ID );
		}

		// Check that the parameters are correct first
		$params = self::validate_parameters( wp_unslash( $_POST ) );
		if ( is_wp_error( $params ) ) {
			$messages[] = $params->get_error_message();
			return $messages;
		}

		if ( empty( $consumer ) ) {
			$authenticator = new DDR_WP_REST_OAuth1();

			// Create the consumer
			$data = array(
				'name' => $params['name'],
				'description' => $params['description'],
				'meta' => array(
					'callback' => $params['callback'],
				),
			);
			$consumer = $result = DDR_WP_REST_OAuth1_Client::create( $data );
		}
		else {
			// Update the existing consumer post
			$data = array(
				'name' => $params['name'],
				'description' => $params['description'],
				'meta' => array(
					'callback' => $params['callback'],
				),
			);
			$result = $consumer->update( $data );
		}

		if ( is_wp_error( $result ) ) {
			$messages[] = $result->get_error_message();

			return $messages;
		}

		// Success, redirect to alias page
		$location = self::get_url(
			array(
				'action'     => 'edit',
				'id'         => $consumer->ID,
				'did_action' => $did_action,
			)
		);
		wp_safe_redirect( $location );
		exit;
	}

	/**
	 * Output alias editing page
	 */
	public static function render_edit_page() {
	}

	public static function handle_delete() {
		if ( empty( $_GET['id'] ) ) {
			return;
		}

		$id = $_GET['id'];
		check_admin_referer( 'rest-oauth1-delete:' . $id );

		if ( ! current_user_can( 'delete_post', $id ) ) {
			wp_die(
				'<h1>' . __( 'Cheatin&#8217; uh?', 'rest_oauth1' ) . '</h1>' .
				'<p>' . __( 'You are not allowed to delete this application.', 'rest_oauth1' ) . '</p>',
				403
			);
		}

		$client = DDR_WP_REST_OAuth1_Client::get( $id );
		if ( is_wp_error( $client ) ) {
			wp_die( $client );
			return;
		}

		if ( ! $client->delete() ) {
			$message = 'Invalid consumer ID';
			wp_die( $message );
			return;
		}

		wp_safe_redirect( self::get_url( 'deleted=1' ) );
		exit;
	}

	public static function handle_regenerate() {
		if ( empty( $_GET['id'] ) ) {
			return;
		}

		$id = $_GET['id'];
		check_admin_referer( 'rest-oauth1-regenerate:' . $id );

		if ( ! current_user_can( 'edit_post', $id ) ) {
			wp_die(
				'<h1>' . __( 'Cheatin&#8217; uh?', 'rest_oauth1' ) . '</h1>' .
				'<p>' . __( 'You are not allowed to edit this application.', 'rest_oauth1' ) . '</p>',
				403
			);
		}

		$client = DDR_WP_REST_OAuth1_Client::get( $id );
		$client->regenerate_secret();

		wp_safe_redirect( self::get_url( array( 'action' => 'edit', 'id' => $id, 'did_action' => 'regenerate' ) ) );
		exit;
	}

    public static function get_current_user() {
        if ( ! function_exists( 'wp_get_current_user' ) )
            return 0;
        $user = wp_get_current_user();
        return $user;
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

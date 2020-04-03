<?php

class DDR_WP_REST_OAuth1_Client extends DDR_WP_REST_Client {
	const CONSUMER_KEY_LENGTH = 12;
	const CONSUMER_SECRET_LENGTH = 48;

	/**
	 * Regenerate the secret for the client.
	 *
	 * @return bool|WP_Error True on success, error otherwise.
	 */
	public function regenerate_secret() {
		$params = array(
			'meta' => array(
				'secret' => wp_generate_password( self::CONSUMER_SECRET_LENGTH, false ),
			),
		);

		return $this->update( $params );
	}

    /**
     * Returns DragDropr application Url
     */
    public static function get_app_url() {
        return 'https://app.dragdropr.com/';
    }

    /**
     * Returns wordpress Url
     * @param null $blog_id
     * @param string $path
     * @param null $scheme
     * @return
     */
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

	/**
	 * Get the client type.
	 *
	 * @return string
	 */
	protected static function get_type() {
		return 'oauth1';
	}

	/**
	 * Add extra meta to a post.
	 *
	 * Adds the key and secret for a client to the meta on creation. Only adds
	 * them if they're not set, allowing them to be overridden for consumers
	 * with a pre-existing pair (such as via an import).
	 *
	 * @param array $meta Metadata for the post.
	 * @param array $params Parameters used to create the post.
	 * @return array Metadata to actually save.
	 */
	protected static function add_extra_meta( $meta, $params ) {
		if ( empty( $meta['key'] ) && empty( $meta['secret'] ) ) {
			$meta['key'] = wp_generate_password( self::CONSUMER_KEY_LENGTH, false );
			$meta['secret'] = wp_generate_password( self::CONSUMER_SECRET_LENGTH, false );
		}
		return parent::add_extra_meta( $meta, $params );
	}
}

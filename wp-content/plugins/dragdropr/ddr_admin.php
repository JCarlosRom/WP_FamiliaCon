<?php
/**
 * Administration UI and utilities
 */

require dirname( __FILE__ ) . '/lib/ddr-class-wp-rest-oauth1-admin.php';
require __DIR__ . '/vendor/autoload.php';

add_action( 'admin_menu', array( 'DDR_WP_REST_OAuth1_Admin', 'register' ) );

add_action( 'personal_options', 'ddr_rest_oauth1_profile_section', 50 );

add_action( 'all_admin_notices', 'ddr_rest_oauth1_profile_messages' );

add_action( 'personal_options_update',  'ddr_rest_oauth1_profile_save', 10, 1 );
add_action( 'edit_user_profile_update', 'ddr_rest_oauth1_profile_save', 10, 1 );

add_action( 'edit_form_after_title', 'ddr_add_new_page_in_dashboard' );

add_filter( 'page_row_actions',  'ddr_add_edit_page_in_dashboard' , 10, 2 );
add_filter( 'post_row_actions',  'ddr_add_edit_post_in_dashboard', 10, 2 );

add_action( 'rest_api_init', function ( $server ) {
    $server->register_route( 'delete-app', '/v1/dragdropr/user/(?P<id>\d+)/deactivate/(?P<consumer_id>\d+)', array(
        'methods'  => 'POST',
        'callback' => function ($data) {
            if (isset($data['id']) && isset($data['consumer_token'])) {
                try {
                    $authorized = get_option('dragdropr_authorized');
                    $token      = get_option('consumer_token');
                    if (!$authorized && $token != $data['consumer_token']) {
                        throw new Exception('Invalid consumer token!', 404);
                    }
                    delete_option('dragdropr_authorized');
                    delete_option('consumer_token');
                    if (get_option('dragdropr_woo_commerce_active')) {
                        delete_option('dragdropr_woo_commerce_active');
                    }
                    return json_encode(array(
                        'result' => 'Successfully deleted Wordpress application!',
                    ));
                } catch (Exception $e) {
                    echo json_encode(array(
                        'error' => array(
                            'msg' => $e->getMessage(),
                            'code' => $e->getCode(),
                        ),
                    ));
                }
            } else {
                throw new Exception('Post parameter token not supplied!', 404);
            }
        },
    ) );

    $server->register_route('V1/dragdropr', '/V1/dragdropr/jwt-token', array(
        'methods' => 'POST',
        'callback' => function(WP_REST_Request $request) {
            $authorizationToken = $request->get_header('DD-Authorization');
            $authorized = get_option('dragdropr_authorized');
            $authorizedToken = get_option('consumer_token');

            if ($authorized && $authorizedToken === $authorizationToken) {
                $data = $request->get_json_params();

                if (isset ($data['jwt_token'])) {
                    update_option('dragdropr_jwt_token', $data['jwt_token']);
                    $response = new WP_REST_Response('success');
                    $response->set_status(200);
                    return $response;
                }
            }

            return new WP_Error(422, ' Unprocessable entity', ['message' => 'Invalid given data']);
        }
    ));
} );

add_action( 'rest_api_init', function ( $server ) {
    $server->register_route( 'activate-app', '/v1/dragdropr/user/(?P<id>\d+)/activate/(?P<consumer_id>\d+)', array(
        'methods'  => 'POST',
        'callback' => function ($data) {
            if (isset($data['id']) && isset($data['consumer_token'])) {
                try {
                    update_option('dragdropr_authorized', (int)$data['consumer_id']);
                    update_option('consumer_token', $data['consumer_token']);
                    return json_encode(array(
                        'result' => 'Successfully installed Wordpress application!',
                    ));
                } catch (Exception $e) {
                    echo json_encode(array(
                        'error' => array(
                            'msg' => $e->getMessage(),
                            'code' => $e->getCode(),
                        ),
                    ));
                }
            } else {
                throw new Exception('Post parameter token not supplied!', 404);
            }
        },
    ) );
} );

add_action( 'rest_api_init', function ( $server ) {
    $server->register_route( 'create-page-with-dragdropr', '/v1/dragdropr/user/(?P<id>\d+)/page/create', array(
        'methods'  => 'POST',
        'callback' => function ($data) {
            if (isset($data['id']) && isset($data['consumer_token'])) {
                try {
                    $authorized = get_option('dragdropr_authorized');
                    $token      = get_option('consumer_token');
                    if (!$authorized && $token != $data['consumer_token']) {
                        throw new Exception('Invalid consumer token!', 404);
                    }
                    // Post filtering
                    kses_remove_filters();

                    $default = array();
                    $default['post_title']   = $data['post_title'];
                    $default['post_author']  = $data['post_author'];
                    $default['post_content'] = $data['post_content'];
                    $default['post_status']  = $data['post_status'];
                    $default['ping_status']  = 'open';
                    $default['post_type']    = 'page';
                    $default['filter']       = true;
                    $ID = wp_insert_post( $default );
                    if ( is_wp_error( $ID ) ) {
                        return $ID;
                    }

                    // Post filtering
                    kses_init_filters();

                    $post = get_post( $ID );
                    return $post;

                } catch (Exception $e) {
                    echo json_encode(array(
                        'error' => array(
                            'msg' => $e->getMessage(),
                            'code' => $e->getCode(),
                        ),
                    ));
                }
            } else {
                throw new Exception('Post parameter token not supplied!', 404);
            }
        },
    ) );
} );

add_action( 'rest_api_init', function ( $server ) {
    $server->register_route( 'edit-page-with-dragdropr', '/v1/dragdropr/user/(?P<id>\d+)/page/edit/(?P<pageId>\d+)', array(
        'methods'  => 'PUT',
        'callback' => function ($data) {
            if (isset($data['id']) && isset($data['consumer_token'])) {
                try {
                    $authorized = get_option('dragdropr_authorized');
                    $token      = get_option('consumer_token');
                    if (!$authorized && $token != $data['consumer_token']) {
                        throw new Exception('Invalid consumer token!', 404);
                    }
                    // Post filtering
                    kses_remove_filters();

                    $default = array();
                    $default['ID']           = $data['pageId'];
                    $default['post_title']   = $data['post_title'];
                    $default['post_author']  = $data['post_author'];
                    $default['post_content'] = $data['post_content'];
                    $default['post_status']  = $data['post_status'];
                    $default['ping_status']  = 'open';
                    $default['post_type']    = 'page';
                    $ID = wp_update_post( $default );
                    if ( is_wp_error( $ID ) ) {
                        return $ID;
                    }

                    // Post filtering
                    kses_init_filters();

                    $post = get_post( $ID );
                    return $post;

                } catch (Exception $e) {
                    echo json_encode(array(
                        'error' => array(
                            'msg' => $e->getMessage(),
                            'code' => $e->getCode(),
                        ),
                    ));
                }
            } else {
                throw new Exception('Put parameter token not supplied!', 404);
            }
        },
    ) );
} );

add_action( 'rest_api_init', function ( $server ) {
    $server->register_route( 'create-post-with-dragdropr', '/v1/dragdropr/user/(?P<id>\d+)/post/create', array(
        'methods'  => 'POST',
        'callback' => function ($data) {
            if (isset($data['id']) && isset($data['consumer_token'])) {
                try {
                    $authorized = get_option('dragdropr_authorized');
                    $token      = get_option('consumer_token');
                    if (!$authorized && $token != $data['consumer_token']) {
                        throw new Exception('Invalid consumer token!', 404);
                    }
                    // Post filtering
                    kses_remove_filters();

                    $default = array();
                    $default['post_title']   = $data['post_title'];
                    $default['post_author']  = $data['post_author'];
                    $default['post_content'] = $data['post_content'];
                    $default['post_status']  = $data['post_status'];
                    $default['ping_status']  = 'open';
                    $default['post_type']    = 'post';
                    $ID = wp_insert_post( $default );
                    if ( is_wp_error( $ID ) ) {
                        return $ID;
                    }

                    // Post filtering
                    kses_init_filters();

                    $post = get_post( $ID );
                    return $post;

                } catch (Exception $e) {
                    echo json_encode(array(
                        'error' => array(
                            'msg' => $e->getMessage(),
                            'code' => $e->getCode(),
                        ),
                    ));
                }
            } else {
                throw new Exception('Post parameter token not supplied!', 404);
            }


        },
    ) );
} );

add_action( 'rest_api_init', function ( $server ) {
    $server->register_route( 'edit-post-with-dragdropr', '/v1/dragdropr/user/(?P<id>\d+)/post/edit/(?P<postId>\d+)', array(
        'methods'  => 'PUT',
        'callback' => function ($data) {
            if (isset($data['id']) && isset($data['consumer_token'])) {
                try {
                    $authorized = get_option('dragdropr_authorized');
                    $token      = get_option('consumer_token');
                    if (!$authorized && $token != $data['consumer_token']) {
                        throw new Exception('Invalid consumer token!', 404);
                    }
                    // Post filtering
                    kses_remove_filters();

                    $default = array();
                    $default['ID']           = $data['postId'];
                    $default['post_title']   = $data['post_title'];
                    $default['post_author']  = $data['post_author'];
                    $default['post_content'] = $data['post_content'];
                    $default['post_status']  = $data['post_status'];
                    $default['ping_status']  = 'open';
                    $default['post_type']    = 'post';
                    $ID = wp_update_post( $default );
                    if ( is_wp_error( $ID ) ) {
                        return $ID;
                    }

                    // Post filtering
                    kses_init_filters();

                    $post = get_post( $ID );
                    return $post;

                } catch (Exception $e) {
                    echo json_encode(array(
                        'error' => array(
                            'msg' => $e->getMessage(),
                            'code' => $e->getCode(),
                        ),
                    ));
                }
            } else {
                throw new Exception('Put parameter token not supplied!', 404);
            }

        },
    ) );
} );


add_action( 'rest_api_init', function ( $server ) {
    $server->register_route( 'get-post-with-dragdropr', '/v1/dragdropr/user/(?P<id>\d+)/post/get/(?P<postId>\d+)', array(
        'methods'  => 'GET',
        'callback' => function ($data) {
            if (isset($data['id']) && isset($data['postId'])) {
                try {
                    $authorized = get_option('dragdropr_authorized');
                    if (!$authorized) {
                        throw new Exception('User is not authorized!', 404);
                    }

                    $post = get_post( $data['postId'] );
                    return $post;

                } catch (Exception $e) {
                    echo json_encode(array(
                        'error' => array(
                            'msg' => $e->getMessage(),
                            'code' => $e->getCode(),
                        ),
                    ));
                }
            } else {
                throw new Exception('Get parameter  not supplied!', 404);
            }

        },
    ) );
} );

add_action( 'rest_api_init', function ( $server ) {
    $server->register_route( 'get-woo-commerce-products-with-dragdropr', '/v1/dragdropr/user/(?P<id>\d+)/products/get', array(
        'methods'  => 'GET',
        'callback' => function ($data) {
            if (isset($data['id']) &&
                isset($data['consumer_key']) &&
                isset($data['consumer_secret']) &&
                isset($data['page']) &&
                isset($data['limit']) &&
                isset($data['title'])
            ) {
                try {
                    $authorized = get_option('dragdropr_authorized');
                    if (!$authorized) {
                        throw new Exception('User is not authorized!', 404);
                    }

                    if ( class_exists( 'WooCommerce' ) ) {

                        $woocommerce = new Automattic\WooCommerce\Client(
                            DDR_WP_REST_OAuth1_Admin::get_site_url(),
                            $data['consumer_key'],
                            $data['consumer_secret'],
                            [
                                'wp_api' => true,
                                'version' => 'wc/v2'
                            ]
                        );

                        $search = false;
                        if ($data['title'] != '') {
                            $search = $data['title'];
                        }

                        $data = [
                            'page' => $data['page'],
                            'per_page' => $data['limit'],
                            'status' => 'publish'
                        ];

                        if ($search) {
                            $data['search'] = $search;
                        }

                        $products = $woocommerce->get('products', $data);

                        return $products;

                    } else {
                       return false;
                    }

                } catch (Exception $e) {
                    echo json_encode(array(
                        'error' => array(
                            'msg' => $e->getMessage(),
                            'code' => $e->getCode(),
                        ),
                    ));
                }
            } else {
                throw new Exception('Get parameters not supplied!', 404);
            }

        },
    ) );
} );

add_action( 'rest_api_init', function ( $server ) {
    $server->register_route( 'get-woo-commerce-product-by-id-with-dragdropr', '/v1/dragdropr/user/(?P<id>\d+)/products/get/(?P<productId>\d+)', array(
        'methods'  => 'GET',
        'callback' => function ($data) {
            if (isset($data['id']) &&
                isset($data['consumer_key']) &&
                isset($data['consumer_secret'])
            ) {
                try {
                    $authorized = get_option('dragdropr_authorized');
                    if (!$authorized) {
                        throw new Exception('User is not authorized!', 404);
                    }

                    if ( class_exists( 'WooCommerce' ) ) {

                        $woocommerce = new Automattic\WooCommerce\Client(
                            DDR_WP_REST_OAuth1_Admin::get_site_url(),
                            $data['consumer_key'],
                            $data['consumer_secret'],
                            [
                                'wp_api' => true,
                                'version' => 'wc/v2'
                            ]
                        );

                        $product = $woocommerce->get('products/'.(int)$data['productId']);

                        return $product;

                    } else {
                        return false;
                    }

                } catch (Exception $e) {
                    echo json_encode(array(
                        'error' => array(
                            'msg' => $e->getMessage(),
                            'code' => $e->getCode(),
                        ),
                    ));
                }
            } else {
                throw new Exception('Get parameters not supplied!', 404);
            }

        },
    ) );
} );

add_action( 'rest_api_init', function ( $server ) {
    $server->register_route( 'get-woo-commerce-products-count-with-dragdropr', '/v1/dragdropr/user/(?P<id>\d+)/products/get/count', array(
        'methods'  => 'GET',
        'callback' => function ($data) {
            if (isset($data['id'])) {
                try {
                    $authorized = get_option('dragdropr_authorized');
                    if (!$authorized) {
                        throw new Exception('User is not authorized!', 404);
                    }

                    if ( class_exists( 'WooCommerce' ) ) {

                        $total_products = count( get_posts( array('post_type' => 'product', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => '-1') ) );

                        return $total_products;

                    } else {
                        return false;
                    }

                } catch (Exception $e) {
                    echo json_encode(array(
                        'error' => array(
                            'msg' => $e->getMessage(),
                            'code' => $e->getCode(),
                        ),
                    ));
                }
            } else {
                throw new Exception('Get parameters not supplied!', 404);
            }

        },
    ) );
} );

add_action( 'rest_api_init', function ( $server ) {
    $server->register_route( 'activate-woo-commerce-with-dragdropr', '/v1/dragdropr/user/(?P<id>\d+)/activateWooCommerce', array(
        'methods'  => 'POST',
        'callback' => function ($data) {
            if (isset($data['id']) ) {
                try {
                    $authorized = get_option('dragdropr_authorized');
                    if (!$authorized) {
                        throw new Exception('User is not authorized!', 404);
                    }

                    add_option('dragdropr_woo_commerce_active', true);

                } catch (Exception $e) {
                    echo json_encode(array(
                        'error' => array(
                            'msg' => $e->getMessage(),
                            'code' => $e->getCode(),
                        ),
                    ));
                }
            } else {
                throw new Exception('Post parameter not supplied!', 404);
            }

        },
    ) );
} );

function get_custom_users_data($data){
    //get users by market

    return $data;
}

function ddr_rest_oauth1_profile_section( $user ) {
	global $wpdb;

	$results = $wpdb->get_col( "SELECT option_value FROM {$wpdb->options} WHERE option_name LIKE 'oauth1_access_%'", 0 );
	$results = array_map( 'unserialize', $results );
	$approved = array_filter( $results, function ( $row ) use ( $user ) {
		return $row['user'] === $user->ID;
	} );
}

function ddr_rest_oauth1_profile_messages() {
	global $pagenow;
	if ( $pagenow !== 'profile.php' && $pagenow !== 'user-edit.php' ) {
		return;
	}

	if ( ! empty( $_GET['rest_oauth1_revoked'] ) ) {
		echo '<div id="message" class="updated"><p>' . __( 'Token revoked.', 'rest_oauth1' ) . '</p></div>';
	}
	if ( ! empty( $_GET['rest_oauth1_revocation_failed'] ) ) {
		echo '<div id="message" class="updated"><p>' . __( 'Unable to revoke token.', 'rest_oauth1' ) . '</p></div>';
	}
}

function ddr_rest_oauth1_profile_save( $user_id ) {
	if ( empty( $_POST['rest_oauth1_revoke'] ) ) {
		return;
	}

	$key = wp_unslash( $_POST['rest_oauth1_revoke'] );

	$authenticator = new DDR_WP_REST_OAuth1();

	$result = $authenticator->revoke_access_token( $key );
	if ( is_wp_error( $result ) ) {
		$redirect = add_query_arg( 'rest_oauth1_revocation_failed', true, get_edit_user_link( $user_id ) );
	}
	else {
		$redirect = add_query_arg( 'rest_oauth1_revoked', $key, get_edit_user_link( $user_id ) );
	}
	wp_redirect($redirect);
	exit;
}

/**
 * Add edit link in outside edit post.
 *
 * @access public
 * @since 1.0.0
 * @param $actions
 * @param $post
 *
 * @return array
 */
function ddr_add_edit_page_in_dashboard( $actions, $post ) {

    $wpUser = wp_get_current_user();

    if ( empty($wpUser->ID) ) {
        return;
    }

    $authorized = get_option('dragdropr_authorized');
    if ($authorized) {
        $data = [
            'entityType' => 'page',
            'urlParameters' => [
                'identifier' => $post->ID,
                'skipReload' => true
            ]
        ];
        $onClick = " onclick='window.DragDropr.Plugins.factory(document.createElement(\"textarea\")).execute(" . json_encode($data). ");return false;'";
        $action = sprintf(
            '<a target="_blank" href="%s"%s>%s</a>',
            dragdropr_apply_jwt_token(DDR_WP_REST_OAuth1_Client::get_app_url() . 'wordpress/pages/' . $post->ID . '?domain=' . DDR_WP_REST_OAuth1_Client::get_site_url()),
            $onClick,
            __('Edit with Dragdropr', 'dragdropr')
        );
    } else {
        $action = sprintf(
                '<a href="%s">%s</a>',
                DDR_WP_REST_OAuth1_Client::get_site_url().'/wp-admin/admin.php?page=dragdropr',
                __( 'Edit with Dragdropr', 'dragdropr' )
            );
    }

    $actions['edit_with_dragdropr$post->ID '] = $action;

    return $actions;

}/**
 * Add edit link in outside edit post.
 *
 * @access public
 * @since 1.0.0
 * @param $post
 *
 * @return array
 */
function ddr_add_new_page_in_dashboard( $post) {

    $wpUser = wp_get_current_user();

    if ( empty($wpUser->ID) ) {
        return;
    }

    $entityIdInput = null;
    $authorized = get_option('dragdropr_authorized');

    $action =  DDR_WP_REST_OAuth1_Client::get_site_url().'/wp-admin/admin.php?page=dragdropr';
    $target = '';
    $onClick = null;

    if ($authorized) {
        $onClick = ' onclick="window.DragDropr.Plugins.factory(document.getElementById(\'content\')).execute();return false;"';

        switch ($post->post_type) {
            case 'post':
                $entityIdInput = '<input id="dragdropr_entity_type" name="dragdropr_post_id" value="' . $post->ID . '" type="hidden"/>';
                break;
            case 'page':
                $entityIdInput = '<input id="dragdropr_entity_type" name="dragdropr_page_id" value="' . $post->ID . '" type="hidden"/>';
                break;
            default:
                $entityIdInput = '<input id="dragdropr_entity_type" name="dragdropr_other_id" value="' . $post->ID . '" type="hidden"/>';
                break;
        }
    }

?>
    <style type="text/css">
        .ddr-page-wrapper {
            position: relative;
            padding: 0px;
            margin: 0px;
            font-family: sans-serif;
            font-size: 16px;
        }

        .ddr-page-wrapper .ddr-page {
            position: relative;
        }

        .ddr-page-wrapper .ddr-page>div::after,
        .ddr-page-wrapper .ddr-page>div::before {
            content: "";
            display: table;
            clear: both;
        }

        .ddr-page-row {
            position: relative;
            box-sizing: border-box;
        }

        .ddr-page-row {
            display: flex;
            width: 100%;
        }

        .ddr-page-column {
            display: flex;
            flex-wrap: wrap;
            box-sizing: border-box;
            position: relative;
            align-content: flex-start;
        }

        .ddr-page-widget {
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .ddr-widget {
            background-color: transparent;
            background-image: none;
            top: auto;
            bottom: auto;
            left: auto;
            right: auto;
            height: auto;
            width: auto;
            max-height: none;
            max-width: none;
            min-height: 0px;
            min-width: 0px;
            padding: 0px;
            margin: 0px;
            overflow: hidden;
        }

        .ddr-widget__button {
            position: relative;
            display: flex;
        }

        .ddr-widget__button a {
            overflow: hidden;
            box-sizing: border-box;
        }

        .ddr-widget__button>a>div {
            display: block;
            overflow: hidden;
            background-color: rgb(0, 143, 162);
            box-sizing: content-box;
            word-break: break-all;
        }

        .ddr-widget__button a,
        .ddr-widget__button a:active,
        .ddr-widget__button a:focus,
        .ddr-widget__button a:hover,
        .ddr-widget__button a:link,
        .ddr-widget__button a:visited {
            display: inline-block;
            vertical-align: middle;
            color: inherit;
            text-decoration: none;
            border: none;
        }
    </style>
    <div class="ddr-page-wrapper">
        <div class="ddr-page">
            <div class="ddr-page-row ddr-page-row-0 ">
                <div class="ddr-page-column ddr-page-column-0 " style="width: 100%;">
                    <div id="ddr-page-widget-000" class="ddr-page-widget ddr-page-widget-0 ">
                        <div class="ddr-widget">
                            <style>
                                #button-000 {
                                    background-color: rgba(226, 118, 37, 1);
                                    color: rgba(250, 250, 250, 1);
                                }

                                #button-000:hover {
                                    transition: background-color 0.5s ease;
                                    background-color: rgba(239, 56, 27, 1);
                                    border-color: rgba(239, 56, 27, 1);
                                }
                            </style>
                            <div class="ddr-widget__button" style="justify-content: flex-start;">
                                <a target="<?php echo $target; ?>" style="margin: 15px 0; border-radius: 3px;" href="<?php echo $action; ?>"<?php echo $onClick;?>>
                                    <div id="button-000" style="padding: 10px 15px; font-size: 18px;">Edit with DragDropr</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($entityIdInput):?>
        <?php echo $entityIdInput;?>
    <?php endif;?>
    <?php

}


/**
 * Add edit link in outside edit post.
 *
 * @access public
 * @since 1.0.0
 * @param $actions
 * @param $post
 *
 * @return array
 */
function ddr_add_edit_post_in_dashboard( $actions, $post ) {

    $wpUser = wp_get_current_user();

    if ( empty($wpUser->ID) ) {
        return;
    }
    $authorized = get_option('dragdropr_authorized');
    if ($authorized) {
        $data = [
            'entityType' => 'post',
            'urlParameters' => [
                'identifier' => $post->ID,
                'skipReload' => true
            ]
        ];
        $onClick = " onclick='window.DragDropr.Plugins.factory(document.createElement(\"textarea\")).execute(" . json_encode($data). ");return false;'";
        $action = sprintf(
            '<a target="_blank" href="%s"%s>%s</a>',
            dragdropr_apply_jwt_token(DDR_WP_REST_OAuth1_Client::get_app_url() . 'wordpress/posts/' . $post->ID . '?domain=' . DDR_WP_REST_OAuth1_Client::get_site_url()),
            $onClick,
            __('Edit with Dragdropr', 'dragdropr')
        );
    } else {
        $action = sprintf(
            '<a href="%s">%s</a>',
            DDR_WP_REST_OAuth1_Client::get_site_url().'/wp-admin/admin.php?page=dragdropr',
            __( 'Edit with Dragdropr', 'dragdropr' )
        );
    }

    $actions['edit_with_dragdropr$post->ID '] = $action;
    return $actions;
}

/**
 * Get edit link for given post type
 *
 * @param $postType
 * @param $entityId
 * @return null|string
 */
function get_dragdropr_entity_edit_url($postType, $entityId = ':identifier')
{
    $post_type_object = get_post_type_object($postType);

    if ($post_type_object) {
        $action = '&action=edit';
        return admin_url(str_replace('=%d', '=' . $entityId,$post_type_object->_edit_link . $action));
    }

    return null;
}

/**
 * Get data source config
 *
 * @return array
 */
function get_dragdropr_configuration()
{
    $wpUser = wp_get_current_user();
    $authorized = false;

    if (! empty($wpUser->ID) ) {
        $authorized = get_option('dragdropr_authorized');
    }

    return array(
        'integration' => array(
            'active' => (boolean) $authorized,
            'url' => get_admin_url(null, 'admin.php?page=dragdropr')
        ),
        'storeUrl' => str_replace(array('index.php/', 'index.php'), '', DDR_WP_REST_OAuth1_Client::get_site_url()),
        'apiKey' => '',
        'post' => array(
            'endpoint' => dragdropr_apply_jwt_token(
                dragdropr_escape_url_protocol(
                    DDR_WP_REST_OAuth1_Client::get_app_url() . 'wordpress/posts(/:identifier)?domain=(:storeUrl)'
                )
            ),
            'new_entity' => dragdropr_escape_url_protocol(
                get_dragdropr_entity_edit_url('post', ':pageId')
            )
        ),
        'page' => array(
            'endpoint' => dragdropr_apply_jwt_token(
                dragdropr_escape_url_protocol(
                    DDR_WP_REST_OAuth1_Client::get_app_url() . 'wordpress/pages(/:identifier)?domain=(:storeUrl)'
                )
            ),
            'new_entity' => dragdropr_escape_url_protocol(
                get_dragdropr_entity_edit_url('page', ':pageId')
            )
        ),
        'default' => array(
            'endpoint' => dragdropr_apply_jwt_token(
                dragdropr_escape_url_protocol(
                DDR_WP_REST_OAuth1_Client::get_app_url() . 'wordpress/editor/pages(/:identifier)?domain=(:storeUrl)'
                )
            )
        ),
        'type' => 'WORDPRESS_INTEGRATION'
    );
}

/**
 * Escapes url protocol so the given url can be correctly used in js with url pattern
 *
 * @param string $url
 * @return string
 */
function dragdropr_escape_url_protocol($url)
{
    return str_replace('https://', 'https\://', str_replace('http://', 'http\://', $url));
}

// Add dragdropr to tinymce editor
add_action('admin_init', 'add_dragdropr_tinymce');

// Add necessary/dependable dragdropr script
add_action('admin_enqueue_scripts', 'add_dragdropr_scripts');

// Add dragdropr to quicktags
add_action('admin_print_footer_scripts', 'add_dragdropr_quicktags');

// Add dragdropr configuration settings
add_action( 'admin_enqueue_scripts', 'add_dragdropr_configuration' );

// Register dragdropr settings
add_action( 'admin_init', 'register_dragdropr_settings',  10 );

/**
 * Add main dragdropr script to the page head
 */
function add_dragdropr_scripts()
{
    wp_enqueue_script(
            'dragdropr',
        plugins_url( '/admin/js/dragdropr.js', __FILE__ ),
            array(),
            '1.0.1',
            false
    );
}

/**
 * Add dragdropr tinymce plugin to editor
 */
function add_dragdropr_tinymce() {
//    add_filter('teeny_mce_buttons', 'add_dragdropr_teeny_mce_buttons');
    add_filter('mce_buttons', 'add_dragdropr_tinymce_buttons');
    add_filter('mce_external_plugins', 'add_dragdropr_tinymce_plugin');
}

/**
 * @deprecated
 */
function add_dragdropr_teeny_mce_buttons($buttons)
{
    array_push($buttons, 'dragdropr');
}

/**
 * Add dragdropr tinymce plugin buttons
 *
 * @param $buttons
 * @return array
 */
function add_dragdropr_tinymce_buttons(array $buttons) {
    array_push($buttons, 'dragdropr');
    return $buttons;
}

/**
 * Add dragdropr tinymce plugin scripts
 *
 * @param array $plugin_array
 * @return array
 */
function add_dragdropr_tinymce_plugin(array $plugin_array) {
    $plugin_array['dragdropr'] = plugins_url( '/admin/js/wysiwyg/tiny_mce/plugins/dragdropr/dragdropr.js', __FILE__ );
    return $plugin_array;
}

/**
 * Add dragdropr to quick tags
 */
function add_dragdropr_quicktags()
{
    if (wp_script_is('quicktags')) {
        echo "
            <script type=\"text/javascript\">
                if (QTags && QTags.addButton) {
                    var openDragDropr = function(button, textArea) {
                        if (textArea && textArea.id) {
                            window.DragDropr.Plugins.factory(document.getElementById(textArea.id)).execute();
                        }
                    };
                    QTags.addButton(
                        'dragdropr',
                        'DragDropr',
                        openDragDropr,
                        null,
                        'dragdropr',
                        'Open DragDropr',
                        201
                    );
                }
            </script>
        ";
    }
}

/**
 * Initialize dragdropr config with data source config
 */
function add_dragdropr_configuration()
{
    wp_add_inline_script(
        'dragdropr',
        '//<![CDATA[
            (function () {
                if (window.DragDropr && window.DragDropr.Plugins) {
                    // Register plugin workers in current context that attach it to existing editors
                    window.DragDropr.Plugins.registerWorkers([
                        window.DragDropr.Plugins.FroalaPlugin,
                        window.DragDropr.Plugins.RedactorPlugin,
                        window.DragDropr.Plugins.TinyMCEPlugin
                    ]);
                }

                var dataSource = ' . json_encode(get_dragdropr_configuration()) . ',
                    setDataSource = function () {
                        if (window.DragDropr && window.DragDropr.getConfig) {
                            window.DragDropr.getConfig().write(\'integration\', dataSource);
                            document.removeEventListener(\'DragDropr.Config\', setDataSource);
                        }
                    };
                document.addEventListener(\'DragDropr.Config\', setDataSource);
                setDataSource();
            })();
//]]>',
        'after'
    );
}

/**
 * Apply jwt token to given url
 *
 * @param string $url
 * @return string
 *
 * @since 1.0.4
 */
function dragdropr_apply_jwt_token($url)
{
    $jwtToken = get_option('dragdropr_jwt_token');

    if ($jwtToken) {
        if (parse_url($url, PHP_URL_QUERY)) {
            $url .= '&jwt_token=' . $jwtToken;
        } else {
            $url .= 'jwt_token=' . $jwtToken;
        }
    }

    return $url;
}

/**
 * Register default dragdropr settings in WordPress.
 *
 * @since 1.0.4
 */
function register_dragdropr_settings()
{
//    add_options_page('DragDropr', 'DragDropr', 'manage_options', 'dragdropr');
    add_settings_section(
        'dragdropr',
        'DragDropr Settings',
        null,
        'dragdropr'
    );
    add_settings_field(
        'dragdropr_jwt_token',
        'JWT Token',
        function() {
            echo '<input name="dragdropr_jwt_token" id="dragdropr_jwt_token" type="text" value=" ' . get_option( 'dragdropr_jwt_token' ). '" class="regular-text"/>';
        },
        'dragdropr',
        'dragdropr'
    );
    register_setting(
        'dragdropr',
        'dragdropr_jwt_token',
        array(
            'type' => 'string',
            'description'  => __( 'JWT token.' )
        )
    );
}
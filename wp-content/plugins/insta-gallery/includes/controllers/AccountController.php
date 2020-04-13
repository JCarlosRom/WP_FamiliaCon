<?php

include_once(QLIGG_PLUGIN_DIR . 'includes/models/Token.php');
include_once(QLIGG_PLUGIN_DIR . 'includes/controllers/QLIGG_Controller.php');

class QLIGG_Account_Controller extends QLIGG_Controller {

  protected static $instance;
  protected static $slug = QLIGG_DOMAIN . '_token';

  public static function instance() {
    if (!isset(self::$instance)) {
      self::$instance = new self();
      self::$instance->init();
    }
    return self::$instance;
  }

  function init() {
    add_action('wp_ajax_qligg_add_token', array($this, 'ajax_add_token'));
    add_action('wp_ajax_qligg_delete_token', array($this, 'ajax_delete_token'));
    add_action('admin_enqueue_scripts', array($this, 'add_js'));
    add_action('admin_menu', array($this, 'add_menu'));
  }

  function add_menu() {
    add_submenu_page(QLIGG_DOMAIN, esc_html__('Account', 'insta-gallery'), esc_html__('Account', 'insta-gallery'), 'manage_options', self::$slug, array($this, 'add_panel'));
  }

  function add_panel() {
    global $submenu, $qligg_api;
    $token_model = new QLIGG_Token();
    $tokens = $token_model->get_tokens();

    include (QLIGG_PLUGIN_DIR . '/includes/view/backend/pages/parts/header.php');
    include (QLIGG_PLUGIN_DIR . '/includes/view/backend/pages/accounts.php');
  }

  function ajax_add_token() {
    
    global $qligg_api;

    if (!empty($_REQUEST) && current_user_can('manage_options') && check_ajax_referer('qligg_add_token', 'nonce', false)) {

      if (empty($_REQUEST['access_token'])) {
        parent::error_ajax(esc_html__('Empty access token', 'insta-gallery'));
      }

      $access_token = sanitize_text_field($_REQUEST['access_token']);
      $token_model = new QLIGG_Token();
      $tokens = $token_model->get_tokens();

      if (count($access_token_id = explode('.', $access_token)) == 1) {
        parent::error_ajax(esc_html__('Invalid access token', 'insta-gallery'));
      }

      if (!$qligg_api->validate_token($access_token)) {
        parent::error_ajax($qligg_api->get_message());
      }

      if (isset($tokens[$access_token_id[0]]) && $tokens[$access_token_id[0]] == $access_token) {
        if ($profile_info = qligg_get_user_profile($access_token_id[0])) {
          parent::error_ajax(sprintf(esc_html__('The %s account is already connected. To connect a new account logout from Instagram in this browser.', 'insta-gallery'), @$profile_info['user']));
        }        
        parent::error_ajax(esc_html__('Account already connected. To connect a new account logout from Instagram in this browser.', 'insta-gallery'));
      }

      $new_token = array($access_token_id[0] => $access_token);

      $token_model->add_token($new_token);

      parent::success_ajax(esc_html__('Access token created', 'insta-gallery'));
    }

    parent::error_access_denied();
  }

  function ajax_delete_token() {

    if (!empty($_REQUEST['token_id']) && current_user_can('manage_options') && check_ajax_referer('qligg_delete_token', 'nonce', false)) {

      $token_model = new QLIGG_Token();

      $token_id = sanitize_text_field($_REQUEST['token_id']);

      $token_model->delete_token($token_id);

      parent::success_ajax(esc_html__('Token removed successfully', 'insta-gallery'));
    }

    parent::error_access_denied();
  }

  function add_js() {

    wp_register_script('qligg-admin-account', plugins_url('/assets/backend/js/qligg-admin-account' . QLIGG::is_min() . '.js', QLIGG_PLUGIN_FILE), array('wp-util', 'jquery', 'backbone', 'jquery-serializejson'), QLIGG_PLUGIN_VERSION, true);
    wp_localize_script('qligg-admin-account', 'qligg_account', array(
        'nonce' => array(
            'qligg_add_token' => wp_create_nonce('qligg_add_token'),
            'qligg_delete_token' => wp_create_nonce('qligg_delete_token'),
        ),
        'message' => array(
            'confirm_delete' => __('Do you want to delete the token?', 'insta-gallery')
        )
    ));

    if (isset($_GET['page']) && ($_GET['page'] === self::$slug)) {
      wp_enqueue_script('qligg-admin-account');
    }
  }

}

QLIGG_Account_Controller ::instance();

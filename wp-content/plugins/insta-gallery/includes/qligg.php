<?php

class QLIGG {

  protected static $instance;

  public static function instance() {
    if (!isset(self::$instance)) {
      self::$instance = new self();
      self::$instance->api();
      self::$instance->init();
      self::$instance->includes();
    }
    return self::$instance;
  }

  function init() {
    add_action('widgets_init', array($this, 'register_widget'));
    do_action('qligg_init');
    load_plugin_textdomain('insta-gallery', false, QLIGG_PLUGIN_DIR . '/languages/');
  }

  function includes() {
    include_once(QLIGG_PLUGIN_DIR . 'includes/notices.php');
    include_once(QLIGG_PLUGIN_DIR . 'includes/helpers.php');
    include_once(QLIGG_PLUGIN_DIR . 'includes/widget.php');
    include_once(QLIGG_PLUGIN_DIR . 'includes/compatibility.php');
    include_once(QLIGG_PLUGIN_DIR . 'includes/backend.php');
    include_once(QLIGG_PLUGIN_DIR . 'includes/frontend.php');
  }

  function register_widget() {
    if (class_exists('QLIGG_Widget')) {
      register_widget('QLIGG_Widget');
    }
  }

  function api() {

    global $qligg_api;

    if (!class_exists('QLIGG_API')) {

      include_once(QLIGG_PLUGIN_DIR . 'includes/api.php');

      $qligg_api = new QLIGG_API();
    }
  }

  public static function do_activation() {
    set_transient('qligg-first-rating', true, MONTH_IN_SECONDS);
  }

  public static function is_min() {
    if (!QLIGG_DEVELOPER && (!defined('SCRIPT_DEBUG') || !SCRIPT_DEBUG)) {
      return '.min';
    }
  }

}

QLIGG::instance();


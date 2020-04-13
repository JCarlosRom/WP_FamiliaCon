<?php

class QLIGG_Model {

  private $cache = array();
  protected $table = null;

  function sanitize_value(&$value, $key, $args) {

    if (isset($args[$key])) {

      $type = $args[$key];

      if (is_null($type) && !is_numeric($value)) {
        $value = intval($value);
      } elseif (is_bool($type) && !is_bool($value)) {
        $value = ($value === 'true' || $value === '1' || $value === 1);
      } elseif (is_string($type) && !is_string($value)) {
        $value = strval($value);
      } elseif (is_array($type) && !is_array($value)) {
        $value = (array) $type;
      } elseif (is_array($type) && count($value)) {
        array_walk($value, array($this, 'sanitize_value'), $type);
      }
    }

    return $value;
  }

  function sanitize_data($value_data) {

    $args = $this->get_args();

    if (!count($args)) {
      return $value_data;
    }

    $value_data = array_replace_recursive($args, $value_data);

    if (!count($value_data)) {
      return $value_data;
    }

    array_walk($value_data, array($this, 'sanitize_value'), $args);
    
    return $value_data;
  }

  function save_all($data = null) {

    if (!$this->table) {
      error_log('Model can\'t be accesed directly');
      die();
    }

    return update_option($this->table, $data);
  }

  function get_all() {

    if (!$this->table) {
      error_log('Model can\'t be accesed directly');
      die();
    }

    if (!isset($this->cache[$this->table])) {
//      $this->cache[$this->table] = $this->sanitize_data(get_option($this->table, array()));
      $this->cache[$this->table] = get_option($this->table, $this->get_args());
    }

    return $this->cache[$this->table];
  }

}

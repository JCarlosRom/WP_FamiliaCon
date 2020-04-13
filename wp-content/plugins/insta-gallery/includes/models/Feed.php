<?php

include_once 'QLIGG_Model.php';

class QLIGG_Feed extends QLIGG_Model {

  protected $table = 'insta_gallery_feeds';

  function get_args() {
    return array(
        1 => array(
            'id' => 1,
            'order' => 1,
//            'profile' => array(
//                'id' => '',
//                'user' => '',
//                'name' => '',
//                'picture' => '',
//                'link' => '',
//            ),
            'type' => 'username',
            'username' => '3617511663',
            'tag' => '',
            'layout' => 'gallery',
            'limit' => 12,
            'columns' => 3,
            'resolution' => 'standard',
            'spacing' => 10,
            'highlight' => array(
                'tag' => '',
                'id' => '',
                'position' => ''
            ),
            'box' => array(
                'display' => false,
                'padding' => 1,
                'radius' => 0,
                'background' => '#fefefe',
                'profile' => false,
                'desc' => '',
            ),
            'mask' => array(
                'display' => true,
                'background' => '#000000',
                'likes' => true,
                'comments' => true,
            ),
            'card' => array(
                'display' => false,
                'radius' => '1',
                'font_size' => '12',
                'background' => '#ffffff',
                'padding' => '5',
                'info' => true,
                'length' => 10,
                'caption' => true,
            ),
            'carousel' => array(
                'slidespv' => 5,
                'autoplay' => false,
                'autoplay_interval' => 3000,
                'navarrows' => true,
                'navarrows_color' => '',
                'pagination' => true,
                'pagination_color' => ''
            ),
            'popup' => array(
                'display' => true,
                'profile' => false,
                'caption' => false,
                'likes' => false,
                'align' => 'bottom',
            ),
            'button' => array(
                'display' => true,
                'text' => 'Ver en Instagram',
                'background' => '',
                'background_hover' => '',
            ),
            'button_load' => array(
                'display' => false,
                'text' => 'Ver más...',
                'background' => '',
                'background_hover' => '',
            ),
        )
    );
  }

  function get_next_id() {
    $feeds = $this->get_feeds();
    if (count($feeds)) {
      return max(array_keys($feeds)) + 1;
    }
    return 0;
  }

  function get_feed($id) {

    $feeds = $this->get_feeds();

    if (isset($feeds[$id])) {
      return $feeds[$id];
    }
  }

  function get_feeds() {

    $feeds = $this->get_all();
    //make sure each feed has all values
    if (count($feeds)) {
      foreach ($feeds as $id => $feed) {
        $feeds[$id] = array_replace_recursive($this->get_args()[1], $feeds[$id]);
      }
    }
    return $feeds;
  }

  function update_feed($feed_data) {
    return $this->save_feed($feed_data);
  }

  function update_feeds($feeds, $order = 0) {
    return $this->save_all($feeds);
  }

  // create a new feed, get the next id
  function add_feed($feed_data) {
    $feed_id = $this->get_next_id();
    $feed_data['id'] = $feed_id;
    $feed_data['order'] = $feed_id + 1;
    $feed_data['tag'] = qligg_sanitize_instagram_feed($feed_data['tag']);
    $feed_data['username'] = qligg_sanitize_instagram_feed($feed_data['username']);
    return $this->save_feed($feed_data);
  }

  function save_feed($feed_data = null) {
    $feeds = $this->get_feeds();    
    $feeds[$feed_data['id']] = array_replace_recursive($this->get_args()[1], $feed_data);
    return $this->save_all($feeds);
  }

  function delete_feed($id = null) {
    $feeds = $this->get_all();
    if ($feeds) {
      if (count($feeds) > 0) {
        unset($feeds[$id]);
        return $this->save_all($feeds);
      }
    }
  }

}

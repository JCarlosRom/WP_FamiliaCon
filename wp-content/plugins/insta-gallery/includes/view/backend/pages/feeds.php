<div class="wrap about-wrap full-width-layout">
  <form method="post">
    <p class="submit"> 
      <?php submit_button(esc_html__('+ Feed', 'btn-instagram'), 'primary', 'submit', false, array('id' => 'qligg-add-feed')); ?>
    <!--<span class="settings-save-status qligg-premium-field">
      <?php submit_button(esc_html__('Save reorder', 'insta-gallery'), 'secondary', 'submit', false, array('id' => 'qligg_feeds_order', 'disabled' => 'disabled')); ?>
    <span class="spinner"></span>
    <span class="saved"><?php esc_html_e('Saved successfully!'); ?></span>
    </span>-->
    </p>
    <table id="qligg_feeds_table" class="form-table widefat striped">
      <thead>
        <tr>
          <th><?php esc_html_e('Image', 'insta-gallery'); ?></th> 
          <th><?php esc_html_e('Feed', 'insta-gallery'); ?></th>
          <th><?php esc_html_e('ID', 'insta-gallery'); ?></th>
          <th><?php esc_html_e('Layout', 'insta-gallery'); ?></th>
          <th><?php esc_html_e('Token', 'insta-gallery'); ?></th>
          <th><?php esc_html_e('Action', 'insta-gallery'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        $position = 1;
        
//        var_dump($feeds);
        
        foreach ($feeds as $id => $feed) {

          if (!isset($feed['type']))
            continue;
          if ($feed['type'] == 'username') {
            $profile_info = qligg_get_user_profile($feed['username']);
          } else {
            $profile_info = qligg_get_tag_profile($feed['tag']);
          }
          ?>
          <tr class="<?php if ($position > 1)  ?>" data-feed_id="<?php echo esc_attr($id) ?>" data-feed_position="<?php echo esc_attr($position) ?>"> 
            <td width="1%">
              <img class="qligg-avatar" src="<?php echo esc_url($profile_info['picture']); ?>" />
            </td>
            <td width="1%">
              <?php echo esc_html($profile_info['user']); ?>
            </td>
            <td width="1%">
              <?php echo esc_html($profile_info['id']); ?>
            </td>
            <td>
              <?php echo esc_html(ucfirst($feed['layout'])); ?>
            </td>
            <td>
              <input id="<?php echo esc_attr($id); ?>-feed-shortcode" type="text" value='[insta-gallery id="<?php echo esc_attr($id); ?>"]' readonly />
              <a href="javascript:;" data-qligg-copy-feed-shortcode="#<?php echo esc_attr($id); ?>-feed-shortcode" class="button button-secondary">
                <i class="dashicons dashicons-edit"></i><?php esc_html_e('Copy', 'insta-gallery'); ?>
              </a>
            </td>
            <td> 
              <a href="javascript:;" class="qligg_edit_feed button button-primary" title="<?php esc_html_e('Edit feed', 'insta-gallery'); ?>"><?php esc_html_e('Edit'); ?></a>
              <a href="javascript:;" class="qligg_clear_cache button button-secondary" title="<?php esc_html_e('Clear feed cache', 'insta-gallery'); ?>"><i class="dashicons dashicons dashicons-update"></i><?php esc_html_e('Cache', 'insta-gallery'); ?></a>
              <a href="javascript:;" class="qligg_delete_feed" title="<?php esc_html_e('Delete feed', 'insta-gallery'); ?>"><?php esc_html_e('Delete'); ?></a> 
              <span class="spinner"></span>
            </td>
          </tr>
          <?php
          $position++;
        } unset($i);
        ?>
      </tbody>
    </table>
  </form>
</div>

<?php include_once('modals/template-scripts-feed.php'); ?> 
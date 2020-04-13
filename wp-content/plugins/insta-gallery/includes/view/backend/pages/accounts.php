<div class="wrap about-wrap full-width-layout">
  <p class="<?php
  if (is_array($tokens) && count($tokens)) {
    echo 'qligg-premium-field';
  }
  ?>">
    <a id="qligg-generate-token" target="_self" href="<?php echo esc_url($qligg_api->get_create_account_link()); ?>" title="<?php esc_html_e('Add New Account', 'insta-gallery'); ?>">
      <?php esc_html_e('Add New Account', 'insta-gallery'); ?>
    </a>
    <span style="float: none; margin-top: 0;" class="spinner"></span>
    <a id="qligg-add-token" href="javascript:;"><?php esc_html_e('Button not working?', 'insta-gallery'); ?></a>
    <span class="description hidden"><small><?php esc_html_e('(This is a premium feature).', 'insta-gallery'); ?></small></span>
  </p>

  <?php if (is_array($tokens) && count($tokens)) : ?>
    <table id="qligg_account_table" class="form-table widefat striped">
      <thead>
        <tr>
          <th><?php esc_html_e('Image', 'insta-gallery'); ?></th>
          <th><?php esc_html_e('User', 'insta-gallery'); ?></th>
          <th><?php esc_html_e('ID', 'insta-gallery'); ?></th>
          <th><?php esc_html_e('Name', 'insta-gallery'); ?></th>
          <th><?php esc_html_e('Token', 'insta-gallery'); ?></th>
          <th><?php esc_html_e('Action', 'insta-gallery'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($tokens as $username => $access_token) {
          $profile_info = qligg_get_user_profile($username);
          ?>
          <tr data-token_id="<?php echo esc_attr($username) ?>">
            <td width="1%">
              <img class="qligg-avatar" src="<?php echo esc_url($profile_info['picture']); ?>" />
            </td>
            <td>
              <?php echo esc_html($profile_info['user']); ?>
            </td>
            <td>
              <?php echo esc_html($profile_info['id']); ?>
            </td>
            <td>
              <?php echo esc_html($profile_info['name']); ?>
            </td>
            <td>
              <input type="hidden" name="token_id" value="<?php echo esc_attr($username); ?>"> 
              <input id="<?php echo esc_attr($username); ?>-access-token" type="text" value="<?php echo esc_attr($access_token); ?>" readonly />
            </td>
            <td>
              <a href="javascript:;" data-qligg-copy-token="#<?php echo esc_attr($username); ?>-access-token" class="button button-primary">
                <i class="dashicons dashicons-edit"></i><?php esc_html_e('Copy', 'insta-gallery'); ?>
              </a>
              <a href="javascript:;" data-qligg-delete-token="<?php echo esc_attr($username); ?>" class="button button-secondary">
                <i class="dashicons dashicons-trash"></i><?php esc_html_e('Delete', 'insta-gallery'); ?>
              </a>
              <span class="spinner"></span>
            </td>
          </tr>
          <?php
        }
        ?>
      </tbody>
    </table> 
  <?php endif; ?>
</div>

<?php include_once('modals/template-scripts-account.php'); ?> 
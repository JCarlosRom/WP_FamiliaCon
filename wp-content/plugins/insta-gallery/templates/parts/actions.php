<?php if (!empty($feed['button']['display'])) : ?>
  <div class="insta-gallery-actions">
    <a href="<?php echo esc_url($profile_info['link']); ?>" target="blank" class="insta-gallery-button follow"><i class="qligg-icon-instagram-o"></i><?php echo esc_html($feed['button']['text']); ?></a>
  </div>
<?php endif; ?>
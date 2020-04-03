<!-- Post Comments -->
<div class="otw_post_content-blog-comment">
  <?php if( !$this->view_data['settings']['otw_pct_meta_icons'] ) : ?>
  <span class="head"><?php _e('Comments:', 'otw_pctl');?></span>
  <?php else: ?>
  <span class="head"><i class="icon-comments"></i></span>
  <?php endif; ?>
  <a href="<?php echo get_comments_link($post->ID);?>" title="<?php _e('Comment on ', 'otw_pctl'); echo $post->post_title;?>"><?php echo $post->comment_count;?></a>
</div>
<!-- END Post Comments -->
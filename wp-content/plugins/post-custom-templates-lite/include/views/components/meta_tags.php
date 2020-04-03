<?php 
  $tagsArray = wp_get_post_tags( $post->ID );
  if( is_array( $tagsArray ) && !empty($tagsArray[0]) ) :
?>
<!-- Post Tags -->
<div class="otw_post_content-blog-tag">
  
  <?php if( !$this->view_data['settings']['otw_pct_meta_icons'] ) : ?>
  <span class="head"><?php _e('Tags:', 'otw_pctl');?></span>
  <?php else: ?>
  <span class="head"><i class="icon-tags"></i></span>
  <?php endif; ?>

  <?php
    foreach( $tagsArray as $index => $tag ):

      $tagUrl = get_tag_link( $tag->term_id );
  ?>
  <a href="<?php echo $tagUrl;?>" rel="tag"><?php echo $tag->name;?></a> 
  <?php if( $index < count( $tagsArray ) - 1 ) { echo ', '; }?>
  <?php
    endforeach;
  ?>
</div>
<!-- END Post Tags -->
<?php endif; ?>
<?php
	$current_count = get_post_meta( $post->ID, 'otw_cpp', true );
	
	if( !$current_count ){
		$current_count = 0;
	}
?>
<div class="otw_post_content-blog-post-meta-item">
	<?php if( !$this->view_data['settings']['otw_pct_meta_icons'] ) : ?>
		<span class="head"><?php _e('Visits:', 'otw_pctl');?></span>
	<?php else: ?>
		<span class="head"><i class="icon-eye-open"></i></span>
	<?php endif; ?>
	<span><?php echo $current_count;?></span>
</div>
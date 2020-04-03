<!-- Social Share Buttons -->
<?php 
  ( !empty( $post->post_excerpt ) )? $postContentFull = $post->post_excerpt : $postContentFull = $post->post_content;

  $socialLink         = get_permalink($post->ID);
  $socialTitle        = $post->post_title;
  $socialDescription  = $this->excerptLength( wp_strip_all_tags( $postContentFull ), $this->view_data['settings']['excerpt_length'] );
  $socialAsset        = $this->getPostAsset( $post );

  $class = '';
  if( $this->view_data['settings']['otw_pct_show_social_icons'] == 'share_btn_small' ) {
    $class = 'small-style';
  }
  $postMetaData = $posts;
?>
<?php if( !is_array( $postMetaData ) && ( $postMetaData == 'single' ) ){?>
	<h3 class="otw_post_content-mb25">
		<?php if( !empty( $this->view_data['settings']['otw_pct_social_title_text'] ) ){?>
			<?php echo $this->view_data['settings']['otw_pct_social_title_text']?>
		<?php }else{ ?>
			<?php _e( 'Hey, like this? Why not share it with a buddy?', 'otw_pctl' )?>
		<?php }?>
	</h3>
<?php }?>
<div 
  class="otw_post_content-social-share-buttons-wrapper otw_post_content-social-wrapper <?php echo $class;?> pm_clearfix" 
  data-title="<?php echo $socialTitle;?>"
  data-description="<?php echo $socialDescription;?>"
  data-image="<?php echo $socialAsset; ?>"
  data-url="<?php echo $socialLink;?>">

<?php if( $this->view_data['settings']['otw_pct_show_social_icons'] == 'share_icons' ) : ?>
	<?php if( !empty( $this->view_data['settings']['otw_pct_show_social_icons_facebook'] ) ){?>
		<a class="otw_post_content-social-item otw-facebook" target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo $socialLink;?>"><i class="icon-facebook" href=""></i></a>
	<?php }?>
	<?php if( !empty( $this->view_data['settings']['otw_pct_show_social_icons_twitter'] ) ){?>
		<a class="otw_post_content-social-item otw-twitter" target="_blank" href="https://twitter.com/intent/tweet?source=tweetbutton&amp;text=<?php echo $socialTitle;?>&amp;url=<?php echo $socialLink;?>"><i class="icon-twitter" href=""></i></a>
	<?php }?>
	<?php if( !empty( $this->view_data['settings']['otw_pct_show_social_icons_googleplus'] ) ){?>
		<a class="otw_post_content-social-item otw-google_plus" target="_blank" href="https://plus.google.com/share?url=<?php echo $socialLink;?>"><i class="icon-google-plus" href=""></i></a>
	<?php }?>
	<?php if( !empty( $this->view_data['settings']['otw_pct_show_social_icons_linkedin'] ) ){?>
		<a class="otw_post_content-social-item otw-linkedin" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $socialLink;?>&amp;title=<?php echo $socialTitle;?>&amp;summary=<?php echo $socialDescription;?>"><i class="icon-linkedin" href=""></i></a>
	<?php }?>
	<?php if( !empty( $this->view_data['settings']['otw_pct_show_social_icons_pinterest'] ) ){?>
		<a class="otw_post_content-social-item otw-pinterest" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php echo $socialLink;?>&amp;media=<?php echo $socialAsset; ?>&amp;description=<?php echo $socialDescription;?>&amp;title=<?php echo $socialTitle;?>"><i class="icon-pinterest" href=""></i></a>
	<?php }?>
<?php endif;?>
<?php if( $this->view_data['settings']['otw_pct_show_social_icons'] == 'share_btn_small' || $this->view_data['settings']['otw_pct_show_social_icons'] == 'share_btn_large') : ?>
	<?php if( !empty( $this->view_data['settings']['otw_pct_show_social_icons_facebook'] ) ){?>
		<div class="otw_post_content-share-button-boxy">
			<a class="otw_post_content-social-share otw-facebook" target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo $socialLink;?>"><i class="icon-facebook-sign"></i></a>
		</div>
	<?php }?>
	<?php if( !empty( $this->view_data['settings']['otw_pct_show_social_icons_twitter'] ) ){?>
		<div class="otw_post_content-share-button-boxy otw-twitter">
			<a class="otw_post_content-social-share otw-twitter" target="_blank" href="https://twitter.com/intent/tweet?source=tweetbutton&amp;text=<?php echo $socialTitle;?>&amp;url=<?php echo $socialLink;?>"><i class="icon-twitter-sign"></i></a>
		</div>
	<?php }?>
	<?php if( !empty( $this->view_data['settings']['otw_pct_show_social_icons_googleplus'] ) ){?>
		<div class="otw_post_content-share-button-boxy">
			<a class="otw_post_content-social-share otw-google_plus" target="_blank" href="https://plus.google.com/share?url=<?php echo $socialLink;?>"><i class="icon-google-plus-sign"></i></a>
		</div>
	<?php }?>
	<?php if( !empty( $this->view_data['settings']['otw_pct_show_social_icons_linkedin'] ) ){?>
		<div class="otw_post_content-share-button-boxy">
			<a class="otw_post_content-social-share otw-linkedin" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $socialLink;?>&amp;title=<?php echo urlencode( $socialTitle );?>&amp;summary=<?php echo urlencode( $socialDescription );?>"><i class="icon-linkedin-sign"></i></a>
		</div>
	<?php }?>
	<?php if( !empty( $this->view_data['settings']['otw_pct_show_social_icons_pinterest'] ) ){?>
		<div class="otw_post_content-share-button-boxy">
			<a class="otw_post_content-social-share otw-pinterest" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php echo $socialLink;?>&amp;media=<?php echo $socialAsset; ?>&amp;description=<?php echo urlencode( $socialDescription );?>&amp;title=<?php echo urlencode( $socialTitle );?>"><i class="icon-pinterest-sign"></i></a>
		</div>
	<?php }?>
<?php endif;?>
<?php if( $this->view_data['settings']['otw_pct_show_social_icons'] == 'like_buttons' ) : ?>
	<?php if( !empty( $this->view_data['settings']['otw_pct_show_social_icons_facebook'] ) ){?>
		<div class="otw_post_content-like-button-boxy">
			<iframe src="//www.facebook.com/plugins/like.php?href=<?php echo $socialLink;?>&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=426590060736305" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>
		</div>
	<?php }?>
	<?php if( !empty( $this->view_data['settings']['otw_pct_show_social_icons_twitter'] ) ){?>
		<div class="otw_post_content-like-button-boxy">
			<a href="https://twitter.com/share" class="twitter-share-button" data-text="<?php echo $socialTitle;?>" data-url="<?php echo $socialLink;?>">Tweet</a>  
		</div>
	<?php }?>
	<?php if( !empty( $this->view_data['settings']['otw_pct_show_social_icons_googleplus'] ) ){?>
		<div class="otw_post_content-like-button-boxy">
			<!-- Place this tag where you want the +1 button to render. -->
			<div class="g-plusone" data-size="medium" data-href="<?php echo $socialLink;?>"></div>
			<!-- Scrip has been moved into scripts.js - because JS reponse thru ajax will not fire the inline JS -->
			<!-- “Scripts in the resulting document tree will not be executed, resources referenced will not be 
			loaded and no associated XSLT will be applied.” -->
		</div>
	<?php }?>
<?php endif;?>
<?php if( $this->view_data['settings']['otw_pct_show_social_icons'] == 'custom_icons' ) : ?>
	<?php echo do_shortcode( $this->view_data['settings']['otw_pct_show_social_icons_custom'] );?>
<?php endif;?>
</div>
<!-- End Social Share Buttons  -->
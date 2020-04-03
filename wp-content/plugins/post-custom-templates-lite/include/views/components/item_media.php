<?php
 switch( $post['postMetaData']['media_type'] ){
	case 'slider':?>
		<?php $sliderImages = explode(',', $post['postMetaData']['slider_url']); ?>
		<div class="otw_post_content-slider otw_post_content-format-gallery" data-animation="slide">
			<ul class="slides">
				<?php foreach( $sliderImages as $sliderImage ){?>
				<?php $imagePath = parse_url($sliderImage); ?>
				<?php $attachmentMetaData = $this->getAttachmentMetaData( $sliderImage, $post );?>
					<li>
						<?php if( $post['settings']['otw_pct_media_lightbox'] == 'yes' ){?>
						<a href="<?php echo $this->otwImageResize( $imagePath['path'], $post['imageLightboxWidth'], $post['imageLightboxHeight'], $post['imageCrop'], $post['imageWhiteSpaces'], $post['imageBackground'], $post['imageLightboxFormat'] )?>" rel="otw_fslide_<?php echo $post['post']->ID?>" class="otw_post_content-fancybox-slider">
						<?php }?>
						<img src="<?php echo $this->otwImageResize( $imagePath['path'], $post['imageWidth'], $post['imageHeight'], $post['imageCrop'], $post['imageWhiteSpaces'], $post['imageBackground'], $post['imageFormat'] )?>" alt="<?php echo $attachmentMetaData['alt']?>" title="<?php echo $attachmentMetaData['title']?>" data-item="media">
						<?php if( $post['settings']['otw_pct_media_lightbox'] == 'yes' ){?>
						</a>
						<?php }?>
					</li>
				<?php }?>
			</ul>
		</div>
		<?php break;
	case 'img':?>
		<?php $imagePath = parse_url($post['postMetaData']['img_url']);?>
		<?php $attachmentMetaData = $this->getAttachmentMetaData( $post['postMetaData']['img_url'], $post );?>
			<figure class="otw_post_content-media otw_post_content-format-image">
				<?php if( $post['settings']['otw_pct_media_lightbox'] == 'yes' ){?>
				<a href="<?php echo $this->otwImageResize( $imagePath['path'], $post['imageLightboxWidth'], $post['imageLightboxHeight'], $post['imageCrop'], $post['imageWhiteSpaces'], $post['imageBackground'], $post['imageLightboxFormat'] )?>" class="otw_post_content-fancybox-img">
				<?php }?>
				<img src="<?php echo $this->otwImageResize
				( $imagePath['path'], $post['imageWidth'], $post['imageHeight'], $post['imageCrop'], $post['imageWhiteSpaces'], $post['imageBackground'], $post['imageFormat'] )?>" alt="<?php echo $attachmentMetaData['alt']?>" title="<?php echo $attachmentMetaData['title']?>" />
				<?php if( $post['settings']['otw_pct_media_lightbox'] == 'yes' ){?>
				</a>
				<?php }?>
			</figure>
		<?php break;
	case 'soundcloud':?>
			<figure class="otw_post_content-media otw_post_content-format-video">
				<?php if( $post['settings']['otw_pct_media_lightbox'] == 'yes' ){?>
				<?php $imgLink = $this->getLink( $post['post'], 'item_media' );?>
				<a href="javascript:;" rel="<?php echo $imgLink?>" class="otw_post_content_video_link otw_post_content-fancybox-movie-wrap"></a>
				<?php }?>
				<?php echo $this->otwEmbedResize( wp_oembed_get($post['postMetaData']['soundcloud_url'], array('width' => $post['imageWidth'])), $post['imageWidth'], $post['imageHeight'], $post['imageCrop'] );?>
				<?php if( $post['settings']['otw_pct_media_lightbox'] == 'yes' ){?>
				</a>
				<?php }?>
			</figure>
		<?php break;
	case 'vimeo':?>
			<figure class="otw_post_content-media otw_post_content-format-video">
				<?php if( $post['settings']['otw_pct_media_lightbox'] == 'yes' ){?>
				<?php $imgLink = $this->getLink( $post['post'], 'item_media' );?>
				<a href="javascript:;" rel="<?php echo $imgLink?>" class="otw_post_content_video_link otw_post_content-fancybox-movie-wrap"></a>
				<?php }?>
				<?php echo $this->otwEmbedResize( wp_oembed_get($post['postMetaData']['vimeo_url'], array('width' => $post['imageWidth'])), $post['imageWidth'], $post['imageHeight'], $post['imageCrop'] );?>
				<?php if( $post['settings']['otw_pct_media_lightbox'] == 'yes' ){?>
				</a>
				<?php }?>
			</figure>
		<?php break;
	case 'youtube':?>
			<figure class="otw_post_content-media otw_post_content-format-video">
				<?php if( $post['settings']['otw_pct_media_lightbox'] == 'yes' ){?>
				<?php $imgLink = $this->getLink( $post['post'], 'item_media' );?>
				<a href="javascript:;" rel="<?php echo $imgLink?>" class="otw_post_content_video_link otw_post_content-fancybox-movie-wrap"></a>
				<?php }?>
				<?php echo $this->otwEmbedResize( wp_oembed_get($post['postMetaData']['youtube_url'], array('width' => $post['imageWidth'])), $post['imageWidth'], $post['imageHeight'], $post['imageCrop'] );?>
				<?php if( $post['settings']['otw_pct_media_lightbox'] == 'yes' ){?>
				</a>
				<?php }?>
			</figure>
		<?php break;
}?>
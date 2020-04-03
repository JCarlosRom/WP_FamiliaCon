<?php
if( isset( $posts->posts ) && count( $posts->posts ) ){?>
	<?php if( !isset( $this->view_data['settings']['otw_pct_related_title_text'] ) || ( $this->view_data['settings']['otw_pct_related_title_text'] != ' ' ) ){?>
		<h3 class="otw_post_content_related_posts_header">
		<?php if( !empty( $this->view_data['settings']['otw_pct_related_title_text'] ) ){?>
			<?php echo $this->view_data['settings']['otw_pct_related_title_text']?>
		<?php }else{ ?>
			<?php _e( 'Related Posts', 'otw_pctl' );?>
		<?php }?>
		</h3>
	<?php }?>
	<div class="otw_post_content-slider otw_post_content-carousel no-control-nav" data-animation="slide" data-item-per-page="<?php echo $this->view_data['settings']['otw_pct_related_posts_number']?>" data-item-margin="20">
		<ul class="slides">
			<?php foreach( $posts->posts as $otw_pct_post ){?>
				
				<?php 
				$postMetaData = get_post_meta( $otw_pct_post->ID, 'otw_bm_meta_data', true );?>
				<?php
					$imageWidth = $this->view_data['imageRelatedWidth'];
					$imageHeight = $this->view_data['imageRelatedHeight'];
					$imageFormat = $this->view_data['imageRelatedFormat'];
					$imageCrop = $this->view_data['imageRelatedCrop'];
					$imageBigWidth = 1024;
					$imageBigHeight = 640;
					$imageBigCrop = 'center_center';
					$linkClass = '';
					
					if( !isset( $postMetaData['media_type'] ) ){
						$postMetaData['media_type'] = '';
						
						$postAttachement = wp_get_attachment_url( get_post_thumbnail_id( $otw_pct_post->ID ) );
						
						if( !$postAttachement ){
							$linkClass = ' class="otw-no-image"';
						}
					}
				?>
				<li class="otw_post_content_related_posts_holder otw_rel_post_<?php echo $otw_pct_post->ID?>">
					<div class="otw_post_content-hover-effect-4 otw_post_content_related_posts_hover">
						<figure class="otw_post_content-media otw_post_content-format-image otw_post_content-related-posts">
							
							<?php switch( $postMetaData['media_type'] ){
							
								case 'vimeo':?>
										<a href="<?php echo get_permalink( $otw_pct_post->ID );?>" class="otw_post_content_video_link"></a>
										<?php
										echo $this->otwEmbedResize( wp_oembed_get($postMetaData['vimeo_url'], array('width' => $imageWidth)), $imageWidth, $imageHeight, $imageCrop );
										$preview_image_href = $postMetaData['vimeo_url'];?>
										</a><?php
									break;
								case 'youtube':?>
										<a href="<?php echo get_permalink( $otw_pct_post->ID );?>" class="otw_post_content_video_link"></a>
										<?php
										echo $this->otwEmbedResize( wp_oembed_get($postMetaData['youtube_url'], array('width' => $imageWidth)), $imageWidth, $imageHeight, $imageCrop );
										$preview_image_href = $postMetaData['youtube_url'];?>
										</a><?php
									break;
								case 'slider':?>
										<a href="<?php echo get_permalink( $otw_pct_post->ID );?>"<?php echo $linkClass?>>
										<?php
										$sliderImages = explode(',', $postMetaData['slider_url']);
										foreach( $sliderImages as $sliderImage ){
											$imagePath = parse_url($sliderImage);?>
											<img src="<?php echo $this->otwImageResize( $imagePath['path'], $imageWidth, $imageHeight, $imageCrop, true, false, $imageFormat )?>" alt="" data-item="media">
											<?php 
											$preview_image_href = $this->otwImageResize( $imagePath['path'], $imageBigWidth, $imageBigHeight, $imageBigCrop, true, false, $imageFormat  );
											break;
										}?>
										</a><?php
									break;
								case 'soundcloud':?>
										<a href="<?php echo get_permalink( $otw_pct_post->ID );?>" class="otw_post_content_video_link"></a>
										<?php
										echo $this->otwEmbedResize( wp_oembed_get($postMetaData['soundcloud_url'], array('width' => $imageWidth ) ), $imageWidth, $imageHeight, $imageCrop );
										$preview_image_href = $postMetaData['soundcloud_url'];?>
										</a><?php
									break;
								case 'img':?>
										<a href="<?php echo get_permalink( $otw_pct_post->ID );?>"<?php echo $linkClass?>>
										<?php
										$imagePath = parse_url($postMetaData['img_url']);?>
										<img src="<?php echo $this->otwImageResize( $imagePath['path'], $imageWidth, $imageHeight, $imageCrop, true, false, $imageFormat  )?>" alt="" data-item="media" />
										<?php
										$preview_image_href = $this->otwImageResize( $imagePath['path'], $imageBigWidth, $imageBigHeight, $imageBigCrop, true, false, $imageFormat  );?>
										</a><?php
									break;
								default:?>
										<a href="<?php echo get_permalink( $otw_pct_post->ID );?>"<?php echo $linkClass?>>
										<?php
										if( $postAttachement ){
											$imagePath = parse_url( $postAttachement );?>
											<img src="<?php echo $this->otwImageResize( $imagePath['path'], $imageWidth, $imageHeight, $imageCrop, true, false, $imageFormat  )?>" alt="" data-item="media" />
											<?php
											$preview_image_href = $this->otwImageResize( $imagePath['path'], $imageBigWidth, $imageBigHeight, $imageBigCrop, true, false, $imageFormat  );
										}else{?>
										
											<img src="<?php echo $this->otwImageResize( $this->view_data['OTW_NO_IMAGE_PATH'], $imageWidth, $imageHeight, $imageCrop, true, false, $imageFormat, '/'  )?>" alt="" data-item="media" />
											<?php
											$preview_image_href = $this->otwImageResize( $this->view_data['OTW_NO_IMAGE_PATH'], $imageBigWidth, $imageBigHeight, $imageBigCrop, true, false, $imageFormat, '/'  );
										}
										?>
										</a><?php
									break;
							}?>
							
						</figure>
					</div>
				</li>
			<?php }?>
		</ul>
	</div>
<?php }?>
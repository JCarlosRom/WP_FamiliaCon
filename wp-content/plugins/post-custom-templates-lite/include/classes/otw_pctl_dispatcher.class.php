<?php
class OTW_PCTL_Dispatcher{
	
	public $views_path = '';
	
	public $view_data = array();
	
	// Width - used by templates for image resize
	public $imageWidth  = 250;
	// Height - used by templates for image resize
	public $imageHeight = 340;
	// Add white spaces to images when their size is smaller that the required thumbnail
	public $imageWhiteSpaces = true;
	//default background for white spaces of the thumbs
	public $imageBackground = '#FFFFFF';
	//the type of image croping
	public $imageCrop = 'center_center';
	// Width - used by templates for lightbox image resize
	public $lightboxImageWidth  = 1024;
	// Height - used by templates for lightbox  image resize
	public $lightboxImageHeight = 600;
	// Format - used by templates for lightbox  image resize
	public $lightboxImageFormat  = 'jpg';
	// dispatcher used for current object
	public $is_used = false;
	
	public function buildPostTemplate( $post ){
		
		$template = 'default';
		
		$this->view_data = array();
		
		$this->view_data['OTW_NO_IMAGE_PATH'] = dirname( dirname( dirname( __FILE__ ) ) ).'/images/no_image_icon.jpg';
		
		//get templates
		$otw_pct_templates = otw_get_post_templates();
		
		//get the plugin options
		$otw_pct_plugin_options = otw_pctl_get_settings();
		
		$db_otw_pct_plugin_options = get_option( 'otw_mb_plugin_options' );
		
		foreach( $otw_pct_plugin_options as $setting_key => $value ){
			
			if( is_array( $db_otw_pct_plugin_options ) && isset( $db_otw_pct_plugin_options[ $setting_key ] ) ){
			
				$otw_pct_plugin_options[ $setting_key ] = $db_otw_pct_plugin_options[ $setting_key ];
			}
		}
		
		$this->view_data['categories'] = wp_get_post_terms( $post->ID, 'category' );
		$this->view_data['tags'] = wp_get_post_terms( $post->ID, 'post_tag' );
		$this->view_data['tabs'] = get_post_meta( $post->ID, 'otw_mb_tabs_meta_data', true );
		$this->view_data['reviews'] = get_post_meta( $post->ID, 'otw_mb_reviews_meta_data', true );
		
		//resolve the template
		if( isset( $otw_pct_plugin_options['otw_pct_template'] ) && ( $otw_pct_plugin_options['otw_pct_template'] != 'default' ) && array_key_exists( $otw_pct_plugin_options['otw_pct_template'], $otw_pct_templates ) ){
			$template = $otw_pct_plugin_options['otw_pct_template'];
		}
		
		if( preg_match( "/^otw_custom_template_([0-9]+)$/", $template, $template_matches ) ){
			
			$otw_custom_templates = otw_get_post_custom_templates();
			
			if( isset( $otw_custom_templates[ $template_matches[1] ] ) ){
				$template = 'otw_custom_template';
				$this->view_data['otw_custom_template'] = $otw_custom_templates[ $template_matches[1] ];
				
				if( isset( $otw_custom_templates[ $template_matches[1] ]['options'] ) && is_array( $otw_custom_templates[ $template_matches[1] ]['options'] ) ){
					$this->view_data['settings'] = $otw_custom_templates[ $template_matches[1] ]['options'];
				}
				
			}else{
				$template = 'default';
			}
		}else{
			$template = 'default';
		}
		
		$this->view_data['imageWidth'] = '650';
		$this->view_data['imageHeight'] = '580';
		$this->view_data['imageFormat'] = '';
		$this->view_data['imageLightboxWidth'] = '1024';
		$this->view_data['imageLightboxHeight'] = '640';
		$this->view_data['imageLightboxFormat'] = '';
		$this->view_data['imageCrop'] = 'center_center';
		$this->view_data['imageWhiteSpaces'] = false;
		$this->view_data['imageBackground'] = false;
		
		
		if( isset( $this->view_data['settings']['otw_pct_item_media_width'] ) && strlen( $this->view_data['settings']['otw_pct_item_media_width'] ) ){
			$this->view_data['imageWidth'] = intval( $this->view_data['settings']['otw_pct_item_media_width'] );
		}
		if( isset( $this->view_data['settings']['otw_pct_item_media_height'] ) && strlen( $this->view_data['settings']['otw_pct_item_media_height'] ) ){
			$this->view_data['imageHeight'] = intval( $this->view_data['settings']['otw_pct_item_media_height'] );
		}
		if( isset( $this->view_data['settings']['otw_pct_item_media_format'] ) && strlen( $this->view_data['settings']['otw_pct_item_media_format'] ) ){
			$this->view_data['imageFormat'] = $this->view_data['settings']['otw_pct_item_media_format'];
		}
		if( isset( $this->view_data['settings']['otw_pct_item_media_title_attr'] ) && strlen( $this->view_data['settings']['otw_pct_item_media_title_attr'] ) ){
			$this->view_data['thumb_title_attr'] = $this->view_data['settings']['otw_pct_item_media_title_attr'];
		}
		if( isset( $this->view_data['settings']['otw_pct_item_media_alt_attr'] ) && strlen( $this->view_data['settings']['otw_pct_item_media_alt_attr'] ) ){
			$this->view_data['thumb_alt_attr'] = $this->view_data['settings']['otw_pct_item_media_alt_attr'];
		}
		
		if( ( $this->view_data['imageWidth'] == $this->view_data['imageHeight'] ) && ( $this->view_data['imageWidth'] == 0 ) ){
			$this->view_data['imageWidth'] = '650';
			$this->view_data['imageHeight'] = '580';
		}
		
		if( isset( $this->view_data['settings']['otw_pct_item_media_lightbox_width'] ) && strlen( $this->view_data['settings']['otw_pct_item_media_lightbox_width'] ) ){
			$this->view_data['imageLightboxWidth'] = intval( $this->view_data['settings']['otw_pct_item_media_lightbox_width'] );
		}
		if( isset( $this->view_data['settings']['otw_pct_item_media_lightbox_height'] ) && strlen( $this->view_data['settings']['otw_pct_item_media_lightbox_height'] ) ){
			$this->view_data['imageLightboxHeight'] = intval( $this->view_data['settings']['otw_pct_item_media_lightbox_height'] );
		}
		if( isset( $this->view_data['settings']['otw_pct_item_media_lightbox_format'] ) && strlen( $this->view_data['settings']['otw_pct_item_media_lightbox_format'] ) ){
			$this->view_data['imageLightboxFormat'] = $this->view_data['settings']['otw_pct_item_media_lightbox_format'];
		}
		
		if( ( $this->view_data['imageLightboxWidth'] == $this->view_data['imageLightboxHeight'] ) && ( $this->view_data['imageLightboxWidth'] == 0 ) ){
			$this->view_data['imageLightboxWidth'] = '1024';
			$this->view_data['imageLightboxHeight'] = '640';
		}
		
		$this->view_data['imageRelatedWidth'] = '220';
		$this->view_data['imageRelatedHeight'] = '150';
		$this->view_data['imageRelatedFormat'] = '';
		$this->view_data['imageRelatedCrop'] = 'center_center';
		$this->view_data['imageRelatedWhiteSpaces'] = false;
		$this->view_data['imageRelatedBackground'] = false;
		
		if( isset( $this->view_data['settings']['otw_pct_related_media_width'] ) && strlen( $this->view_data['settings']['otw_pct_related_media_width'] ) ){
			$this->view_data['imageRelatedWidth'] = intval( $this->view_data['settings']['otw_pct_related_media_width'] );
		}
		if( isset( $this->view_data['settings']['otw_pct_related_media_height'] ) && strlen( $this->view_data['settings']['otw_pct_related_media_height'] ) ){
			$this->view_data['imageRelatedHeight'] = intval( $this->view_data['settings']['otw_pct_related_media_height'] );
		}
		if( isset( $this->view_data['settings']['otw_pct_related_media_format'] ) && strlen( $this->view_data['settings']['otw_pct_related_media_format'] ) ){
			$this->view_data['imageRelatedFormat'] = $this->view_data['settings']['otw_pct_related_media_format'];
		}
		
		if( ( $this->view_data['imageRelatedWidth'] == $this->view_data['imageRelatedHeight'] ) && ( $this->view_data['imageRelatedWidth'] == 0 ) ){
			$this->view_data['imageRelatedWidth'] = '220';
			$this->view_data['imageRelatedHeight'] = '150';
		}
		
		$this->view_data['postMetaData'] = get_post_meta( $post->ID, 'otw_bm_meta_data', true );
		
		if( !isset( $this->view_data['postMetaData']['media_type'] ) ){
			$this->view_data['postMetaData']['media_type'] = '';
		}
		
		if( $template != 'default' ){
			
			$this->is_used = true;
			
			foreach( $otw_pct_plugin_options as $key => $value ){
				if( !isset( $this->view_data['settings'][ $key ] ) ){
					$this->view_data['settings'][ $key ] = $value;
				}
			}
			$this->view_data['settings']['excerpt_length'] = '';
			$this->view_data['post'] = $post;
			
			if( $template == 'otw_custom_template' ){
				
				global $otw_post_items_data;
				
				if( !is_array( $otw_post_items_data ) ){
					$otw_post_items_data = array();
				}
				$otw_post_items_data[ $post->ID ] = array();
				$otw_post_items_data[ $post->ID ]['data'] = $this->view_data;
				$otw_post_items_data[ $post->ID ]['dispatcher'] = &$this;
			}
			
			ob_start();
			require_once( $this->views_path.$template.'.php');
			$template_content = ob_get_clean();
			global $otw_pctl_content_sidebars_object;
			
			get_header();
			if( isset( $this->view_data['otw_custom_template'] ) && isset( $this->view_data['otw_custom_template']['cs'] ) && isset( $this->view_data['otw_custom_template']['cs']['layout'] ) ){
				$cs_settings = array();
				$cs_settings['layout'] = $this->view_data['otw_custom_template']['cs']['layout'];
				$cs_settings['sidebars'] = array();
				$cs_settings['sidebars'][1] = array();
				$cs_settings['sidebars'][2] = array();
				
				$cs_settings['sidebars'][1]['size'] = $this->view_data['otw_custom_template']['cs']['sidebar1_size'];
				$cs_settings['sidebars'][1]['wp_sidebars'] = array( $this->view_data['otw_custom_template']['cs']['sidebar1'] );
				
				$cs_settings['sidebars'][2]['size'] = $this->view_data['otw_custom_template']['cs']['sidebar2_size'];
				$cs_settings['sidebars'][2]['wp_sidebars'] = array( $this->view_data['otw_custom_template']['cs']['sidebar2'] );
				
				echo $otw_pctl_content_sidebars_object->filter_show_content_sidebars( $template_content, $cs_settings );
			}else{
				echo $template_content;
			}
			get_footer();
			die;
		}
	}
	/**
	   * getTitle - Get Item (Post) Title
	   * @param $post - array
	   * @return mixed
	   */
	public function getTitle ( $post ) {
		return $this->loadComponent( 'title', $post );
	}
	
	public function getPostTabs( $args ){
		
		$content = '';
		
		if( isset( $args['tabs'] ) && is_array( $args['tabs'] ) && count( $args['tabs'] ) ){
			$content = $this->loadComponent( 'tabs', $args['post'], $args );
		}
		return $content;
	}
	
	public function getPostReviews( $args ){
		
		$content = '';
		
		if( isset( $args['reviews'] ) && is_array( $args['reviews'] ) && count( $args['reviews'] ) ){
			$content = $this->loadComponent( 'reviews', $args['post'], $args );
		}
		
		return $content;
	}
	
	/**
	 * loadComponent - Loads components found in /plugin_name/skeleton/components/*.php
	 * @param $componentName - string
	 * @param $post - array() - Post Data
	 * @return mixed
	 */
	private function loadComponent( $componentName, $post = null, $posts = null ){
		ob_start();
		include( $this->views_path.'components/'.$componentName.'.php' );
		return ob_get_clean();
	}
	
	private function loadWrapper( $wrapperName, $metaData ) {
		ob_start();
		include( $this->views_path.'wrappers/'. $wrapperName . '.php' );
		return ob_get_clean();
	}
	
	public function getItemMedia( $otw_pct_posts ){
		
		$check_attachment = false;
		
		if( !isset( $otw_pct_posts['postMetaData'] ) || !is_array( $otw_pct_posts['postMetaData'] ) ){
			$otw_pct_posts['postMetaData'] = array();
			$check_attachment = true;
		}
		
		if( !isset( $otw_pct_posts['postMetaData']['media_type'] ) || !$otw_pct_posts['postMetaData']['media_type'] ){
			$otw_pct_posts['postMetaData']['media_type'] = 'img';
			$check_attachment = true;
		}
		
		if( $check_attachment && $otw_pct_posts['postMetaData']['media_type'] == 'img' )
		{
			if( !isset( $otw_pct_posts['postMetaData']['img_url'] ) || !$otw_pct_posts['postMetaData']['img_url'] ){
			
				if( has_post_thumbnail( $otw_pct_posts['post']->ID ) ){
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $otw_pct_posts['post']->ID ), 'single-post-thumbnail' );
					
					if( !empty( $image[0] ) ){
						$otw_pct_posts['postMetaData']['img_url'] = $image[0];
					}else{
						$otw_pct_posts['postMetaData']['media_type'] = '';
					}
				}else{
					$otw_pct_posts['postMetaData']['media_type'] = '';
				}
			}
		}
		
		return $this->loadComponent( 'item_media', $otw_pct_posts );
	}
	
	/**
	 * getLink - get link for title or media items
	 * @param $post - array - post info
	 * @param $type - string - title or media item for getLink
	 */
	private function getLink ( $post , $type = null ) {
		
		if( !empty($type) ) {
			switch ( $type ) {
				case 'item_media':
						return $this->getPostAsset( $post, $type );
					break;
				case 'media':
						switch ( $this->view_data['settings']['image_link'] ) {
						
							case 'single':
									return get_permalink( $post->ID );
								break;
							case 'lightbox':
									return $this->getPostAsset( $post );
								break;
							default:
									return null;
								break;
						}
					break;
				case 'title':
						switch ( $this->view_data['settings']['otw_pct_title_link'] ) {
							case 'single':
									return get_permalink( $post->ID );
								break;
							case 'lightbox':
									return $this->getPostAsset( $post );
								break;
							default:
									return null;
								break;
						}
					break;
			}
		}
	}
	
	public function buildPostMetaItems( $post ){
		
		$this->metaItems = $this->view_data['settings']['otw_pct_meta_items'];
		
		return $this->buildInterfaceMetaItems( $this->metaItems, $post['post'] );
	}
	
	/**
	  * Get Meta Items in the specific order
	  * @param $metaItems - string (format: author,date,category,tags,comments)
	  * @return void()
	*/
	public function buildInterfaceMetaItems ( $metaItems = null, $post ) {
	
		$items = explode(',', $this->metaItems);
		
		$otw_details = get_option( 'otw_mb_post_details' );
		
		$metaHTML = '';
		
		foreach( $items as $item ){
			
			switch ( $item ) {
				case 'author':
						$metaHTML .= $this->loadComponent( 'meta_authors', $post );
					break;
				case 'date':
						$metaHTML .= $this->loadComponent( 'meta_date', $post );
					break;
				case 'category':
						$metaHTML .= $this->loadComponent( 'meta_categories', $post );
					break;
				case 'tags':
						$metaHTML .= $this->loadComponent( 'meta_tags', $post );
					break;
				case 'comments':
						$metaHTML .= $this->loadComponent( 'meta_comments', $post );
					break;
				case 'views':
						$metaHTML .= $this->loadComponent( 'meta_views', $post );
					break;
				default:
						if( preg_match( "/^otw_post_detail_(\d+)$/", $item, $matches ) ){
							
							$detail_value = '';
							$detail_title = '';
							
							if( isset( $otw_details[ $matches[1] ] ) ){
								$detail_title = $otw_details[ $matches[1] ]['title'];
							}
							$detail_value = get_post_meta( $post->ID, 'otw_mb_post_detail_'.$matches[1], true );
							
							$metaHTML .= $this->loadComponent( 'meta_post_detail', $post, array( $detail_title, $detail_value, $item ) );
						}
					break;
			}
		}
		
		return $this->loadWrapper('meta', $metaHTML);
	}
	
	/**
	 * getPostContent - Get Post Content
	 * @param $post - array
	 * @return mixed
	*/
	private function getPostContent ( $requested_post ) {
		
		$content = nl2br( $requested_post->post_content );
		
		$content = apply_filters( 'the_content', $content );
		
		return $content;
	}
	
	public function getPostCustomFields( $post_data, $params ){
		
		if( isset( $post_data['post'] ) && isset( $post_data['post']->ID ) && $post_data['post']->ID ){
		
			$this->view_data['custom_fields'] = array();
			$this->view_data['custom_fields']['params'] = $params;
			$this->view_data['custom_fields']['params']['title'] = '';
			
			$this->view_data['custom_fields']['items'] = get_post_meta( $post_data['post']->ID, 'otw_post_custom_fields_meta_data', true );
			
			if( isset( $post_data['settings'] ) && isset( $post_data['settings']['otw_pct_post_fields_title_text'] ) && strlen( $post_data['settings']['otw_pct_post_fields_title_text'] ) ){
				$this->view_data['custom_fields']['params']['title'] = $post_data['settings']['otw_pct_post_fields_title_text'];
			}
			
			if( isset( $post_data['settings'] ) && isset( $post_data['settings']['otw_pct_post_fields_delimiters'] ) && ( $post_data['settings']['otw_pct_post_fields_delimiters'] == 'yes' ) ){
				$this->view_data['custom_fields']['params']['fields_box_class'] .= ' otw_custom_fields_with_delimiter';
			}
			
			if( is_array( $this->view_data['custom_fields']['items'] ) && count( $this->view_data['custom_fields']['items'] ) ){
				
				uasort( $this->view_data['custom_fields']['items'], 'otw_pctl_sort_custom_fields' );
				
				return $this->loadComponent( 'custom_fields', $post_data['post'], '' );
			}
		}
	}
	
	public function getPostComments( $post_data ){
	
		$html = '';
		if( isset( $post_data['post'] ) && isset( $post_data['post']->ID ) ){
			
			if( comments_open( $post_data['post']->ID ) || get_comments_number( $post_data['post']->ID ) ){
				ob_start();
				comments_template();
				$html .= ob_get_contents();
				ob_end_clean();
			}
		}
		return $html;
	}
	
	/**
	 * getSocial - Get Social Links for a specific Post
	 * @param $post - array
	 * @return mixed
	 */
	public function getSocial ( $post, $type = 'list'  ){
		
		if( !empty( $this->view_data['settings']['otw_pct_show_social_icons'] ) ){
			return $this->loadComponent( 'social', $post, $type );
		}
	}
	
	public function getPostPagination( $args = array() ) {
		
		$content = '';
		
		if( isset( $args['otw_pct_prev_next_nav'] ) && ( $args['otw_pct_prev_next_nav'] == 'yes' ) ){
			
			$args = wp_parse_args( $args, array(
				'prev_text'          => '%title',
				'next_text'          => '%title'
			) );
			
			$previous   = get_previous_post_link( '%link', '&laquo; %title' );
			$next       = get_next_post_link( '%link', '%title &raquo;' );
			
			if ( $previous || $next ){
				$previous = str_replace( '<a ', '<a class="prev" ', $previous );
				$next = str_replace( '<a ', '<a class="next" ', $next );
				
				$content .= '<div class="otw_pct_clear otw_post_content-mb30"></div>';
				$content .= '<div class="otw_post_content-nav-single otw_pct_clearfix">';
				$content .= $previous;
				$content .= $next;
				$content .= '</div>';
			}
		
		}
		return $content;
	}
	
	/**
	 * getPostRelatedPosts - Get Related posts
	 * @param $args - array
	 * @return html
	 */
	public function getPostRelatedPosts( $args ){
		
		$content = '';
		
		$related_args = array();
		
		if( isset( $args['settings']['otw_pct_related_posts'] ) && ( $args['settings']['otw_pct_related_posts'] == 'yes' ) && isset( $args['settings']['otw_pct_related_posts_criteria'] ) && ( $args['settings']['otw_pct_related_posts_criteria'] == 'handpicked' ) && isset( $args['post'] ) && isset( $args['post']->ID ) ){
			
			$otw_related_posts = get_post_meta( $args['post']->ID, 'otw_mb_related_posts', true );
			
			if( strlen( $otw_related_posts ) ){
				
				$related_args = array(
						'post_type' => 'post',
						'post_status'     => 'publish',
						'post__in' => explode( ',', $otw_related_posts ),
						'post__not_in' => array($args['post']->ID)
				);
			}
			
		}elseif( isset( $args['settings']['otw_pct_related_posts'] ) && ( $args['settings']['otw_pct_related_posts'] == 'yes' ) && isset( $args['post'] ) && isset( $args['post']->ID ) ){
			
			$term_ids = array();
			
			if( !isset( $args['settings']['otw_pct_related_posts_criteria'] ) || ( $args['settings']['otw_pct_related_posts_criteria'] == 'category' ) ){
				
				if( isset( $args['categories'] ) && count( $args['categories'] ) ){
					foreach( $args['categories'] as $cat ){
						$term_ids[] = $cat->term_id;
					}
					
					$related_args = array(
						'post_type' => 'post',
						'post_status'     => 'publish',
						'tax_query'       => array( array(
							'taxonomy' => 'category',
							'field' => 'id',
							'terms' => $term_ids
						) ),
						'post__not_in' => array($args['post']->ID)
					);
				}
			}elseif( isset( $args['settings']['otw_pct_related_posts_criteria'] ) && ( $args['settings']['otw_pct_related_posts_criteria'] == 'post_tag' ) ){
				
				if( isset( $args['tags'] ) && count( $args['tags'] ) ){
				
					foreach( $args['tags'] as $tag ){
						$term_ids[] = $tag->term_id;
					}
					
					$related_args = array(
						'post_type' => 'post',
						'post_status'     => 'publish',
						'tax_query'       => array( array(
							'taxonomy' => 'post_tag',
							'field' => 'id',
							'terms' => $term_ids
						) ),
						'post__not_in' => array($args['post']->ID)
					);
				}
			}
		}
		
		if( count( $related_args ) ){
			
			$related_posts = new wp_query( $related_args );
			
			//loop all posts and remove those with no media
			if( count( $related_posts->posts ) ){
				
				$tmp_posts = $related_posts->posts;
				$related_posts->posts = array();
				
				foreach( $tmp_posts as $post_key => $post_data ){
					
					$add_post = false;
					
					//check if we have otw media
					$postMetaData = get_post_meta( $post_data->ID, 'otw_bm_meta_data', true );
					
					if( isset( $postMetaData['media_type'] ) ){
						
						if( isset( $postMetaData[ $postMetaData['media_type'].'_url' ] ) && strlen( trim( $postMetaData[ $postMetaData['media_type'].'_url' ] ) ) ){
							$add_post = true;
						}
					}
					
					if( !$add_post ){
						//check for featured image
						$postAttachement = wp_get_attachment_url( get_post_thumbnail_id( $post_data->ID ) );
						
						if( strlen( trim( $postAttachement ) ) ){
							$add_post = true;
						}
					}
					$add_post = true;
					if( $add_post ){
						$related_posts->posts[] = $post_data;
					}
				}
			}
			$content = $this->loadComponent( 'related_posts', $args['post'], $related_posts );
		}
		
		return $content;
	}
	
	/**
	 * get attachment meta data 
	 *
	 * @param $image url
	 * @return array
	 */
	public function getAttachmentMetaData( $image_url, $options ){
		
		global $wpdb;
		
		$metaData = array();
		$metaData['title'] = '';
		$metaData['alt'] = '';
		
		if( ( isset( $options['thumb_alt_attr'] ) && ( $options['thumb_alt_attr'] == 'media_settings' ) ) || ( isset( $options['thumb_title_attr'] ) && ( $options['thumb_title_attr'] == 'media_settings' ) ) ){
			
			$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
			
			if( isset( $attachment[0] ) && intval( $attachment[0] ) ){
				
				$att_post = get_post( $attachment[0] );
				
				if( isset( $att_post->ID ) && $att_post->ID ){
					
					if( isset( $options['thumb_title_attr'] ) && ( $options['thumb_title_attr'] == 'media_settings' ) ){
						$metaData['title'] = $att_post->post_title;
					}
					if( isset( $options['thumb_alt_attr'] ) && ( $options['thumb_alt_attr'] == 'media_settings' ) ){
						$metaData['alt'] = get_post_meta( $att_post->ID, '_wp_attachment_image_alt', true );
					}
				}
			
			}
		}
		return $metaData;
	}
	
	public function otwImageResize( $imgData = null, $resizeWidth, $resizeHeight, $crop = false, $white_spaces = true, $background = false, $format = '', $base_path = false ){
		
		global $otw_pctl_image_object, $otw_pctl_image_profile;
		
		return set_url_scheme( $otw_pctl_image_object->resize( $otw_pctl_image_profile, $imgData, $resizeWidth, $resizeHeight, $crop , $base_path, $white_spaces, $background, $format ) );
	}
	
	public function otwEmbedResize( $html, $resizeWidth, $resizeHeight, $crop = false ){
		
		global $otw_pctl_image_object, $otw_pctl_image_profile;
		
		return $otw_pctl_image_object->embed_resize( $otw_pctl_image_profile, $html, $resizeWidth, $resizeHeight, $crop );
	}
	
	/**
	* Get Post Assets - First Look For OTW Meta Box Content (img), if no Meta Box content has been found,
	* use featured image
	* @param $post - array()
	* @return string
	  */
	private function getPostAsset ( $post, $type = '' ) {
		
		$postMetaData = get_post_meta( $post->ID, 'otw_bm_meta_data', true );
		
		$media_type = 'img';
		if( isset( $postMetaData['media_type'] ) ){
			$media_type = $postMetaData['media_type'];
		}
		
		switch( $media_type ){
		
			case 'img':
					if( !empty( $postMetaData ) && !empty( $postMetaData['img_url'] ) ){
						
						$imagePath = parse_url( $postMetaData['img_url'] );
						
						if( isset( $this->view_data['imageLightboxWidth'] ) && preg_match( "/^\d+$/", $this->view_data['imageLightboxWidth'] ) ){
							
							$this->lightboxImageWidth = $this->view_data['imageLightboxWidth'];
						}
						
						if( isset( $this->view_data['imageLightboxHeight'] ) && preg_match( "/^\d+$/", $this->view_data['imageLightboxHeight'] ) ) {
							
							$this->lightboxImageHeight = $this->view_data['imageLightboxHeight'];
						}
						if( isset( $this->view_data['imageLightboxFormat'] ) ){
							
							$this->lightboxImageFormat = $this->view_data['imageLightboxFormat'];
						}
						
						return $this->otwImageResize( $imagePath['path'], $this->lightboxImageWidth, $this->lightboxImageHeight, $this->imageCrop, $this->imageWhiteSpaces, $this->imageBackground, $this->lightboxImageFormat );
					}
				break;
			case 'slider':
					if ( !empty( $postMetaData ) && !empty( $postMetaData['slider_url'] ) ){
						$sliderImages = explode(',', $postMetaData['slider_url']);
						
						if( !empty( $sliderImages[0] ) ){
							$imagePath = parse_url($sliderImages[0]);
							
							if( isset( $this->view_data['imageLightboxWidth'] ) && preg_match( "/^\d+$/", $this->view_data['imageLightboxWidth'] ) ){
								
								$this->lightboxImageWidth = $this->view_data['imageLightboxWidth'];
							}
							
							if( isset( $this->view_data['imageLightboxHeight'] ) && preg_match( "/^\d+$/", $this->view_data['imageLightboxHeight'] ) ) {
								
								$this->lightboxImageHeight = $this->view_data['imageLightboxHeight'];
							}
							if( isset( $this->view_data['imageLightboxFormat'] ) ){
								
								$this->lightboxImageFormat = $this->view_data['imageLightboxFormat'];
							}
							
							return $this->otwImageResize( $imagePath['path'], $this->lightboxImageWidth, $this->lightboxImageHeight, $this->imageCrop, $this->imageWhiteSpaces, $this->imageBackground, $this->lightboxImageFormat );
						}
						return $sliderImages[0];
					}
				break;
			case 'vimeo':
			case 'youtube':
			case 'soundcloud':
					$uniqueHash = wp_create_nonce("otw_pctl_get_video"); 
					$view_ref = '';
					if( strlen( $type ) ){
						$view_ref = '&vr='.$type;
					}
					return admin_url( 'admin-ajax.php?action=otw_pctl_get_video&post_id='. $post->ID.$view_ref.'&nonce='. $uniqueHash);
				break;
		}
/*	/*	if( !empty( $postMetaData ) && !empty( $postMetaData['img_url'] ) ) {
			return $postMetaData['img_url'];
		} elseif ( !empty( $postMetaData ) && !empty( $postMetaData['slider_url'] ) ){
			$sliderImages = explode(',', $postMetaData['slider_url']);
			return $sliderImages[0];
		}*/
		$postAsset = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
		
		if( !empty( $postAsset ) ) {
		
			if( isset( $this->view_data['imageLightboxWidth'] ) && preg_match( "/^\d+$/", $this->view_data['imageLightboxWidth'] ) ){
				$this->lightboxImageWidth = $this->view_data['imageLightboxWidth'];
			}
			
			if( isset( $this->view_data['imageLightboxHeight'] ) && preg_match( "/^\d+$/", $this->view_data['imageLightboxHeight'] ) ) {
			
				$this->lightboxImageHeight = $this->view_data['imageLightboxHeight'];
			}
			if( isset( $this->view_data['imageLightboxFormat'] ) ){
			
				$this->lightboxImageFormat = $this->view_data['imageLightboxFormat'];
			}
			
			$imagePath = parse_url( $postAsset );
			
			return $this->otwImageResize( $imagePath['path'], $this->lightboxImageWidth, $this->lightboxImageHeight, $this->imageCrop, $this->imageWhiteSpaces, $this->imageBackground, $this->lightboxImageFormat );
		}
		return null;
	}
	
	 /**
	* excerptLength - Get content based on word count.
	* @return string
	*/
	private function excerptLength($content, $count, $strip_tags = true ){
		
		if( $strip_tags ){
			$content = strip_tags($content);
			$content = str_replace('&nbsp;', ' ', $content);
			$content = preg_split("/\s+/", $content);
			
			if( $count == 0 ){
				$count = 1;
			}
			
			if ($count < count($content) ) {
				$content = array_slice($content, 0, $count);
			}
			$content = join(" ", $content);
		}else{
			$content = $this->htmlExcerptLength( $content, $count  );
		}
		return $content;
	}
	
	public function getPostExcerpt( $post ){
		
		$content = '';
		
		if( !empty( $post->post_excerpt ) ){
			$content = $post->post_excerpt;
		}else{
			$extended = get_extended( $post->post_content );
			
			if( !empty( $extended['main'] ) ){
				$content = $extended['main'];
			}else{
				$content = $post->post_content;
			}
		}
		return $content;
	}
	
	private function htmlExcerptLength( $content, $count ){
		
		$new_content = '';
		
		$content_size = strlen( $content );
		
		$open_tag = false;
		
		$tags = array();
		
		$tag_index = 0;
		
		$new_count = 0;
		
		$word_started = true;
		
		$opened_tags = array();
		
		for( $cC = 0; $cC < $content_size; $cC++ ){
			
			$current_char = $content{ $cC };
			
			if( $current_char == '<' ){
				
				$tag_index = count( $tags );
				$open_tag = true;
				$tags[ $tag_index ] = array();
				$tags[ $tag_index ]['open'] = '<';
				$tags[ $tag_index ]['closed'] = false;
				
				if( $new_count >= $count ){
					
					if( isset( $content{ $cC + 1 } ) && ( $content{ $cC + 1 } != '/' ) ){
						break;
					}
				}
			}
			elseif( $current_char == '>' ){
				
				$open_tag = false;
				$tags[ $tag_index ]['open'] .= '>';
				$new_content .= $tags[ $tag_index ]['open'];
				
				if( preg_match_all( "/^\<([a-zA-Z0-9]+)([\s+])?(.*)?\>$/", $tags[ $tag_index ]['open'], $o_tag_match ) ){
					
					//check if this tags is closed
					if( !preg_match( "/\/\>$/", $tags[ $tag_index ]['open'] ) ){
						$opened_tags[] = trim( $o_tag_match[1][0] );
					}
					
					
				}elseif( preg_match_all( "/^\<\/([a-zA-Z0-9]+)([\s+])?(.*)?\>$/", $tags[ $tag_index ]['open'], $o_tag_match ) ){
					
					$closing_tag = trim( $o_tag_match[1][0] );
					$total_opened_tags  = count( $opened_tags );
					
					if( $total_opened_tags ){
						
						$total_opened_tags = $total_opened_tags - 1;
						for( $cO = $total_opened_tags; $cO >=0; $cO-- ){
							
							if( $closing_tag == $opened_tags[ $cO ] ){
								unset( $opened_tags[ $cO ] );
								$opened_tags = array_values( $opened_tags );
								break;
							}
						}
					}
				}
			}
			elseif( !$open_tag ){
				
				if( preg_match( "/[[:space:][:punct:]]/", $current_char, $match ) ){
				
					if( $word_started ){
						$new_count++;
						$word_started = false;
					}
					
					if( $new_count < $count ){
						$new_content .= $match[0];
					}
				}else{
					if( $new_count < $count ){
						$new_content .= $current_char;
					}
					$word_started = true;
				}
			}
			elseif( $open_tag ){
				
				$tags[ $tag_index ]['open'] .= $current_char;
			}
		}
		
		if( count( $opened_tags ) ){
			
			foreach( $opened_tags as $o_tag ){
			
				if( !in_array( $o_tag, array( 'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr' ) ) ){
					$new_content .= '</'.$o_tag.'>';
				}
			}
		}
		return $new_content;
	}
}
?>
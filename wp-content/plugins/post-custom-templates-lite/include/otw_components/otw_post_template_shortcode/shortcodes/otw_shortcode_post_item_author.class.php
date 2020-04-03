<?php
class OTW_Post_Template_Shortcode_Post_Item_Author extends OTW_Post_Template_Shortcodes{
	
	public function __construct(){
		
		$this->has_options = false;
		
		$this->has_custom_options = false;
		
		$this->has_preview = false;
		
		parent::__construct();
		
		$this->shortcode_name = 'otw_shortcode_post_item_author';
	}
	
	/**
	 * register external libs
	 */
	public function register_external_libs(){
	
		$this->add_external_lib( 'css', 'otw-shortcode', $this->component_url.'css/otw_shortcode.css', 'all', 100 );
		
		$this->add_external_lib( 'js', 'otw-shortcode-core', $this->component_url.'js/otw_shortcode_core.js', 'all', 99, array( 'jquery' ) );
		$this->add_external_lib( 'js', 'otw-shortcode', $this->component_url.'js/otw_shortcode.js', 'front', 100 );
		$this->add_external_lib( 'js', 'otw-shortcode_live_preview', $this->component_url.'js/otw_shortcode_live_preview.js', 'live_preview', 200 );
	}
	
	/**
	 * apply settings
	 */
	public function apply_settings(){
		
		$this->settings = array();
	}
	/**
	 * Shortcode icon_link admin interface
	 */
	public function build_shortcode_editor_options(){
		
		$this->apply_settings();
		
		$html = '';
		
		$source = array();
		if( isset( $_POST['shortcode_object'] ) ){
			$source = $_POST['shortcode_object'];
		}
		
		return $html;
	}
	
	/** build icon link shortcode
	 *
	 *  @param array
	 *  @return string
	 */
	public function build_shortcode_code( $attributes ){
		
		$code = '';
		
		if( !$this->has_error ){
		
			$code = '[otw_shortcode_post_item_author]';
		}
		
		return $code;
	}
	
	/**
	 * Process shortcode icon link
	 */
	public function display_shortcode( $attributes, $content ){
		
		$html = '';
		
		$post_item_id = $this->format_attribute( '', 'post_item_id', $attributes, false, '' );
		
		if( is_admin() ){
			$html = '<img src="'.$this->component_url.'images/sidebars-icon-placeholder.png'.'" alt=""/>';
		}else{
			$html = '';
			
			if( $post_item_id ){
			
				global $otw_post_items_data;
				
				if( is_array( $otw_post_items_data ) && isset( $otw_post_items_data[ $post_item_id ] ) && isset( $otw_post_items_data[ $post_item_id ]['data'] ) && isset( $otw_post_items_data[ $post_item_id ]['data']['post'] ) ){
					
					if( isset( $otw_post_items_data[ $post_item_id ]['data']['post']->post_author ) && intval( $otw_post_items_data[ $post_item_id ]['data']['post']->post_author ) ){
					
						$user_data = get_userdata( $otw_post_items_data[ $post_item_id ]['data']['post']->post_author );
						
						if( isset( $user_data->data ) && isset( $user_data->data->ID ) && intval( $user_data->data->ID ) ){
							
							$html = '<div class="otw-widget-blogauthorinfo">';
							$html .= '<a class="image"';
							
							if( !empty( $user_data->data->user_url ) ){
								$html .= ' href="'.$user_data->data->user_url.'"';
							}else{
								$html .= ' href="javascript:;"';
							}
							$html .= '>';
							$html .= get_avatar( $user_data->data->user_email, 80 );
							$html .= '<span class="shadow-overlay hide-for-small"></span>';
							$html .= '</a>';
							
							$html .= '<div class="author-contents" style="margin-left: 90px;">';
							$html .= '<h3 class="widget-title">'.$user_data->first_name.' '.$user_data->last_name.'</h3>';
							$html .= '<p>'.nl2br( $user_data->description ).'</p>';
							
							if( !empty( $user_data->data->user_url ) ){
								
								$html .= '<div class="read-more"><a href="'.$user_data->data->user_url.'">'.$this->get_label( 'More about the author' ).'</a></div>';
							}
							$html .= '</div>';
							
							$html .= '</div>';
						}
					}
				}
			}
		}
		
		return $this->format_shortcode_output( $html );
	}
	
	/**
	 * Return shortcode attributes
	 */
	public function get_shortcode_attributes( $attributes ){
		
		$shortcode_attributes = array();
		
		if( isset( $attributes['item_type'] ) ){
		
			if( isset( $this->settings['item_type_options'][ $attributes['item_type'] ] ) ){
				$shortcode_attributes['iname'] = $this->settings['item_type_options'][ $attributes['item_type'] ];
			}else{
				$shortcode_attributes['iname'] = ucfirst( $attributes['item_type'] );
			}
		}
		
		return $shortcode_attributes;
	}
}
?>
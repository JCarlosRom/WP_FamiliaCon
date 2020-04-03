<?php
class OTW_Post_Template_Shortcode_Post_Item_Prev_Next_Navigation extends OTW_Post_Template_Shortcodes{
	
	public function __construct(){
		
		$this->has_options = false;
		
		$this->has_custom_options = false;
		
		$this->has_preview = false;
		
		parent::__construct();
		
		$this->shortcode_name = 'otw_shortcode_post_item_prev_next_navigation';
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
		
			$code = '[otw_shortcode_post_item_prev_next_navigation]';
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
				
				if( is_array( $otw_post_items_data ) && isset( $otw_post_items_data[ $post_item_id ] ) && isset( $otw_post_items_data[ $post_item_id ]['data'] ) ){
					$html .= $otw_post_items_data[ $post_item_id ]['dispatcher']->getPostPagination( $otw_post_items_data[ $post_item_id ]['data']['settings'] );
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
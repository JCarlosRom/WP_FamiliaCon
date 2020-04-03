<?php
class OTW_Post_Template_Shortcode_Post_Item_Meta extends OTW_Post_Template_Shortcodes{
	
	public function __construct(){
		
		$this->has_options = true;
		
		$this->has_custom_options = false;
		
		$this->has_preview = false;
		
		$this->custom_styles = array();
		
		$this->shortcode_id = '';
		
		$this->google_fonts = array();
		
		$this->fonts_to_include = array();
		
		parent::__construct();
		
		$this->shortcode_name = 'otw_shortcode_post_item_meta';
	}
	/**
	 * register external libs
	 */
	public function register_external_libs(){
	
		$this->add_external_lib( 'css', 'otw-shortcode-font-awesome', $this->component_url.'css/font-awesome.min.css', 'all', 10 );
	}
	
	/**
	 * apply settings
	 */
	public function apply_settings(){
		
		$this->settings = array(
			'items' => array(
				'author'  => $this->get_label( 'author' ),
				'date'  => $this->get_label( 'date' ),
				'category'  => $this->get_label( 'category' ),
				'tags'  => $this->get_label( 'tags' ),
				'comments'  => $this->get_label( 'comments' ),
				'views'  => $this->get_label( 'Post Visits' )
			),
			'default_items' => 'date,category'
		);
		
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
		
		$labels = $this->settings['default_labels'];
		
		if( isset( $source['otw-shortcode-element-labels'] ) ){
			$labels = $source['otw-shortcode-element-labels'];
		}
		
		$html .= OTW_Form::active_elements( array( 'id' => 'otw-shortcode-element-items', 'label_active_elements' => $this->get_label( 'Active Elements' ), 'label_inactive_elements' => $this->get_label( 'Inactive Elements' ), 'description' => $this->get_label( 'Drag & drop the items that you\'d like to show in the Active Elements area on the left. Arrange them however you want to see them.' ), 'items' => $this->settings['items'], 'value' => $this->settings['default_items'], 'parse' => $source ) );
		
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
		
			$code = '[otw_shortcode_post_item_meta';
			
			$code .= $this->format_attribute( 'items', 'items', $attributes );
			
			$code .= ']';
			
			$code .= '[/otw_shortcode_post_item_meta]';
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
					
					$this->shortcode_id = $this->format_attribute( '', 'ssid', $attributes, false, '' );
					
					$otw_post_items_data[ $post_item_id ]['dispatcher']->view_data['settings']['otw_pct_meta_id'] = $this->format_attribute( '', 'ssid', $attributes, false, '' );
					
					$otw_post_items_data[ $post_item_id ]['dispatcher']->view_data['settings']['otw_pct_meta_items'] = $this->format_attribute( '', 'items', $attributes, false, '' );
					
					$html .= $otw_post_items_data[ $post_item_id ]['dispatcher']->buildPostMetaItems( $otw_post_items_data[ $post_item_id ]['data'] );
				}
			}
		}
		
		return $this->format_shortcode_output( $html );
	}
	
	function otw_shortcode_custom_styles(){
	
		if( count( $this->fonts_to_include ) ){
			
			$url = '//fonts.googleapis.com/css?family='.urlencode( implode( '|', $this->fonts_to_include ) ).'&variant=italic:bold';
			
			wp_enqueue_style('otw-smi-googlefonts',$url, null, null);
		}
	
		echo '<style type="text/css">'.implode( ' ', $this->custom_styles ).'</style>';
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
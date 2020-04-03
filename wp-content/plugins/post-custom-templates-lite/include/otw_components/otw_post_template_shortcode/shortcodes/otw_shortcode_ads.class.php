<?php
class OTW_Post_Template_Shortcode_Ads extends OTW_Post_Template_Shortcodes{
	
	public function __construct(){
		
		$this->has_custom_options = false;
		
		$this->preview = 'div';
		
		parent::__construct();
		
		$this->shortcode_name = 'otw_shortcode_ads';
	}
	
	/**
	 * apply settings
	 */
	public function apply_settings(){
		
		$this->settings = array(
			
			'types' => array(
				'img'  => 'Banner(default)',
				'code' => 'Ad code'
			),
			'default_type' => 'img',
			'targets' => array(
				'_self'  => 'Same Window/Tab (default)',
				'_blank' => 'New Window/Tab'
			),
			'default_target' => '_self',
			
		);
	}
	
	/**
	 * Shortcode admin interface
	 */
	public function build_shortcode_editor_options(){
		
		$html = '';
		
		$source = array();
		if( isset( $_POST['shortcode_object'] ) ){
			$source = $_POST['shortcode_object'];
		}
		
		$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-title', 'label' => $this->get_label( 'Title (optional)' ), 'description' => $this->get_label( 'The title for your Ad' ), 'parse' => $source )  );
		
		$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-type', 'label' => $this->get_label( 'Banner or Ad code' ), 'description' => $this->get_label( 'Select Banner or Ad code' ), 'parse' => $source, 'options' => $this->settings['types'], 'value' => $this->settings['default_type'], 'data-reload' => '1' ) );
		
		if( !isset( $source['otw-shortcode-element-type'] ) ){
			$source['otw-shortcode-element-type'] = 'img';
		}
		
		switch( $source['otw-shortcode-element-type'] ){
			
			case 'code':
					$html .= OTW_Form::text_area( array( 'id' => 'otw-shortcode-element-code', 'label' => $this->get_label( 'Ad Code' ), 'description' => $this->get_label( 'Paste your Ad code here' ), 'parse' => $source )  );
				break;
			case 'img':
			default:
					$html .= OTW_Form::uploader( array( 'id' => 'otw-shortcode-element-image_url', 'label' => $this->get_label( 'Image' ), 'description' => $this->get_label( 'Choose an image' ), 'parse' => $source )  );
					$html .= OTW_Form::text_input( array( 'id' => 'otw-shortcode-element-link', 'label' => $this->get_label( 'URL' ), 'description' => $this->get_label( 'Type in the URL where the click on the image leads to' ), 'parse' => $source )  );
					$html .= OTW_Form::select( array( 'id' => 'otw-shortcode-element-target', 'label' => $this->get_label( 'Link Target' ), 'description' => $this->get_label( 'Select if you would like to open the link in a new window / tab or the same window / tab.' ), 'parse' => $source, 'options' => $this->settings['targets'], 'value' => $this->settings['default_target'], 'data-reload' => '1' ) );
				break;
		}
		
		
		return $html;
	}
	
	/** build shortcode
	 *
	 *  @param array
	 *  @return string
	 */
	public function build_shortcode_code( $attributes ){
		
		$code = '';
		
		if( !$this->has_error ){
		
			$code = '[otw_shortcode_ads';
			
			$code .= $this->format_attribute( 'title', 'title', $attributes );
			
			$code .= $this->format_attribute( 'type', 'type', $attributes );
			
			$code .= $this->format_attribute( 'target', 'target', $attributes );
			
			$type = $this->format_attribute( '', 'type', $attributes );
			
			switch( $type ){
				
				case 'code':
						$code .= ']';
						$code .= $attributes['code'];
					break;
				case 'img':
				default:
						$code .= $this->format_attribute( 'image_url', 'image_url', $attributes );
						$code .= $this->format_attribute( 'link', 'link', $attributes );
						$code .= ']';
					break;
			}
			
			$code .= '[/otw_shortcode_ads]';
		}
		return $code;
	}
	
	/**
	 * Display shortcode
	 */
	public function display_shortcode( $attributes, $content ){
		
		$html = '';
		$html .= '<div class="otw-ad">';
		
		if( $title = $this->format_attribute( '', 'title', $attributes, false, '' ) ){
			$html .= '<h3>'.$title.'</h3>';
		}
		
		$type = $this->format_attribute( '', 'type', $attributes, false, '' );
		
		switch( $type ){
			case 'code':
					$html .= $content;
				break;
			case 'img':
					if( $link = $this->format_attribute( '', 'link', $attributes, false, '' ) ){
						
						$target = $this->format_attribute( '', 'target', $attributes, false, '' );
						
						$html .= '<a href="'.$link.'" target="'.$target.'">';
					}
					
					if( $image_url = $this->format_attribute( '', 'image_url', $attributes, false, '' ) ){
						$html .= '<img src="'.$image_url.'" border="0" alt="" />';
					}
					
					if( $link = $this->format_attribute( '', 'link', $attributes, false, '' ) ){
						$html .= '</a>';
					}
			default:
				break;
		}
		
		$html .= '</div>';
		
		
		return $this->format_shortcode_output( $html );
	}
}

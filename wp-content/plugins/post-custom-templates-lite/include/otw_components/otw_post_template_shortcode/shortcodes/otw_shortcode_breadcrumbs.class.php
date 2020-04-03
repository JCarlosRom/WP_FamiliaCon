<?php
class OTW_Post_Template_Shortcode_Breadcrumbs extends OTW_Post_Template_Shortcodes{
	
	public function __construct(){
		
		$this->has_custom_options = false;
		
		parent::__construct();
		
		$this->shortcode_name = 'otw_shortcode_breadcrumbs';
	}
	/**
	 * register external libs
	 */
	public function register_external_libs(){
	
		$this->add_external_lib( 'css', 'otw-shortcode', $this->component_url.'css/otw_shortcode.css', 'all', 100 );
	}
	/**
	 * apply settings
	 */
	public function apply_settings(){
		
		$this->settings = array();
		
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
		
		return $html;
	}
	
	/**
	 * Shortcode admin interface custom options
	 */
	public function build_shortcode_editor_custom_options(){
		
		$html = '';
		
		$source = array();
		if( isset( $_POST['shortcode_object'] ) ){
			$source = $_POST['shortcode_object'];
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
		
			$code = '[otw_shortcode_breadcrumbs';
			
			$code .= ']';
			
			$code .= '[/otw_shortcode_breadcrumbs]';
		
		}
		
		return $code;

	}
	
	/**
	 * Display shortcode
	 */
	public function display_shortcode( $attributes, $content ){
		
		global $post;
		
		$html = '';
		
		$text['home']     = $this->get_label('Home'); // text for the 'Home' link
		$text['category'] = $this->get_label('Archive for %s'); // text for a category page
		$text['search']   = $this->get_label('Search results for: %s'); // text for a search results page
		$text['tag']      = $this->get_label('Posts tagged %s'); // text for a tag page
		$text['author']   = $this->get_label('View all posts by %s'); // text for an author page
		$text['404']      = $this->get_label('Error 404'); // text for the 404 page
		$text['page']     = $this->get_label('Page %s'); // text 'Page N'
		$text['cpage']    = $this->get_label('Comment Page %s'); // text 'Comment Page N'
		
		$wrap_before    = '<div class="otw_breadcrumbs">'; // the opening wrapper tag
		$wrap_after     = '</div><!-- .breadcrumbs -->'; // the closing wrapper tag
		$sep            = ' &raquo; '; // separator between crumbs
		$sep_before     = '<span class="otw_breadcrumbs_sep">'; // tag before separator
		$sep_after      = '</span>'; // tag after separator
		$show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
		$show_on_home   = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
		$show_current   = 1; // 1 - show current page title, 0 - don't show
		$before         = '<span class="otw_breadcrumbs_current">'; // tag before the current crumb
		$after          = '</span>'; // tag after the current crumb
		
		$home_link      = home_url('/');
		$link_before    = '<span typeof="v:Breadcrumb">';
		$link_after     = '</span>';
		$link_attr      = ' rel="v:url" property="v:title"';
		$link_in_before = '<span itemprop="title">';
		$link_in_after  = '</span>';
		$link           = $link_before . '<a href="%1$s"' . $link_attr . '>' . $link_in_before . '%2$s' . $link_in_after . '</a>' . $link_after;
		$frontpage_id   = get_option('page_on_front');
		if( $post && isset( $post->post_parent ) ){
			$parent_id      = $post->post_parent;
		}else{
			$parent_id = false;
		}
		$sep            = ' ' . $sep_before . $sep . $sep_after . ' ';
		
		if (is_home() || is_front_page()) {
			
			if ($show_on_home){ 
				$html .= $wrap_before . '<a href="' . $home_link . '">' . $text['home'] . '</a>' . $wrap_after;
			}
		} else {
			
			$html .= $wrap_before;
			
			if ($show_home_link){
				$html .= sprintf($link, $home_link, $text['home']);
			}
			
			if ( is_category() ) {
				$cat = get_category(get_query_var('cat'), false);
				
				if ($cat->parent != 0) {
					$cats = get_category_parents($cat->parent, TRUE, $sep);
					$cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
					$cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
					
					if ($show_home_link){
						$html .= $sep;
					}
					$html .= $cats;
				}
				if ( get_query_var('paged') ) {
					$cat = $cat->cat_ID;
					$html .= $sep . sprintf($link, get_category_link($cat), get_cat_name($cat)) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
				} else {
					if ($show_current){
						$html .= $sep . $before . sprintf($text['category'], single_cat_title('', false)) . $after;
					}
				}
			} elseif ( is_search() ) {
				
				if (have_posts()) {
					if ($show_home_link && $show_current){ 
						$html .= $sep;
					}
					if ($show_current){
						$html .= $before . sprintf($text['search'], get_search_query()) . $after;
					}
				} else {
					if ($show_home_link){
						$html .= $sep;
					}
					$html .= $before . sprintf($text['search'], get_search_query()) . $after;
				}
			} elseif ( is_day() ) {
				if ($show_home_link){
					$html .= $sep;
				}
				$html .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $sep;
				$html .= sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'));
				
				if ($show_current){
					$html .= $sep . $before . get_the_time('d') . $after;
				}
			} elseif ( is_month() ) {
				
				if ($show_home_link){
					$html .= $sep;
				}
				$html .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'));
				
				if ($show_current){
					$html .= $sep . $before . get_the_time('F') . $after;
				}
			} elseif ( is_year() ) {
				
				if ($show_home_link && $show_current){
					$html .= $sep;
				}
				if ($show_current){
					$html .= $before . get_the_time('Y') . $after;
				}
			} elseif ( is_single() && !is_attachment() ) {
				
				if ($show_home_link){
					$html .= $sep;
				}
				
				if ( get_post_type() != 'post' ) {
					$post_type = get_post_type_object(get_post_type());
					$slug = $post_type->rewrite;
					
					$html .= sprintf($link, $home_link . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
					
					if ($show_current){
						$html .= $sep . $before . get_the_title() . $after;
					}
				} else {
					$cat = get_the_category(); $cat = $cat[0];
					$cats = get_category_parents($cat, TRUE, $sep);
					
					if (!$show_current || get_query_var('cpage')){
						$cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
					}
					$cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
					
					$html .= $cats;
					
					if ( get_query_var('cpage') ) {
						$html .= $sep . sprintf($link, get_permalink(), get_the_title()) . $sep . $before . sprintf($text['cpage'], get_query_var('cpage')) . $after;
					} else {
						if ($show_current){
							$html .= $before . get_the_title() . $after;
						}
					}
				}
			// custom post type
			} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
				
				$post_type = get_post_type_object(get_post_type());
				
				if ( get_query_var('paged') ) {
					$html .= $sep . sprintf($link, get_post_type_archive_link($post_type->name), $post_type->label) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
				} else {
					if ($show_current){
						$html .= $sep . $before . $post_type->label . $after;
					}
				}
				
			} elseif ( is_attachment() ) {
				
				if ($show_home_link){
					$html .= $sep;
				}
				$parent = get_post($parent_id);
				$cat = get_the_category($parent->ID); $cat = $cat[0];
				
				if ($cat) {
					$cats = get_category_parents($cat, TRUE, $sep);
					$cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
					$html .= $cats;
				}
				$html .= sprintf($link, get_permalink($parent), $parent->post_title);
				
				if ($show_current){
					$html .= $sep . $before . get_the_title() . $after;
				}
			} elseif ( is_page() && !$parent_id ) {
				
				if ($show_current){
					$html .= $sep . $before . get_the_title() . $after;
				}
			} elseif ( is_page() && $parent_id ) {
				
				if ($show_home_link){
					$html .= $sep;
				}
				
				if ($parent_id != $frontpage_id) {
					$breadcrumbs = array();
					while ($parent_id) {
						$page = get_page($parent_id);
						if ($parent_id != $frontpage_id) {
							$breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
						}
						$parent_id = $page->post_parent;
					}
					$breadcrumbs = array_reverse($breadcrumbs);
					
					for ($i = 0; $i < count($breadcrumbs); $i++) {
						$html .= $breadcrumbs[$i];
						if ($i != count($breadcrumbs)-1){
							$html .= $sep;
						}
					}
				}
				if ($show_current){
					$html .= $sep . $before . get_the_title() . $after;
				}
			} elseif ( is_tag() ) {
				
				if ( get_query_var('paged') ) {
					$tag_id = get_queried_object_id();
					$tag = get_tag($tag_id);
					$html .= $sep . sprintf($link, get_tag_link($tag_id), $tag->name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
				} else {
					if ($show_current){
						$html .= $sep . $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
					}
				}
			} elseif ( is_author() ) {
				
				global $author;
				$author = get_userdata($author);
				if ( get_query_var('paged') ) {
					
					if ($show_home_link){
						$html .= $sep;
					}
					$html .= sprintf($link, get_author_posts_url($author->ID), $author->display_name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
				} else {
					if ($show_home_link && $show_current){
						$html .= $sep;
					}
					if ($show_current){
						$html .= $before . sprintf($text['author'], $author->display_name) . $after;
					}
				}
			} elseif ( is_404() ) {
				if ($show_home_link && $show_current){
					$html .= $sep;
				}
				if ($show_current){
					$html .= $before . $text['404'] . $after;
				}
			} elseif ( has_post_format() && !is_singular() ) {
				
				if ($show_home_link){
					$html .= $sep;
				}
				$html .= get_post_format_string( get_post_format() );
			}
			$html .= $wrap_after;
		}
		
		return $this->format_shortcode_output( $html );
	}
}

<!-- Blog Info -->
<?php
	$meta_class = '';
	
	if( $this->view_data['settings']['otw_pct_meta_type_align'] && ( $this->view_data['settings']['otw_pct_meta_type_align'] == 'vertical' ) ){
		
		if( !isset( $this->view_data['settings']['otw_pct_meta_separators'] ) || ( $this->view_data['settings']['otw_pct_meta_separators'] != 'no' ) ){
			$meta_class = ' otw-meta-lines-sep';
		}else{
			$meta_class = ' otw-meta-lines';
		}
	}elseif( !isset( $this->view_data['settings']['otw_pct_meta_separators'] ) || ( $this->view_data['settings']['otw_pct_meta_separators'] != 'no' ) ){
		$meta_class = ' otw-meta-sep';
	}
	
	if( isset( $this->view_data['settings']['otw_pct_meta_css_class'] ) && strlen( trim( $this->view_data['settings']['otw_pct_meta_css_class'] ) ) ){
	
		$meta_class .= ' '.trim( $meta_class.' '.$this->view_data['settings']['otw_pct_meta_css_class'] );
	}
	
	$meta_id = '';
	
	if( isset( $this->view_data['settings']['otw_pct_meta_id'] ) ){
		$meta_id = ' id="'.$this->view_data['settings']['otw_pct_meta_id'].'"';
	}
?>
<div class="otw_post_content-blog-meta-wrapper<?php echo $meta_class;?>"<?php echo $meta_id?>>
  <?php echo $metaData; ?>
</div>
<!-- End Blog Info -->
	<!-- Wrapper with sidebar -->
	<div class="otw-row">
		<section class="otw-twentyfour otw-columns">
			<?php 
				$this->grid_manager_component_object->post_item_id = $post->ID;
				$template_content = $this->grid_manager_component_object->decode_grid_content( 'otwct_'.$post->ID, otw_stripslashes( $this->view_data['otw_custom_template']['grid_content'] ) );
				echo $this->grid_manager_component_object->otw_shortcode_remove_wpautop( $template_content );
			?>
		</section>
	</div>
	<!-- End Wrapper with sidebar -->
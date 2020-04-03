<?php
	$_wp_column_headers['toplevel_page_otw-pm'] = array(
	'title' => __( 'Title', 'otw_pctl' )
);
	
	$message = '';
	$massages = array();
	$messages[1] = __( 'Single Posts Template saved.', 'otw_pctl' );
	$messages[2] = __( 'Single Posts Template deleted.', 'otw_pctl' );
	$messages[5] = __( 'Single Posts Template copied.', 'otw_pctl' );
	
	if( isset( $_GET['message'] ) && isset( $messages[ $_GET['message'] ] ) ){
		$message .= $messages[ $_GET['message'] ];
	}
	
?>
<?php if ( $message ) : ?>
<div id="message" class="updated"><p><?php echo $message; ?></p></div>
<?php
 endif; ?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2><?php _e('Single Posts Templates', 'otw_pctl'); ?>
		<a class="button add-new-h2" href="admin.php?page=otw-pctl-custom-templates-add"><?php _e('Add New', 'otw_pctl') ?></a>
	</h2>
	<?php include_once( 'otw_pctl_help.php' ); ?>
	<div class="updated"><p><?php _e( 'In this section you can create custom templates.<br> You can select which template your theme uses in the plugin Options page.', 'otw_pctl' );?></p></div>
	<table class="widefat fixed" cellspacing="0">
		<thead>
			<tr>
				<?php foreach( $_wp_column_headers['toplevel_page_otw-pm'] as $key => $name ){?>
					<th><?php echo $name?></th>
				<?php }?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<?php foreach( $_wp_column_headers['toplevel_page_otw-pm'] as $key => $name ){?>
					<th><?php echo $name?></th>
				<?php }?>
			</tr>
		</tfoot>
		<tbody>
	<?php if( is_array( $otw_custom_templates ) && count( $otw_custom_templates ) ){?>
		<?php foreach( $otw_custom_templates as $d_key => $d_item ){?>
			<?php if( isset( $d_item['pdf_id'] ) && strlen( $d_item['pdf_id'] ) ){?>
			<tr class="otw_pct_default">
			<?php }else{ ?>
			<tr>
			<?php } ?>
				<?php foreach( $_wp_column_headers['toplevel_page_otw-pm'] as $column_name => $column_title ){
					
					$edit_link = admin_url( 'admin.php?page=otw-pctl-custom-templates-edit&amp;custom_template='.$d_item['id'] );
					$delete_link = admin_url( 'admin.php?page=otw-pctl-custom-templates-action&amp;custom_template='.$d_item['id'].'&amp;action=delete' );
					switch($column_name) {
						
						case 'title':
								echo '<td><strong><a href="'.$edit_link.'" title="'.esc_attr(sprintf(__('Edit &#8220;%s&#8221;', 'otw_pctl'), $d_item['title'])).'">';
								
								if( !isset( $d_item['title'] ) || !strlen( trim( $d_item['title'] ) ) ){
									_e( 'No title', 'otw_pctl' );
								}else{
									echo $d_item['title'];
								}
								echo '</a></strong><br />';
								echo '<div class="row-actions">';
									if( !isset( $d_item['pdf_id'] ) ){
										echo '<a href="'.$edit_link.'">' . __('Edit', 'otw_pctl') . '</a>';
										echo ' | <a href="'.$delete_link.'">' . __('Delete', 'otw_pctl'). '</a>';
									}else{
										echo '<a href="'.$edit_link.'">' . __('Edit', 'otw_pctl') . '</a>';
									}
								echo '</div>';
									
								echo '</td>';
							break;
					}?>
				<?php }?>
			</tr>
		<?php }?>
	<?php }?>
		</tbody>
	</table>
</div>
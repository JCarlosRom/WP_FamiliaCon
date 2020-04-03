<?php
	global $otw_pctl_validate_messages;
	
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
	<h2><?php echo $page_title ?>
		<a class="button add-new-h2" href="admin.php?page=otw-pctl"><?php _e('Back to custom templates list', 'otw_pctl') ?></a>
	</h2>
	<?php include_once( 'otw_pctl_help.php' ); ?>
	<?php if( isset( $otw_pctl_validate_messages ) && count( $otw_pctl_validate_messages ) ){?>
		<div id="message" class="error">
			<?php foreach( $otw_pctl_validate_messages as $v_message ){
				echo '<p>'.$v_message.'</p>';
			}?>
		</div>
	<?php }?>
	<div id="poststuff">
		<form method="post" action="" class="validate">
			<input type="hidden" name="otw_pctl_action" value="manage_otw_pctl_custom_templates" />
			<?php wp_original_referer_field(true, 'previous'); wp_nonce_field('otw-pctl-manage'); ?>
			<div id="post-body">
				<div  class="form-wrap">
					<div class="form-field form-required">
						<label for="pctl_custom_template_title"><?php _e( 'Single Posts Template title', 'otw_pctl' );?></label>
						<input type="text" id="pctl_custom_template_title" value="<?php echo $otw_pctl_custom_template_values['title']?>" tabindex="1" size="30" name="pctl_custom_template_title"/>
						<p><?php _e( 'The name is how it appears on your site.', 'otw_pctl' );?></p>
					</div>
					<div id="<?php echo $otw_pctl_grid_manager_object->meta_name;?>">
						<div class="meta-box-sortables">
							<div class="postbox">
								<h3 class="hndle sitem_header"><span><?php _e( 'OTW Grid Manager', 'otw_pctl' )?></span></h3>
								<div class="inside">
									<?php echo $otw_pctl_grid_manager_object->build_custom_box( $otw_pctl_custom_template_values['grid_content'] );?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Save Single Posts Template', 'otw_pctl') ?>" name="submit" class="button button-primary button-hero"/>
				</p>
				<?php include_once( 'otw_pctl_custom_template_settings.php' )?>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Save Single Posts Template', 'otw_pctl') ?>" name="submit" class="button button-primary button-hero"/>
				</p>
			</div>
		</form>
	</div>
</div>
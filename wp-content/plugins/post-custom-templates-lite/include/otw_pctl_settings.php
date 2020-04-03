<?php

global $otw_pctl_plugin_id;


$db_values = array();
$db_values['otw_pct_promotions'] = get_option( $otw_pctl_plugin_id.'_dnms' );

if( empty( $db_values['otw_pct_promotions'] ) ){
	$db_values['otw_pct_promotions'] = 'on';
}


$message = '';
$massages = array();
$messages[1] = __( 'Options saved', 'otw_pctl' );

if( isset( $_GET['message'] ) && isset( $messages[ $_GET['message'] ] ) ){
	$message .= $messages[ $_GET['message'] ];
}
?>
<?php if ( $message ) : ?>
<div id="message" class="updated"><p><?php echo $message; ?></p></div>
<?php endif; ?>
<div class="wrap">
	<div id="icon-edit" class="icon32"><br/></div>
	<h2>
		<?php _e('Plugin Options', 'otw_pctl') ?>
	</h2>
	<?php include_once( 'otw_pctl_help.php' ); ?>
	<form name="otw-pctl-list-style" method="post" action="" class="validate otw-pctl-options-form">
		<h3><?php _e('Post Template Selection', 'otw_pctl'); ?></h3>
		<div class="otw_pctl_sp_settings" id="otw_pctl_settings">
			<table class="form-table">
				<tr>
					<th scope="row"><label for="otw_pct_template"><?php _e('Single post template', 'otw_pctl'); ?></label></th>
					<td>
						<select id="otw_pct_template" name="otw_pct_template">
						<?php foreach( $otw_pct_templates as $template_key => $template_name ){?>
							<?php
								$selected = '';
								if( isset( $otw_pct_plugin_options['otw_pct_template'] ) && ( $otw_pct_plugin_options['otw_pct_template'] == $template_key ) ){
									$selected = ' selected="selected"';
								}
							?>
							<?php if( $template_key == '-' ){ ?>
								<option disabled="disabled">------------------------------------------</option>
							<?php }else{ ?>
								<option value="<?php echo $template_key?>"<?php echo $selected?>><?php echo $template_name?></option>
							<?php } ?>
						<?php }?>
						</select>
						<p class="description"><?php _e( 'This is the template that applies to all of your single posts.', 'otw_pctl' )?></p>
					</td>
				</tr>
			</table>
		</div>
		<div>
			<p class="submit">
				<input type="submit" value="<?php _e( 'Save', 'otw_pctl') ?>" name="submit" class="button button-primary button-hero"/>
			</p>
		</div>
		<h3><?php _e('Promotion messages', 'otw_pctl'); ?></h3>
		<div class="otw_pctl_sp_settings" >
			<div class="form-field">
				<label for="otw_pct_promotions"><?php _e('Show OTW Promotion Messages in my WordPress admin', 'otw_pctl'); ?></label>
				<select id="otw_pct_promotions" name="otw_pct_promotions">
					<option value="on" <?php echo ( isset( $db_values['otw_pct_promotions'] ) && ( $db_values['otw_pct_promotions'] == 'on' ) )? 'selected="selected"':''?>>on(default)</option>
					<option value="off"<?php echo ( isset( $db_values['otw_pct_promotions'] ) && ( $db_values['otw_pct_promotions'] == 'off' ) )? 'selected="selected"':''?>>off</option>
				</select>
			</div>
		</div>
		<h3><?php _e('Custom CSS', 'otw_pctl'); ?></h3>
		<p class="description"><?php _e('Adjust your own CSS for all of your templates. Please use with caution.', 'otw_pctl'); ?></p>
		<div>
			<textarea name="otw_pctl_custom_css" cols="100" rows="35" class="otw-pctl-custom-css"><?php echo $customCss;?></textarea>
			<p class="submit">
				<input type="hidden" name="otw_pctl_save_settings" value="1" />
				<input type="hidden" name="otw_pctl_action" value="manage_otw_pctl_options" />
				<?php wp_original_referer_field(true, 'previous'); wp_nonce_field('otw-pctl-options'); ?>
				<input type="submit" value="<?php _e( 'Save', 'otw_pctl') ?>" name="submit" class="button button-primary button-hero"/>
			</p>
		</div>
	</form>
</div>

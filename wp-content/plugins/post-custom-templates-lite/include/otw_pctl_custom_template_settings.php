<div class="meta-box-sortables otw_pctl_ct_settings">
	<?php $otw_pctl_content_sidebars_object->content_sidebars_standalone( 'pct', $otw_pctl_custom_template_values['cs'] );?>
	<div class="postbox">
		<h3 class="hndle sitem_header"><span><?php _e( 'OTW Post Template Options.', 'otw_pctl' )?></span></h3>
		<div class="inside">
			<table class="form-table">
				<?php if( count( $meta_elements  ) ){?>
				<tr class="otw_pct_template_related_settings">
					<th scope="row">
						<label for="meta_order"><?php _e('Meta Elements and Order', 'otw_pctl');?></label>
					</th>
					<td>
						<div class="active_elements">
							<h3><?php _e('Active Elements', 'otw_pctl');?></h3>
							<input type="hidden" name="otw_pct_meta_items" class="js-meta-items" value="<?php echo $otw_pctl_custom_template_values['options']['otw_pct_meta_items'];?>"/>
							<ul class="b-meta-box js-meta-active" style="height: <?php echo $meta_elements_height?>px;">
							</ul>
						</div>
						<div class="inactive_elements">
							<h3><?php _e('Inactive Elements', 'otw_pctl');?></h3>
							<ul class="b-meta-box js-meta-inactive" style="height: <?php echo $meta_elements_height?>px;">
							<?php foreach( $meta_elements as $meta_element_key => $meta_element_name ){?>
								<li data-item="meta" data-value="<?php echo $meta_element_key?>" class="b-meta-items js-meta--item"><?php echo $meta_element_name?></li>
							<?php }?>
							</ul>
						</div>
						<p class="description"><?php _e('Drag & drop the items that you\'d like to show in the Active Elements area on the left. Arrange them however you want to see them.', 'otw_pctl');?></p>
					</td>
				</tr>
				<?php }?>
				<tr class="otw_pct_template_related_settings">
					<th scope="row"><label for="otw_pct_prev_next_nav"><?php _e('Enable Previous / Next Post Navigation', 'otw_pctl'); ?></label></th>
					<td>
						<select id="otw_pct_prev_next_nav" name="otw_pct_prev_next_nav">
						<?php foreach( $otw_pct_prev_next_nav_options as $key => $name ){?>
							<?php
								$selected = '';
								if( isset( $otw_pctl_custom_template_values['options']['otw_pct_prev_next_nav'] ) && ( $otw_pctl_custom_template_values['options']['otw_pct_prev_next_nav'] == $key ) ){
									$selected = ' selected="selected"';
								}
							?>
							<option value="<?php echo $key?>"<?php echo $selected?>><?php echo $name?></option>
						<?php }?>
						</select>
						<p class="description"><?php _e( 'Enable Previous / Next Post Navigation for single post page.', 'otw_pctl' )?></p>
					</td>
				</tr>
				<tr class="otw_pct_template_related_settings">
					<th scope="row"><label for="otw_pct_related_posts"><?php _e('Enable Related Posts', 'otw_pctl'); ?></label></th>
					<td>
						<select id="otw_pct_related_posts" name="otw_pct_related_posts">
						<?php foreach( $otw_pct_related_posts_options as $key => $name ){?>
							<?php
								$selected = '';
								if( isset( $otw_pctl_custom_template_values['options']['otw_pct_related_posts'] ) && ( $otw_pctl_custom_template_values['options']['otw_pct_related_posts'] == $key ) ){
									$selected = ' selected="selected"';
								}
							?>
							<option value="<?php echo $key?>"<?php echo $selected?>><?php echo $name?></option>
						<?php }?>
						</select>
						<p class="description"><?php _e( 'Enable Related Posts.', 'otw_pctl' )?></p>
					</td>
				</tr>
				<tr class="otw_pct_template_related_settings">
					<th scope="row"><label for="otw_pct_related_posts_criteria"><?php _e('Related Post Criteria', 'otw_pctl'); ?></label></th>
					<td>
						<select id="otw_pct_related_posts_criteria" name="otw_pct_related_posts_criteria">
						<?php foreach( $otw_pct_related_posts_criteria_options as $key => $name ){?>
							<?php
								$selected = '';
								if( isset( $otw_pctl_custom_template_values['options']['otw_pct_related_posts_criteria'] ) && ( $otw_pctl_custom_template_values['options']['otw_pct_related_posts_criteria'] == $key ) ){
									$selected = ' selected="selected"';
								}
							?>
							<option value="<?php echo $key?>"<?php echo $selected?>><?php echo $name?></option>
						<?php }?>
						</select>
						<p class="description"><?php _e( 'Select the Related Post Criteria', 'otw_pctl' )?></p>
					</td>
				</tr>
				<tr class="otw_pct_template_related_settings">
					<th scope="row"><label for="otw_pct_social_title_text"><?php _e('Social Icons Title Text', 'otw_pctl'); ?></label></th>
					<td>
						<input type="text" name="otw_pct_social_title_text" id="otw_pct_social_title_text" value="<?php echo $otw_pctl_custom_template_values['options']['otw_pct_social_title_text']?>" />
						<p class="description"><?php _e( 'Enter custom Social Icons Title Text. If empty "Hey, like this? Why not share it with a buddy?" will be used. If you enter a "space" no title will be displayed.', 'otw_pctl' )?></p>
					</td>
				</tr>
				<tr class="otw_pct_template_related_settings">
					<th scope="row"><label for="otw_pct_related_title_text"><?php _e('Related posts Title Text', 'otw_pctl'); ?></label></th>
					<td>
						<input type="text" name="otw_pct_related_title_text" id="otw_pct_related_title_text" value="<?php echo $otw_pctl_custom_template_values['options']['otw_pct_related_title_text']?>" />
						<p class="description"><?php _e( 'Enter custom Related posts Title Text. If empty "Related posts" will be used. If you enter a "space" no title will be displayed.', 'otw_pctl' )?></p>
					</td>
				</tr>
				<tr valign="top" class="otw_pct_template_related_settings">
					<th scope="row">
						<label for="show-social-icons"><?php _e('Show Social Icons', 'otw_pctl');?></label>
					</th>
					<td>
						<?php
							$yes = ''; $no = ''; 
							($otw_pctl_custom_template_values['options']['otw_pct_show_social_icons'])? $yes = 'checked="checked"' : $no = 'checked="checked"'; 
						?>
						<select id="show-social-icons" name="otw_pct_show_social_icons">
							<?php 
								foreach( $otw_pct_social_icons_options as $optionData ){
									$selected = '';
									if( $optionData['value'] === $otw_pctl_custom_template_values['options']['otw_pct_show_social_icons'] ) {
										$selected = 'selected="selected"';
									}
									echo "<option value=\"".$optionData['value']."\" ".$selected.">".$optionData['text']."</option>";
								}
							?>
						</select>
						<p class="description">
							<?php
								_e("Social Icons will make your posts easy to share in social networks. Note that to use 
									\"Share buttons small\" and \"Share buttons large\" you need to have CURL installed on your server.
									", 'otw_pctl');
							?>
						</p>
					</td>
				</tr>
				<tr id="otw-show-social-icons-type" class="otw_pct_template_related_settings">
					<th scope="row">
						<label for="otw_pct_show_social_icons_type"><?php _e('Select Social Icons', 'otw_pctl');?></label>
					</th>
					<td>
						<input type="checkbox" id="otw_pct_show_social_icons_type" name="otw_pct_show_social_icons_facebook" value="1" <?php echo ( $otw_pctl_custom_template_values['options']['otw_pct_show_social_icons_facebook'] )?' checked="checked"':''?> /><label for="otw_pct_show_social_icons_type"><?php _e('Facebook', 'otw_pctl');?></label>
						<input type="checkbox" id="otw_pct_show_social_icons_twitter" name="otw_pct_show_social_icons_twitter" value="1" <?php echo ( $otw_pctl_custom_template_values['options']['otw_pct_show_social_icons_twitter'] )?' checked="checked"':''?>/><label for="otw_pct_show_social_icons_twitter"><?php _e('Twitter', 'otw_pctl');?></label>
						<input type="checkbox" id="otw_pct_show_social_icons_googleplus" name="otw_pct_show_social_icons_googleplus" value="1" <?php echo ( $otw_pctl_custom_template_values['options']['otw_pct_show_social_icons_googleplus'] )?' checked="checked"':''?>/><label for="otw_pct_show_social_icons_googleplus"><?php _e('Google+', 'otw_pctl');?></label>
						<input type="checkbox" id="otw_pct_show_social_icons_linkedin" name="otw_pct_show_social_icons_linkedin" value="1" <?php echo ( $otw_pctl_custom_template_values['options']['otw_pct_show_social_icons_linkedin'] )?' checked="checked"':''?>/><label for="otw_pct_show_social_icons_linkedin"><?php _e('LinkedIn', 'otw_pctl');?></label>
						<input type="checkbox" id="otw_pct_show_social_icons_pinterest" name="otw_pct_show_social_icons_pinterest" value="1" <?php echo ( $otw_pctl_custom_template_values['options']['otw_pct_show_social_icons_pinterest'] )?' checked="checked"':''?>/><label for="otw_pct_show_social_icons_pinterest"><?php _e('Pinterest', 'otw_pctl');?></label>
						<p class="description"><?php _e( 'Select the social icons that will be displayed.', 'otw_pctl');?></p>
					</td>
				</tr>
				<tr id="otw-show-social-icons-custom" class="otw_pct_template_related_settings">
					<th scope="row">
						<label for="otw_pct_show_social_icons_custom"><?php _e('Custom Social Icons', 'otw_pctl');?></label>
					</th>
					<td>
						<textarea id="otw_pct_show_social_icons_custom" name="otw_pct_show_social_icons_custom" rows="6" cols="80"><?php echo ( $otw_pctl_custom_template_values['options']['otw_pct_show_social_icons_custom'] )?></textarea>
						<p class="description"><?php _e( 'Insert your Custom Social Icons. HTML and Shortcodes are allowed.', 'otw_pctl');?></p>
					</td>
				</tr>
				<tr class="otw_pct_template_related_settings">
					<th scope="row"><label for="otw_pct_item_media_width"><?php _e('Media Width', 'otw_pctl'); ?></label></th>
					<td>
						<input type="text" name="otw_pct_item_media_width" id="otw_pct_item_media_width" value="<?php echo $otw_pctl_custom_template_values['options']['otw_pct_item_media_width']?>" />
						<p class="description"><?php _e( 'Default 650px.', 'otw_pctl' )?></p>
					</td>
				</tr>
				<tr class="otw_pct_template_related_settings">
					<th scope="row"><label for="otw_pct_item_media_height"><?php _e('Media Height', 'otw_pctl'); ?></label></th>
					<td>
						<input type="text" name="otw_pct_item_media_height" id="otw_pct_item_media_height" value="<?php echo $otw_pctl_custom_template_values['options']['otw_pct_item_media_height']?>" />
						<p class="description"><?php _e( 'Default 580px.', 'otw_pctl' )?></p>
					</td>
				</tr>
				<tr class="otw_pct_template_related_settings">
					<th scope="row"><label for="otw_pct_related_media_width"><?php _e('Related Posts Media Width', 'otw_pctl'); ?></label></th>
					<td>
						<input type="text" name="otw_pct_related_media_width" id="otw_pct_related_media_width" value="<?php echo $otw_pctl_custom_template_values['options']['otw_pct_related_media_width']?>" />
						<p class="description"><?php _e( 'Default 220px.', 'otw_pctl' )?></p>
					</td>
				</tr>
				<tr class="otw_pct_template_related_settings">
					<th scope="row"><label for="otw_pct_related_media_height"><?php _e('Related Posts Media Height', 'otw_pctl'); ?></label></th>
					<td>
						<input type="text" name="otw_pct_related_media_height" id="otw_pct_related_media_height" value="<?php echo $otw_pctl_custom_template_values['options']['otw_pct_related_media_height']?>" />
						<p class="description"><?php _e( 'Default 150px.', 'otw_pctl' )?></p>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
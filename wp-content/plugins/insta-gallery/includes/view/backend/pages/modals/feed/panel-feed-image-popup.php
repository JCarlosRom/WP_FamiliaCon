<div id="tab_panel_feed_image_popup" class="panel qligg_options_panel <# if (data.panel != 'tab_panel_feed_image_popup') { #>hidden<# } #>" >

  <div class="options_group"> 
    <p class="form-field"> 
      <label><?php esc_html_e('Images popup', 'insta-gallery'); ?></label>
      <input class="media-modal-render-panels" name="popup[display]" type="checkbox" value="true" <# if (data.popup.display){ #>checked<# } #>/>
             <span class="description"><small><?php esc_html_e('Display popup gallery by clicking on image', 'insta-gallery'); ?></small></span>
    </p>
  </div>
 
  <div class="options_group qligg-premium-field <# if (!data.popup.display){ #>disabled-color-picker<# } #>">
       <p class="form-field"> 
      <label><?php esc_html_e('Images popup profile', 'insta-gallery'); ?></label> 
      <input name="popup[profile]" type="checkbox" value="true" <# if (data.popup.profile){ #>checked<# } #> />
             <span class="description"><small><?php esc_html_e('Display user profile or tag info', 'insta-gallery'); ?></small></span>
      <span class="description hidden"><small><?php esc_html_e('(This is a premium feature)', 'insta-gallery'); ?></small></span>
    </p>
  </div>
  
  <div class="options_group qligg-premium-field <# if (!data.popup.display){ #>disabled-color-picker<# } #>">
    <p class="form-field"> 
      <label><?php esc_html_e('Images popup caption', 'insta-gallery'); ?></label>
      <input name="popup[caption]" type="checkbox" value="true" <# if (data.popup.caption){ #>checked<# } #> /> 
             <span class="description"><small><?php esc_html_e('Display caption in the popup', 'insta-gallery'); ?></small></span>
      <span class="description hidden"><small><?php esc_html_e('(This is a premium feature)', 'insta-gallery'); ?></small></span>
    </p>
  </div>

  <div class="options_group qligg-premium-field <# if (!data.popup.display){ #>disabled-color-picker<# } #>">
    <p class="form-field"> 
      <label><?php esc_html_e('Images popup likes', 'insta-gallery'); ?></label>
      <input name="popup[likes]" type="checkbox" value="true" <# if (data.popup.likes){ #>checked<# } #>/>
             <span class="description"><small><?php esc_html_e('Display likes count of images', 'insta-gallery'); ?></small></span>
      <span class="description hidden"><small><?php esc_html_e('(This is a premium feature)', 'insta-gallery'); ?></small></span>
    </p>
  </div>

  <div class="options_group qligg-premium-field <# if (!data.popup.display){ #>disabled-color-picker<# } #>">
    <p class="form-field"> 
      <label><?php esc_html_e('Images popup align', 'insta-gallery'); ?></label>
      <select name="popup[align]">
              <option value="top" <?php selected('top', $feed['popup']['align']); ?>><?php esc_html_e('Top', 'insta-gallery'); ?></option>
        <option <# if ( data.popup.align == 'left') { #>selected="selected"<# } #> value="left"><?php esc_html_e('Left', 'insta-gallery'); ?> </option>
        <option <# if ( data.popup.align == 'right') { #>selected="selected"<# } #> value="right"><?php esc_html_e('Right', 'insta-gallery'); ?> </option>
        <option <# if ( data.popup.align == 'bottom') { #>selected="selected"<# } #> value="bottom"><?php esc_html_e('Bottom', 'insta-gallery'); ?> </option>
        <option <# if ( data.popup.align == 'top') { #>selected="selected"<# } #> value="top"><?php esc_html_e('Top', 'insta-gallery'); ?> </option>
      </select>
      <span class="description"><small><?php esc_html_e('Display likes count of images', 'insta-gallery'); ?></small></span>
      <span class="description hidden"><small><?php esc_html_e('(This is a premium feature)', 'insta-gallery'); ?></small></span>
    </p>
  </div>

</div>
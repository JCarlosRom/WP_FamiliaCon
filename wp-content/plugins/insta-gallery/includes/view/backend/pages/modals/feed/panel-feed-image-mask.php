<div id="tab_panel_feed_image_mask" class="panel qligg_options_panel <# if (data.panel != 'tab_panel_feed_image_mask') { #>hidden<# } #>" >

  <div class="options_group">
    <p class="form-field">
      <label><?php esc_html_e('Images mask', 'insta-gallery'); ?></label>
      <input class="media-modal-render-panels" name="mask[display]" type="checkbox" value="true" <# if (data.mask.display){ #>checked<# } #>/>
             <span class="description"><small><?php esc_html_e('Image mouseover effect', 'insta-gallery'); ?></small></span>
    </p>
  </div>

  <div class="options_group <# if (!data.mask.display){ #>disabled-color-picker<# } #>">
    <p class="form-field">
      <label><?php esc_html_e('Images mask color', 'insta-gallery'); ?></label>
      <input data-alpha="true" name="mask[background]"  type="text"  placeholder="#007aff" value="{{data.mask.background}}" class="color-picker"/>
       
             <span class="description"><small><?php esc_html_e('Color which is displayed when displayed over images', 'insta-gallery'); ?></small></span>
    </p>
  </div>

  <div class="options_group <# if (!data.mask.display){ #>disabled-color-picker<# } #>">
    <p class="form-field">
      <label><?php esc_html_e('Images mask likes', 'insta-gallery'); ?></label>
      <input name="mask[likes]" type="checkbox" value="true" <# if (data.mask.likes ){ #>checked<# } #>/>
             <span class="description"><small><?php esc_html_e('Display likes count of images', 'insta-gallery'); ?></small></span>
    </p>
  </div>

  <div class="options_group <# if (!data.mask.display){ #>disabled-color-picker<# } #>">
    <p class="form-field">
      <label><?php esc_html_e('Images mask comments', 'insta-gallery'); ?></label>
      <input name="mask[comments]" type="checkbox" value="true" <# if (data.mask.comments ){ #>checked<# } #>/>
             <span class="description"><small><?php esc_html_e('Display comments count of images', 'insta-gallery'); ?></small></span>
    </p>
  </div>

</div>
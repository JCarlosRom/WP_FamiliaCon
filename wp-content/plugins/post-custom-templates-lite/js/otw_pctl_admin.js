jQuery(document).ready(function(){
	
	otw_pctl_init_settings_page();
	
	otw_pctl_init_custom_template_page();
	
	otw_pctl_init_post_page();
});

function otw_pctl_init_settings_page(){

	if( jQuery( '#otw_pctl_settings' ).size() ){
	
		otw_pctl_init_template_options();
	};
};

function otw_pctl_init_custom_template_page(){

	if( jQuery( '.otw_pctl_ct_settings' ).size() ){
		otw_pctl_init_custom_template_options();
	}
}

function otw_pctl_init_post_page(){
	
	if( jQuery( '#otw-bm-meta-box' ).size() ){
		otw_pctl_init_media_meta_box();
	}
	if( jQuery( '#otw-mbp-options-meta-box' ).size() ){
		otw_pctl_init_template_options();
		
		if( jQuery( '#otw_pct_options_type' ).size() ){
			
			otw_pctl_set_options_metabox();
			
			jQuery( '#otw_pct_options_type' ).change( function(){ otw_pctl_set_options_metabox() } )
		}
	};
	
	otw_pctl_init_tabs_metabox();
	
	otw_pctl_init_reviews_metabox();
	
	otw_pctl_init_post_custom_fields_metabox();
};

function otw_pctl_init_reviews_metabox(){
	
	if( jQuery( '#otw_pct_add_review' ).size() ){
		
		jQuery( '#otw_pct_add_review' ).click( function(){
			
			
			var table = jQuery( '#otw_mb_reviews_table' );
			
			var new_table = jQuery( '#otw_mb_reviews_add_table' );
			var new_table_rows = new_table.find( 'tr' );
			
			/*find the number of the last row*/
			var last_area = table.find( 'tr:last' ).find( 'select' );
			
			if( last_area.size() ){
				var matches = false;
				if( matches = last_area.attr( 'id' ).match( /^otw_mb_review_rate_(\d+)$/ ) ){
					var new_row_id = Number( matches[1] ) + 1;
				}
			}else{
				
				var new_row_id = 0;
			}
			for( var cR = 0; cR < new_table_rows.length; cR++ ){
				
				new_row = jQuery( new_table_rows[ cR ] ).clone();
				new_row.hide();
				
				var labels = new_row.find( 'label' );
				
				for( var cL = 0; cL < labels.length; cL++ ){
					jQuery( labels[cL] ).attr( 'for',  jQuery( labels[cL] ).attr( 'for' ).replace( /next_row_id/, new_row_id ) );
				}
				
				var controls = new_row.find( 'select,input' );
				
				for( var cL = 0; cL < controls.length; cL++ ){
					
					var control = jQuery( controls[cL] );
					
					control.attr( 'id', control.attr( 'id' ).replace( /next_row_id/, new_row_id ) );
					control.attr( 'name', control.attr( 'name' ).replace( /next_row_id/, new_row_id ) );
					
					if( control.attr( 'name' ).match( /rate/ ) ){
						control.val( 50 );
					}else{
						control.val( '' );
					}
				}
				
				table.find( 'tbody' ).append( new_row );
				new_row.fadeIn();
			};
		});
	};
};

function otw_pctl_init_post_custom_fields_metabox(){
	
	if( jQuery( '#otw_pct_add_post_custom_field' ).size() ){
		
		jQuery( '#otw_pct_add_post_custom_field' ).click( function(){
			
			
			var table = jQuery( '#otw_post_custom_fields_table' );
			
			var new_table = jQuery( '#otw_post_custom_fields_add_table' );
			var new_table_rows = new_table.find( 'div.otw_post_custom_fields_table_row' );
			
			/*find the number of the last row*/
			var new_row_id = 1;
			var new_row_order = 1;
			
			var table_rows = table.find( 'div.otw_post_custom_fields_table_row' );
			
			if( table_rows.size() ){
				
				for( var cR = 0; cR < table_rows.length; cR++ ){
					
					
					var inputs = jQuery( table_rows[ cR ] ).find( 'input[type=text]' );
					
					for( var cL = 0; cL < inputs.length; cL++ ){
					
						var matches = false;
						if( matches = inputs[cL].id.match( /^otw_post_custom_field_order_([0-9]+)$/ ) ){
							
							if( matches[1] > new_row_id ){
								new_row_id = matches[1];
							}
							if( jQuery( inputs[cL] ).val() > new_row_order ){
								new_row_order = jQuery( inputs[cL] ).val();
							}
						}
					}
				}
				new_row_id++;
				new_row_order++;
			}
			
			
			
			for( var cR = 0; cR < new_table_rows.length; cR++ ){
				
				new_row = jQuery( new_table_rows[ cR ] ).clone();
				new_row.hide();
				
				var labels = new_row.find( 'label' );
				
				for( var cL = 0; cL < labels.length; cL++ ){
					jQuery( labels[cL] ).attr( 'for',  jQuery( labels[cL] ).attr( 'for' ).replace( /next_row_id/, new_row_id ) );
				}
				
				var controls = new_row.find( 'input' );
				
				for( var cL = 0; cL < controls.length; cL++ ){
					
					var control = jQuery( controls[cL] );
					
					control.attr( 'id', control.attr( 'id' ).replace( /next_row_id/, new_row_id ) );
					control.attr( 'name', control.attr( 'name' ).replace( /next_row_id/, new_row_id ) );
					
					if( control.attr( 'name' ).match( /order/ ) ){
						
						control.val( new_row_order );
					}else{
						control.val( '' );
					}
					
					
				}
				
				table.append( new_row );
				new_row.fadeIn();
				otw_pctl_remove_post_custom_fields();
			};
		});
	};
	
	otw_pctl_remove_post_custom_fields();
	
	jQuery( '#otw_post_custom_fields_table' ).sortable({
		placeholder: "otw_post_custom_fields_table_row_placeholder",
		start: function(e, ui){
			ui.placeholder.height(ui.item.height());
		},
		update: function( event, ui ){
			
			var sortable_items = jQuery( '#otw_post_custom_fields_table > .otw_post_custom_fields_table_row .otw_post_custom_item_order' );
			
			for( var cS = 0; cS < sortable_items.size(); cS++ ){
				jQuery( sortable_items[cS] ).val( cS + 1 );
				jQuery( sortable_items[cS] ).change();
			};
		}
	});
};

function otw_pctl_remove_post_custom_fields(){
	
	jQuery( '#otw_post_custom_fields_table .otw_post_custom_field_remove' ).unbind( 'click' );
	jQuery( '#otw_post_custom_fields_table .otw_post_custom_field_remove' ).click( function(){
		jQuery( this ).parents( '.otw_post_custom_fields_table_row' ).first().remove();
	} );
}

function otw_pctl_init_tabs_metabox(){
	
	if( jQuery( '#otw_pct_add_tab' ).size() ){
		
		jQuery( '#otw_pct_add_tab' ).click( function(){
			
			
			var table = jQuery( '#otw_pct_tabs_table' );
			
			var new_table = jQuery( '#otw_pct_tabs_add_table' );
			var new_table_rows = new_table.find( 'tr' );
			
			/*find the number of the last row*/
			var last_area = table.find( 'tr:last' ).find( 'textarea' );
			
			if( last_area.size() ){
				var matches = false;
				if( matches = last_area.attr( 'id' ).match( /^otw_mb_tab_content_(\d+)$/ ) ){
					var new_row_id = Number( matches[1] ) + 1;
				}
			}else{
				
				var new_row_id = 0;
			}
			for( var cR = 0; cR < new_table_rows.length; cR++ ){
				
				new_row = jQuery( new_table_rows[ cR ] ).clone();
				new_row.hide();
				
				var labels = new_row.find( 'label' );
				
				for( var cL = 0; cL < labels.length; cL++ ){
					jQuery( labels[cL] ).attr( 'for',  jQuery( labels[cL] ).attr( 'for' ).replace( /next_row_id/, new_row_id ) );
				}
				
				var controls = new_row.find( 'textarea,input' );
				
				for( var cL = 0; cL < controls.length; cL++ ){
					
					var control = jQuery( controls[cL] );
					
					control.attr( 'id', control.attr( 'id' ).replace( /next_row_id/, new_row_id ) );
					control.attr( 'name', control.attr( 'name' ).replace( /next_row_id/, new_row_id ) );
					control.val( '' );
				}
				
				table.find( 'tbody' ).append( new_row );
				new_row.fadeIn();
			};
		});
	};
};

function otw_pctl_set_options_metabox(){
	
	if( jQuery( '#otw_pct_options_type' ).val() == 'custom' ){
		
		jQuery( '#otw_pct_custom_options_table' ).show();
	}else{
	
		jQuery( '#otw_pct_custom_options_table' ).hide();
	}
};


function otw_pctl_media_type(){

	var mediaType = jQuery( '.js-otw-media-type' ).val();
	
	jQuery('.js-meta-youtube').hide();
	jQuery('.js-meta-vimeo').hide();
	jQuery('.js-meta-soundcloud').hide();
	jQuery('.js-meta-image').hide();
	jQuery('.js-meta-slider').hide();
	
	switch ( mediaType ) {
		case 'youtube':
			jQuery('.js-meta-youtube').fadeIn();
		break;
		case 'vimeo':
			jQuery('.js-meta-vimeo').fadeIn();
		break;
		case 'soundcloud':
			jQuery('.js-meta-soundcloud').fadeIn();
		break;
		case 'img':
			jQuery('.js-meta-image').fadeIn();
		break;
		case 'slider':
			jQuery('.js-meta-slider').fadeIn();
		break;
	}
}

function otw_pctl_init_media_meta_box(){
	
	var frameUpload;
	
	// Translation enabled messages
	var $messages = JSON.parse( messages );
	
	otw_pctl_media_type();
	
	jQuery('.js-otw-media-type').on('change', function(e) {
		
		otw_pctl_media_type();
	});
	
	/**
	 * Make Slider Elements Sortable
	 */
	jQuery('.js-meta-slider-preview').sortable({
		update: function( event, ui ) {
			otw_pctl_update_slider_assets();
		}
	});
	
	/**
	 * Add functionality to delete images from slider
	 */
	jQuery(document).on('click', '.b-delete_btn', function(e) {
		e.preventDefault();
		
		// Get current selected item
		item = jQuery(this).parent();

		//Remove item from the list
		jQuery(item).remove();

		// Update assets list
		otw_pctl_update_slider_assets();
	});
	
	/**
	 * Add Functionality for WordPress Media Upload
	 */
	jQuery(document).on('click', '.js-add-image', function(e) {
		e.preventDefault();
		/**
		 * WordPress Based Media Selection and Upload (Images)
		 * Used for Post Meta information: Images and Slider Images
		 */

		if( frameUpload ) {
			frameUpload.open();
			return;
		}

		frameUpload = wp.media({
			id: 'otw-mbp-media-upload',
			// Set the title of the modal.
			title: $messages['modal_title'],
			multiple: false,
			// Tell the modal to show only images.
			library: {
				type: 'image'
			},
			// Customize the submit button.
			button: {
				// Set the text of the button.
				text: $messages['modal_btn'],
				// Change close: false, in order to prevent window to close on selection
				close: true
			}
		});

		frameUpload.on( 'select', function() {
			var attachements = frameUpload.state().get('selection').first().id;
			var attachementURL = wp.media.attachment( attachements ).attributes.url;

			if( jQuery('.js-otw-media-type').val() === 'slider' ) {

				imgTAG = '<li class="b-slider__item" data-src="'+attachementURL+'">';
				imgTAG += '<a href="#" class="b-delete_btn"></a>';
				imgTAG += '<img src="'+attachementURL+'" width="100" />';
				imgTAG += '</li>';
				
				jQuery('.js-meta-slider-preview').append( imgTAG ); //Display IMG
				otw_pctl_update_slider_assets();

			} else {
				// Create HTML for visual effect
				var imgTAG = '<img src="'+attachementURL+'" width="150" />';
				// Append HTML for visual preview
				jQuery('.js-img-preview').html( imgTAG ); //Display IMG

				// Add Image to Hidden input - save to DB
				jQuery('.js-img-url').val( attachementURL );
			}

		})

		frameUpload.open();
	});
};

function otw_pctl_update_slider_assets(){
	
	var imagesArray = new Array();
	jQuery('.b-slider-preview > .b-slider__item').each(function( item, value) {
		imagesArray.push( jQuery(value).data('src') );
	});

	// Add Array to hidden input
	jQuery('.js-img-slider-url').val( imagesArray );
}

function otw_pctl_init_template_options(){
	
	if( jQuery( '#otw_pct_template' ).size() ){
		
		jQuery( '#otw_pct_template' ).change( function(){
			otw_pct_set_template_options();
		} );
		
		otw_pct_set_template_options();
		
		otw_pct_init_category_templates();
	};
};

function otw_pctl_init_custom_template_options(){
	
	jQuery( '#show-social-icons' ).change( function(){
		otw_pct_set_social_related_controlers();
	});
	otw_pct_set_social_related_controlers();
	
	otw_pct_set_sortable_controls();
}

function otw_pct_set_sortable_controls(){
	
	var metaElements = jQuery('.js-meta-items').val();
	
	if( typeof metaElements !== 'undefined' ) {
		metaItems = metaElements.split(',');

		jQuery(metaItems).each( function( item, value ) {

			jQuery('.js-meta-inactive > .js-meta--item').each( function( miItem, miValue )  {

				if( jQuery(miValue).data('value') === value ) {
					jQuery('.js-meta-active').append( miValue );
				}
			});

		});

	}
	/**
	 * Interface for Meta Elements
	 * Drag & Drop support + Sortable Support
	 */
	jQuery('.js-meta-active, .js-meta-inactive').sortable({
		connectWith: ".b-meta-box",
		update: function( event, ui ) {
			otw_pct_update_sortable_elements();
		},
		stop: function( event, ui ) {
			jQuery.event.trigger({
				type: "metaEvent"
			});
		}
	});
}

function otw_pct_set_template_options(){

	if( jQuery( '#otw_pct_template' ).val().match( /\-sidebar$/ ) ){
		jQuery( '#otw_pct_template_sidebar' ).fadeIn();
	}else{
		jQuery( '#otw_pct_template_sidebar' ).hide();
	}
	
	if( jQuery( '#otw_pct_template' ).val() == 'default' ){
		jQuery( '.otw_pct_template_related_settings' ).hide();
	}else{
		jQuery( '.otw_pct_template_related_settings' ).fadeIn();
		otw_pct_set_social_related_controlers();
		if( jQuery( '#otw_pct_template' ).val().match( /\-sidebar$/ ) ){
			jQuery( '#otw_pct_template_sidebar' ).fadeIn();
		}else{
			jQuery( '#otw_pct_template_sidebar' ).hide();
		}
	}
};

function otw_pct_init_category_templates(){
	
	var text_value_object = jQuery( '#otw_pct_category_template' );
	
	otw_pct_setting_form_sidebars( 'otw_new_ct_template' );
	
	if( text_value_object.size() ){
		
		var value_object = new Array();
		
		if( text_value_object.val().length ){
			value_object = jQuery.parseJSON( text_value_object.val() );
		}
		
		if( jQuery( '#otw_add_category_template' ).size() ){
		
			jQuery( '#otw_new_ct_category' ).val( '' );
			jQuery( '#otw_new_ct_template' ).val( 'default' );
			
			jQuery( '#otw_new_ct_template').change( function(){
				otw_pct_setting_form_sidebars( 'otw_new_ct_template' );
			} );
			
			jQuery( '#otw_add_category_template' ).click( function(){
				
				if( jQuery( '#otw_new_ct_category').val() && jQuery( '#otw_new_ct_template').val() ){
					
					var value_object = otw_pct_ct_get_json();
					var selected_sidebar = false;
					
					if( jQuery( '#otw_new_ct_template').val().match( /\-sidebar$/ ) ){
						selected_sidebar = jQuery( '#otw_new_ct_sidebar').val();
					}
					
					value_object[ value_object.length ] = {
						order: value_object.length,
						category: jQuery( '#otw_new_ct_category').val(),
						template: jQuery( '#otw_new_ct_template').val(),
						sidebar: selected_sidebar
					};
					
					otw_pct_ct_set_json( value_object );
				};
			});
		}
		
		otw_pct_ct_table();
	};
};

function otw_pct_ct_table(){
	
	var text_value_object = jQuery( '#otw_pct_category_template' );
	
	var req_url = ajaxurl;
	
	jQuery.ajax( req_url , {
		data: {
			object: text_value_object.val(),
			action: 'otw_pctl_category_template_table'
		},
		method: 'post'
	}).done(function(data) {
		jQuery( '#otw_pct_ct_table' ).html( data );
		
		otw_pct_ct_init_table();
	});
};

function otw_pct_ct_set_json( value_object ){
	
	var text_value_object = jQuery( '#otw_pct_category_template' );
	
	if( text_value_object.size() ){
		
		text_value_object.val( JSON.stringify( value_object ) );
		otw_pct_ct_table();
	}
}

function otw_pct_ct_get_json(){
	
	var value_object = false;
	
	var text_value_object = jQuery( '#otw_pct_category_template' );
	
	if( text_value_object.size() ){
		
		var value_object = new Array();
		
		if( text_value_object.val().length ){
			value_object = jQuery.parseJSON( text_value_object.val() );
		};
	};
	
	return value_object;
};

function otw_pct_ct_init_table(){
	
	jQuery( '#otw_pct_ct_table' ).find( '.otw_ct_delete' ).click( function(){
		
		var row_id = -1;
		
		var matches = false;
		
		if( matches = jQuery( this ).parents( 'div.otw_ct_row' ).first().attr( 'id' ).match( /^otw_ct_row_(\d+)$/ ) ){
			row_id = Number( matches[1] );
			
			if( row_id >= 0 ){
				
				var current_value = otw_pct_ct_get_json();
				var new_value = new Array();
				
				for( var cV = 0; cV < current_value.length; cV++ ){
					
					if( cV != row_id ){
						var new_index = new_value.length;
						new_value[ new_index ] = current_value[cV];
						new_value[ new_index ].order = new_index;
					}
				}
				otw_pct_ct_set_json( new_value );
			};
		}
	});
	jQuery( "#otw_pct_ct_table>div" ).sortable({
			
			update: function( event, ui ) {
			
				var current_value = otw_pct_ct_get_json();
				var new_value = new Array();
				
				var sorted_rows = jQuery( "#otw_pct_ct_table .otw_ct_row" );
				
				for( var cR = 0; cR < sorted_rows.size(); cR++ ){
					
					var new_index = new_value.length;
					var matches = false;
					
					if( matches = sorted_rows[ cR ].id.match( /^otw_ct_row_(\d+)$/ ) ){
						
						var old_index = matches[1];
						
						if( typeof current_value[ old_index ] == 'object' ){
						
							var new_index = new_value.length;
							new_value[ new_index ] = current_value[ old_index ];
							new_value[ new_index ].order = new_index;
						};
					};
				};
				otw_pct_ct_set_json( new_value );
			}
		}
	);
}

/** 
 * Show soccial related contollers based in the selected value
 */
function otw_pct_set_social_related_controlers(){
	var social_value = jQuery( '#show-social-icons' ).val();
	
	switch( social_value ){
		
		case '0':
				jQuery( '#otw-show-social-icons-type' ).hide();
				jQuery( '#otw-show-social-icons-custom' ).hide();
			break;
		case 'custom_icons':
				jQuery( '#otw-show-social-icons-type' ).hide();
				jQuery( '#otw-show-social-icons-custom' ).fadeIn();
			break;
		case 'like_buttons':
				var labels = jQuery( '#otw-show-social-icons-type label' );
				var inputs = jQuery( '#otw-show-social-icons-type input' );
				
				jQuery( labels[4] ).hide();
				jQuery( labels[5] ).hide();
				
				jQuery( inputs[3] ).hide();
				jQuery( inputs[4] ).hide();
				
				jQuery( '#otw-show-social-icons-type' ).fadeIn();
				jQuery( '#otw-show-social-icons-custom' ).hide();
			break;
		default:
				jQuery( '#otw-show-social-icons-type' ).fadeIn();
				jQuery( '#otw-show-social-icons-custom' ).hide();
				jQuery( '#otw-show-social-icons-type label' ).fadeIn();
				jQuery( '#otw-show-social-icons-type input' ).fadeIn();
			break;
	}
}

function otw_pct_setting_form_sidebars( id ){
	
	var selector =  '.otw_pct_archive_template';
	
	if( id ){
		selector = '#' + id;
	}
	
	jQuery( selector ).each( function(){
	
		var sidebar_holder = jQuery( '#' + this.id.replace( /_template$/, '_sidebar_holder' ) );
		
		if( sidebar_holder.size() ){
			if( this.value.match( /\-sidebar$/ ) ){
				
				sidebar_holder.fadeIn();
			}else{
				sidebar_holder.hide();
			}
		}else{
		
			var sidebar_select = jQuery( '#' + this.id.replace( /_template$/, '_sidebar' ) );
			
			if( this.value.match( /\-sidebar$/ ) ){
				
				sidebar_select.fadeIn();
			}else{
				sidebar_select.hide();
			}
		}
	} );
}


/**
 * Iterate On Blog Meta Items
 * Detect Items that will be used in the meta
 * Drag & Drop List Functionality
 */
function otw_pct_update_sortable_elements() {
	var elementsArray = new Array();

	jQuery('.js-meta-active > .js-meta--item').each( function( item, value )  {
		elementsArray.push( jQuery(value).data('value') );
	});

	jQuery('.js-meta-items').val( elementsArray );
}


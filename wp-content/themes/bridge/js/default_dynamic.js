		function bridgeQodeAjaxSubmitCommentForm(){
		"use strict";

		var options = {
		success: function(){
		$j("#commentform textarea").val("");
		$j("#commentform .success p").text("Comment has been sent!");
		}
		};

		$j('#commentform').submit(function() {
		$j(this).find('input[type="submit"]').next('.success').remove();
		$j(this).find('input[type="submit"]').after('<div class="success"><p></p></div>');
		$j(this).ajaxSubmit(options);
		return false;
		});
		}
		var header_height = 100;
		var min_header_height_scroll = 57;
		var min_header_height_fixed_hidden = 50;
		var min_header_height_sticky = 60;
		var scroll_amount_for_sticky = 85;
		var content_line_height = 60;
		var header_bottom_border_weight = 1;
		var scroll_amount_for_fixed_hiding = 200;
		var paspartu_width_init = 0.02;
        var add_for_admin_bar = jQuery('body').hasClass('admin-bar') ? 32 : 0;

																
		var logo_height = 130; // proya logo height
		var logo_width = 280; // proya logo width
								logo_height = 76;
						logo_width = 213;

											header_top_height = 0;
					var loading_text;
				loading_text = 'Loading new posts...';
				var finished_text;
		finished_text = 'No more posts';
		
		function showContactMap() {
		"use strict";

		if($j("#map_canvas").length > 0){
		initialize();
		codeAddress("");
		codeAddress("");
		codeAddress("");
		codeAddress("");
		codeAddress("");
		}
		}

		var no_ajax_pages = [];
		var qode_root = 'http://localhost:8080/wordpress/';
		var theme_root = 'http://localhost:8080/wordpress/wp-content/themes/bridge/';
					var header_style_admin = "";
				if(typeof no_ajax_obj !== 'undefined') {
		no_ajax_pages = no_ajax_obj.no_ajax_pages;
		}

	
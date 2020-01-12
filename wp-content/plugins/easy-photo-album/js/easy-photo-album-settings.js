jQuery(document)
		.ready(
				function($) {
					var $lightbox_settings = $('#epa-lightbox-settings');
					$('input[name="EasyPhotoAlbum[viewmode]"]:radio').change(
							function(e) {
								if ($(this).val() == 'lightbox') {
									$lightbox_settings.slideDown(400);
								} else {
									$lightbox_settings.slideUp(400);
								}
							});
					if ($('input[name="EasyPhotoAlbum[viewmode]"]:checked')
							.val() != 'lightbox') {
						$lightbox_settings.hide(0);
					}

					var $label = $(
							'input[name="EasyPhotoAlbum[imagenumberformat]"]')
							.parents('tr');
					$('#epa-showimagenumber').change(function(e) {
						if ($(this).is(':checked')) {
							$label.slideDown(300);
						} else {
							$label.slideUp(300);
						}
					});
					if (!$('#epa-showimagenumber').is(':checked')) {
						$label.hide(0);
					}

					// help boxes
					var open = null;
					$('.epa-help').click(
							function(e) {
								// hide any open help texts
								$('.epa-help-content').fadeOut('fast');
								// show it
								if (open != $(this).data('helpid')) {
									$('#' + $(this).data('helpid')).fadeIn(
											'fast').css("display",
											"inline-block");
									open = $(this).data('helpid');
								} else
									open = null;
							});
					// global click
					$(document).click(function(e) {
						// hide them all
						if (open != $(e.target).data('helpid')) {
							$('.epa-help-content').fadeOut('fast');
							open = null;
						}
					});
				});

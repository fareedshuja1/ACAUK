//
// ======================== SIDEBARS ======================== 
//	
	jQuery( document ).on( 'click', '.smt_add_sidebar', function() {
		
		makeWindow( {
			'title':'Add New Sidebar',
			'saveBtn':'Add',
			'atts':{
				'title':{
					'value':'',
					'type':'text',
					'title':'Sidebar title'
				},
				'description':{
					'value':'',
					'type':'textarea',
					'title':'Sidebar Description'
				}
			},
			'saveClick':function( atts, e ) {
				atts[ 'action' ] = 'smt_add_sidebar';
				jQuery.ajax({
					url:ajaxurl,
					cache: false,
					data: atts,
					type: 'POST',
					dataType: 'json',
					success: function(response, textStatus, jqXHR ) {
						
						document.location.reload(true);
						
					}
				});	
				return true;
			}
		});
		
	});
	
	


	
	
//
// ======================== CHECKBOX ======================== 
//	
	function prepareCheckboxes() {
		jQuery( 'input.smt_checkbox_ui:not(.prepared)' ).each( function() {
			var input = jQuery(this);
			input.addClass( 'prepared' );
			var checkbox = jQuery('<div>', { 'class':'smt_checkbox_input' });
			if ( input.is( ':checked' ) ) {
				var ic_class = 'smt_checkbox_ic on';
			} else {
				var ic_class = 'smt_checkbox_ic';
			}
			checkbox.append( 
				jQuery('<div>', { 'class':ic_class }) 
			).append( input.clone() );
			
			jQuery( this ).replaceWith( checkbox );
			
		});
	}


	jQuery( document ).ready( function() {
		prepareCheckboxes();
	});
	
	
	jQuery( document ).on( 'click', '.smt_checkbox_ic', function() {
		var checkbox = jQuery( this ).closest( '.smt_checkbox_input' );
		jQuery( 'input', checkbox ).prop( 'checked', !jQuery( 'input', checkbox  ).is( ':checked' ) ).trigger( 'change' );
		if ( jQuery( 'input', checkbox  ).is( ':checked' ) ) {
			jQuery( this ).addClass( 'on' );
		} else {
			jQuery( this ).removeClass( 'on' );
		}
	});
	
	
	
	
	
	
//
// ======================== SELECT ======================== 
//
	function prepareSelects() {
		jQuery( '.smt_option select:not(.prepared)' ).each( function() {
			var select = jQuery(this);
			select.addClass( 'prepared' );
			var dropdown=jQuery('<div>', { 'class':'dropdown' });
			dropdown.append( '<div class="smt_select_swipe"></div>' );
			if ( typeof this.attributes['multiple'] != 'undefined' ) {
				dropdown.addClass( 'multiple' );
			}
			var currentOption = [];
			jQuery( 'option:selected', this).each( function() {
				currentOption.push( jQuery( this ).text() );
			});
			dropdown.append(
				jQuery('<div>', { 'class':'current smt_ic' }).text( currentOption.join( ', ' ) )
			).append(jQuery('<ul>')).append( select.clone() );
			jQuery('option', this).each(function() {
				if ( jQuery(this).prop( 'selected' ) ) {
				//if ( select.val() == jQuery( this ).attr( 'value' ) ) {
					var atts = { 'class':'active' };
				} else {
					var atts = {  };
				}
				jQuery( 'ul', dropdown ).append( jQuery( '<li>', atts ).text( jQuery( this ).text( ) ) );
			});
			jQuery( 'ul', dropdown ).mousewheel( function( event, delta ) {
				var direction =  delta / Math.abs( delta );
				var newTop = jQuery( this ).position().top + direction*100;
				var scrolled=jQuery('html, body').scrollTop() ? jQuery('html, body').scrollTop() : e.currentTarget.scrollY;
				if ( ( jQuery( this ).offset().top - scrolled < 0 && direction > 0 ) || ( scrolled + jQuery(window).height() < jQuery( this ).offset().top + jQuery( this ).height() && direction < 0 ) ) {
				
					jQuery( '.smt_select_swipe', dropdown ).stop().animate(
						{ top:newTop },
						200
					);
					
					jQuery( this ).stop().animate(
						{ top:newTop },
						200
					);
				}
				return false;
			});
			jQuery( this ).replaceWith( dropdown );
			
		});
	}
	jQuery( document ).ready( function() {
		prepareSelects();
	});
	function rehashSelect( select ) {
		var ul = select.prev( 'ul' );
		console.log( ul.html( '' ) );
		jQuery( 'option', select ).each(function() {
			ul.append( jQuery( '<li>' ).text( jQuery( this ).text( ) ) );
		});
	}
	jQuery( document ).on( 'click', '.dropdown .current', function( e ) {
		var dropdown = jQuery( this ).closest( '.dropdown' );
		jQuery( '.smt_select_swipe', dropdown ).click();
	});
	jQuery( document ).on( 'click', '.dropdown .smt_select_swipe', function( e ) {
		
		var dropdown = jQuery( this ).closest( '.dropdown' );
		var ul = jQuery( 'ul', dropdown ).show();
			
		if ( jQuery( this ).hasClass( 'up' ) ) {
			
			var currentOption = [];
			jQuery( 'option:selected', dropdown ).each( function() {
				currentOption.push( jQuery( this ).text() );
			});
			jQuery( '.current', dropdown ).text( currentOption.join( ', ' ) );
			var newHeight = parseInt(jQuery( '.current', dropdown ).outerHeight())-4;
			ul.children().hide();
			jQuery( '.smt_select_swipe', dropdown ).removeClass( 'up' ).animate(
				{height:newHeight, top:0 },
				200
			);
			ul.animate(
				{height:newHeight, top:0 },
				200,
				function() { jQuery( this ).hide(); }
			);
		
		} else {
			
			
			var newHeight = ul.children().eq(0).outerHeight()*ul.children().length;
			var scrolled = jQuery('html, body').scrollTop() ? jQuery('html, body').scrollTop() : e.currentTarget.scrollY;
			if ( void 0 == scrolled ) scrolled = 0;
			
			if ( ( ul.offset().top - scrolled ) < ((newHeight-ul.height())/2) ) {
				newTop = 0;
			} else {
				newTop = ((newHeight-ul.height())/2);
			}
			
			jQuery( '.smt_select_swipe', dropdown ).addClass( 'up' ).animate(
				{height:newHeight, top:-newTop },
				200
			);
			
			ul.animate(
				{height:newHeight, top:-newTop },
				200
			).children().show();
		
		}
		
		
	});
	// jQuery( document ).on( 'click', '.dropdown ul', function() {
			
	// });
	jQuery( document ).on( 'click', '.dropdown ul li', function() {
		var dropdown = jQuery( this ).closest( '.dropdown' );
		var multiple = dropdown.hasClass( 'multiple' );
		
		if ( !jQuery( this ).hasClass( 'active' ) || !multiple ) {
			jQuery( this ).addClass( 'active' );
			jQuery( 'select option', dropdown ).eq( jQuery(this).index() ).prop('selected', true);
		} else {
			jQuery( this ).removeClass( 'active' );	
			jQuery( 'select option', dropdown ).eq( jQuery(this).index() ).prop('selected', false);
		}
		
		jQuery( 'select', dropdown ).trigger( 'change' );
		
		if ( !multiple ) {
			jQuery( this ).siblings().removeClass( 'active' );
			jQuery( '.smt_select_swipe', dropdown ).click();
		}
		
	});
	
	
	
	
	
	
//
// ======================== IMAGE ======================== 
//

	function prepareImages() {
		jQuery( 'input.smt_image_ui:not(.prepared)' ).each( function() {
			var input = jQuery(this);
			input.addClass( 'prepared' );
			var image = jQuery('<div>', { 'class':'smt_image_input' });
			
			if ( input.val() != '' ) {
				var ic_text = 'Change Image';
				var preview = 'block';
			} else {
				var ic_text = 'Set Image';
				var preview = 'none';
			}
			
			image.append( 
				jQuery('<div>', { 'class':'smt_image_preview' }).append(
					jQuery( '<img>', { src: input.val() } )
				).css( { 'display': preview })
			).append(
				jQuery('<span>', { 'class':'smt_image_ic smt_ic' }).text( ic_text )
			).append( input.clone() );
			
			jQuery( this ).replaceWith( image );
			
		});
	}


	jQuery( document ).ready( function() {
		prepareImages();
	});
	
	jQuery( document ).on( 'click', '.smt_image_preview img', function() {
		jQuery( this ).closest( '.smt_image_input' ).find( '.smt_image_ic' ).click();
	});
	
	jQuery( document ).on( 'click', '.smt_image_ic', function() {
		
		var imageOption = jQuery( this ).closest( '.smt_image_input' );
		var imageInput = imageOption.find( 'input[type="hidden"]' );
		
		if( wp.media.frames[ imageInput.attr( 'name' ) ] ) {
			wp.media.frames[ imageInput.attr( 'name' ) ].open();
			return;
		}
		wp.media.frames[ imageInput.attr( 'name' ) ] = wp.media({
		   title: 'Select image',
		   multiple: false,
		   library: {
			  type: 'image'
		   },
		   button: {
			  text: 'Use selected image'
		   }
		});
		

		wp.media.frames[ imageInput.attr( 'name' ) ].on( 'select', function() {
			var selection = wp.media.frames[ imageInput.attr( 'name' ) ].state().get('selection');
		 
			if (!selection) {
				return;
			}
		 
			// iterate through selected elements
			selection.each(function(attachment) {
				
				console.log( attachment );
				var url = attachment.attributes.url;
				imageOption.find( 'input[type="hidden"]' ).val( url );
				imageOption.find( 'img' ).attr( 'src', '' );
				imageOption.find( 'img' ).attr( 'src', url );
				imageOption.find( '.smt_image_preview' ).show();
				imageOption.find( '.smt_image_ic' ).text( 'Change image' );
				console.log( url );
			});
		} );
		wp.media.frames[ imageInput.attr( 'name' ) ].open();
		
	});
	
	
	
	
	
	
//
// ======================== DEPENDS ======================== 
//
	
	jQuery( document ).ready( function() {
		jQuery( '.smt_option select, .smt_option input' ).each( function() {
			
			if ( jQuery( this ).attr( 'type' )=='checkbox' ) {
				var val = ( jQuery(this).prop( 'checked' ) )?1:0;
			} else {
				var val = jQuery(this).val();
			}
			
			jQuery( '.smt_option[depend="'+jQuery( this ).attr( 'name' )+'"][case="' + val + '"]' ).show();
		});
	});
	jQuery( document ).on( 'change', '.smt_option select, .smt_option input', function() {
		
		if ( jQuery( this ).attr( 'type' )=='checkbox' ) {
			var val = ( jQuery(this).prop( 'checked' ) )?1:0;
		} else {
			var val = jQuery(this).val();
		}
			
		jQuery( '.smt_option[depend="'+jQuery( this ).attr( 'name' )+'"]' ).hide();
		jQuery( '.smt_option[depend="'+jQuery( this ).attr( 'name' )+'"][case="' + val + '"]' ).show();
	});
	
	
	
	
	
	
//
// ======================== HINTS ======================== 
//
	
	jQuery( document ).on( 'click', '.smt_hint_trigger', function() {
		if ( jQuery( this ).hasClass( 'active' ) ) {
			jQuery( this ).removeClass( 'active' ).next( '.smt_hint_text' ).animate({ 'opacity':'0', 'margin-bottom':'0'}, 500, function() { jQuery( this ).hide();  });
		} else {
			jQuery( this ).addClass( 'active' ).next( '.smt_hint_text' ).css( { 'display': 'block' } ).animate({ 'opacity':'1', 'margin-bottom':'10px'}, 500 );
		}
	});
	jQuery( document ).on( 'click', '.smt_hint_text .close', function() {
		jQuery( this ).closest( '.smt_hint_text' ).animate({ 'opacity':'0', 'margin-bottom':'0'}, 500, function() { jQuery( this ).hide();  });
	});
	
	
	
	
	
	
//
// ======================== SAVE & RESET ======================== 
//

	jQuery( document ).on( 'click', '#smt_save_settings', function() {
		
		var data = {'action': 'smt_update_options'};
		
		jQuery( '.smt_settings_body *[name]' ).each( function() {
			if ( jQuery( this ).attr( 'type' )=='checkbox' ) {
				data[ jQuery(this).attr( 'name' ) ] = ( jQuery(this).prop( 'checked' ) )?1:0;
			} else {
				data[ jQuery(this).attr( 'name' ) ] = jQuery(this).val();
			}
		});
		
		
		jQuery( '#smt_reset_settings, #smt_save_settings' ).animate( { 'opacity':0.3 }, 500 );
		jQuery( '.saving_settings' ).show();
		
		jQuery.ajax({
			url:ajaxurl,
			cache: false,
			data: data,
			type: 'POST',
			dataType: 'json',
			success: function(response, textStatus, jqXHR ) {
				
				jQuery( '.saving_settings' ).hide();
				jQuery( '#smt_reset_settings, #smt_save_settings' ).animate( { 'opacity':1 }, 500 );
				console.log( response );
				
			}
		});
		
	});
	
	jQuery( document ).on( 'click', '#smt_reset_settings', function() {
		if( !confirm( 'Are you sure you want to reset ' + jQuery( '.smt_settings_title h2' ).text() + ' settings?' ) ) {
			return false;
		}
	});
	
	
	
	
//
// ======================== SPOILERS ======================== 
//
	
	jQuery( document ).on( 'click', '.smt_group_caption', function() {
		
		jQuery( this ).toggleClass( 'active' );
		
		if( jQuery( this ).hasClass( 'active' ) ) {
			jQuery( this ).next( '.smt_group_content' ).slideDown();
		} else {
			jQuery( this ).next( '.smt_group_content' ).slideUp();
		}
		
	});
	
	
	
	

	
//
// ======================== OBJECT LIST ======================== 
//
	
				jQuery( function() {
					
					jQuery( ".smt_object_list" ).sortable({
						handle: ".smt_object_list_element_caption",
						placeholder: "smt_object_list_dropzone"
					});
					//jQuery( ".smt_object_list" ).disableSelection();
					
				});
					
				jQuery( document ).on( 'click', '.smt_object_list_element_caption', function() {
		
					jQuery( this ).toggleClass( 'active' );
					
					if( jQuery( this ).hasClass( 'active' ) ) {
						jQuery( this ).next( '.smt_object_list_element_content' ).slideDown();
					} else {
						jQuery( this ).next( '.smt_object_list_element_content' ).slideUp();
					}
					
				});
				
				jQuery( document ).on( 'click', '.smt_object_list_element_caption .smt_object_list_element_remove', function() {
					
					jQuery( this ).closest( '.smt_object_list > li' ).remove();
					
				});
				
				jQuery( document ).on( 'click', '.smt_object_list_new', function() {
					
					
					var newSlide = jQuery( this ).find( '.smt_object_list_blank' ).children().clone();
					var paramName = jQuery( this ).attr( 'data-name' );
					var slideName = Math.floor( ( Math.random( ) * 100000 ) + 1 ); 
					
					jQuery( '*[name]', newSlide ).each( function() {
						jQuery( this ).attr( 'name', paramName+'[' + slideName + '][' + jQuery( this ).attr( 'name' ) + ']' );
					});
					jQuery( this ).prev( '.smt_object_list' ).append( newSlide );
					
					jQuery( '.smt_object_list_element_caption', newSlide ).click();
					
				});
				
				
				
				
				
				
				
				
//
// ======================== FONT AWESOME ======================== 
//

	function prepareIcons() {
		jQuery( 'input.smt_icon_ui:not(.prepared)' ).each( function() {
			var input = jQuery(this);
			input.addClass( 'prepared' );
			var icon = jQuery('<div>', { 'class':'smt_icon_input' });
			
			if ( input.val() != '' ) {
				var ic_text = 'Change Icon';
				var preview = 'block';
			} else {
				var ic_text = 'Set Icon';
				var preview = 'none';
			}
			
			icon.append( 
				jQuery('<div>', { 'class':'smt_icon_preview' }).append(
					jQuery( '<div>', { 'class': 'icon' } ).html(  input.val()  )
				).css( { 'display': preview })
			).append(
				jQuery('<span>', { 'class':'smt_icon_ic smt_ic' }).text( ic_text )
			).append( input.clone() );
			
			jQuery( this ).replaceWith( icon );
			
		});
	}


	jQuery( document ).ready( function() {
		prepareIcons();
	});
	
	jQuery( document ).on( 'click', '.smt_icon_preview .icon', function() {
		jQuery( this ).closest( '.smt_icon_input' ).find( '.smt_icon_ic' ).click();
	});
	
	jQuery( document ).on( 'click', '.smt_icon_ic', function() {
		
		var MWindow = jQuery( '<div class="popup-window"></div>' );
		MWindow.data( 'sender', jQuery( this ) );
		var windowContent = jQuery( '<div class="popup-window-content"></div>' );
		
		windowContent.append( '<div class="smt_text_input smt_ic"><input type="text" name="" value="" placeholder="Search icon" id="iconHelper" /></div>' );
		
		var icons = smt_getFontAwesome();
		
		var iconsContainer = jQuery( '<div>', { 'class':'iconsContainer' } );
		
		for( iconTitle in icons ) {
			iconsContainer.append(
				'<div class="icon" alt="' + iconTitle + '">' + icons[ iconTitle ] + '</div>'
			);
		}
		
		windowContent.append( iconsContainer );
		
		
		jQuery( '<div class="popup-window-header"><span class="close icon">&#xf05c;</span><h3>Select icon</h3></div>' ).appendTo( MWindow );
		windowContent.appendTo( MWindow );
		
		jQuery( '<div class="modal-fade"></div>' ).appendTo( 'body' );
		MWindow.appendTo( 'body' );
		
		MWindow.mousewheel( function( event, delta ) {
			event.preventDefault();
			var direction =  delta / Math.abs( delta );
			
			var newTop = parseInt( jQuery( this ).css( 'top' ) ) + direction*100;
			console.log( '--------------------------------' );
			console.log( jQuery( this ).css( 'top' ) );
			console.log( 'window', jQuery(MWindow).height() );
			
			var scrolled=jQuery('html, body').scrollTop() ? jQuery('html, body').scrollTop() : e.currentTarget.scrollY;
			if ( newTop < 100 && newTop > -( jQuery( this ).height() - jQuery( '.modal-fade' ).height()  + 100 ) ) {
			
				console.log( newTop );
				jQuery( this ).stop().animate(
					{ top:newTop },
					200
				);
			}
			return false;
		});
		
	});
	jQuery( document ).on( 'click', '.iconsContainer .icon', function() {
		
		var input = jQuery( this ).closest( '.popup-window' ).data( 'sender' ).closest( '.smt_icon_input' );
		input.find( '.smt_icon_ui' ).val( jQuery( this ).html() );
		input.find( '.smt_icon_preview .icon' ).html( jQuery( this ).html() );
		input.find( '.smt_icon_preview' ).show();
		input.find( '.smt_icon_ic' ).text( 'Change image' );
		jQuery( this ).closest( '.popup-window' ).find( '.close' ).click();
		
		
	});
	jQuery( document ).on( 'keyup', '#iconHelper', function() {
		
		if ( jQuery( this ).val() != '' ) {
			jQuery( ".iconsContainer .icon" ).hide();
			jQuery( ".iconsContainer .icon[alt*='"+jQuery( this ).val()+"']" ).show();
		} else jQuery( ".iconsContainer .icon" ).show();
		
	});
	
	
function smt_getFontAwesome() {
 return {"500px":"&#xf26e;","adjust":"&#xf042;","adn":"&#xf170;","align center":"&#xf037;","align justify":"&#xf039;","align left":"&#xf036;","align right":"&#xf038;","amazon":"&#xf270;","ambulance":"&#xf0f9;","anchor":"&#xf13d;","android":"&#xf17b;","angellist":"&#xf209;","angle double down":"&#xf103;","angle double left":"&#xf100;","angle double right":"&#xf101;","angle double up":"&#xf102;","angle down":"&#xf107;","angle left":"&#xf104;","angle right":"&#xf105;","angle up":"&#xf106;","apple":"&#xf179;","archive":"&#xf187;","area chart":"&#xf1fe;","arrow circle down":"&#xf0ab;","arrow circle left":"&#xf0a8;","arrow circle o down":"&#xf01a;","arrow circle o left":"&#xf190;","arrow circle o right":"&#xf18e;","arrow circle o up":"&#xf01b;","arrow circle right":"&#xf0a9;","arrow circle up":"&#xf0aa;","arrow down":"&#xf063;","arrow left":"&#xf060;","arrow right":"&#xf061;","arrow up":"&#xf062;","arrows":"&#xf047;","arrows alt":"&#xf0b2;","arrows h":"&#xf07e;","arrows v":"&#xf07d;","asterisk":"&#xf069;","at":"&#xf1fa;","automobile":"&#xf1b9;","backward":"&#xf04a;","balance scale":"&#xf24e;","ban":"&#xf05e;","bank":"&#xf19c;","bar chart":"&#xf080;","bar chart o":"&#xf080;","barcode":"&#xf02a;","bars":"&#xf0c9;","battery 0":"&#xf244;","battery 1":"&#xf243;","battery 2":"&#xf242;","battery 3":"&#xf241;","battery 4":"&#xf240;","battery empty":"&#xf244;","battery full":"&#xf240;","battery half":"&#xf242;","battery quarter":"&#xf243;","battery three quarters":"&#xf241;","bed":"&#xf236;","beer":"&#xf0fc;","behance":"&#xf1b4;","behance square":"&#xf1b5;","bell":"&#xf0f3;","bell o":"&#xf0a2;","bell slash":"&#xf1f6;","bell slash o":"&#xf1f7;","bicycle":"&#xf206;","binoculars":"&#xf1e5;","birthday cake":"&#xf1fd;","bitbucket":"&#xf171;","bitbucket square":"&#xf172;","bitcoin":"&#xf15a;","black tie":"&#xf27e;","bluetooth":"&#xf293;","bluetooth b":"&#xf294;","bold":"&#xf032;","bolt":"&#xf0e7;","bomb":"&#xf1e2;","book":"&#xf02d;","bookmark":"&#xf02e;","bookmark o":"&#xf097;","briefcase":"&#xf0b1;","btc":"&#xf15a;","bug":"&#xf188;","building":"&#xf1ad;","building o":"&#xf0f7;","bullhorn":"&#xf0a1;","bullseye":"&#xf140;","bus":"&#xf207;","buysellads":"&#xf20d;","cab":"&#xf1ba;","calculator":"&#xf1ec;","calendar":"&#xf073;","calendar check o":"&#xf274;","calendar minus o":"&#xf272;","calendar o":"&#xf133;","calendar plus o":"&#xf271;","calendar times o":"&#xf273;","camera":"&#xf030;","camera retro":"&#xf083;","car":"&#xf1b9;","caret down":"&#xf0d7;","caret left":"&#xf0d9;","caret right":"&#xf0da;","caret square o down":"&#xf150;","caret square o left":"&#xf191;","caret square o right":"&#xf152;","caret square o up":"&#xf151;","caret up":"&#xf0d8;","cart arrow down":"&#xf218;","cart plus":"&#xf217;","cc":"&#xf20a;","cc amex":"&#xf1f3;","cc diners club":"&#xf24c;","cc discover":"&#xf1f2;","cc jcb":"&#xf24b;","cc mastercard":"&#xf1f1;","cc paypal":"&#xf1f4;","cc stripe":"&#xf1f5;","cc visa":"&#xf1f0;","certificate":"&#xf0a3;","chain":"&#xf0c1;","chain broken":"&#xf127;","check":"&#xf00c;","check circle":"&#xf058;","check circle o":"&#xf05d;","check square":"&#xf14a;","check square o":"&#xf046;","chevron circle down":"&#xf13a;","chevron circle left":"&#xf137;","chevron circle right":"&#xf138;","chevron circle up":"&#xf139;","chevron down":"&#xf078;","chevron left":"&#xf053;","chevron right":"&#xf054;","chevron up":"&#xf077;","child":"&#xf1ae;","chrome":"&#xf268;","circle":"&#xf111;","circle o":"&#xf10c;","circle o notch":"&#xf1ce;","circle thin":"&#xf1db;","clipboard":"&#xf0ea;","clock o":"&#xf017;","clone":"&#xf24d;","close":"&#xf00d;","cloud":"&#xf0c2;","cloud download":"&#xf0ed;","cloud upload":"&#xf0ee;","cny":"&#xf157;","code":"&#xf121;","code fork":"&#xf126;","codepen":"&#xf1cb;","codiepie":"&#xf284;","coffee":"&#xf0f4;","cog":"&#xf013;","cogs":"&#xf085;","columns":"&#xf0db;","comment":"&#xf075;","comment o":"&#xf0e5;","commenting":"&#xf27a;","commenting o":"&#xf27b;","comments":"&#xf086;","comments o":"&#xf0e6;","compass":"&#xf14e;","compress":"&#xf066;","connectdevelop":"&#xf20e;","contao":"&#xf26d;","copy":"&#xf0c5;","copyright":"&#xf1f9;","creative commons":"&#xf25e;","credit card":"&#xf09d;","credit card alt":"&#xf283;","crop":"&#xf125;","crosshairs":"&#xf05b;","css3":"&#xf13c;","cube":"&#xf1b2;","cubes":"&#xf1b3;","cut":"&#xf0c4;","cutlery":"&#xf0f5;","dashboard":"&#xf0e4;","dashcube":"&#xf210;","database":"&#xf1c0;","dedent":"&#xf03b;","delicious":"&#xf1a5;","desktop":"&#xf108;","deviantart":"&#xf1bd;","diamond":"&#xf219;","digg":"&#xf1a6;","dollar":"&#xf155;","dot circle o":"&#xf192;","download":"&#xf019;","dribbble":"&#xf17d;","dropbox":"&#xf16b;","drupal":"&#xf1a9;","edge":"&#xf282;","edit":"&#xf044;","eject":"&#xf052;","ellipsis h":"&#xf141;","ellipsis v":"&#xf142;","empire":"&#xf1d1;","envelope":"&#xf0e0;","envelope o":"&#xf003;","envelope square":"&#xf199;","eraser":"&#xf12d;","eur":"&#xf153;","euro":"&#xf153;","exchange":"&#xf0ec;","exclamation":"&#xf12a;","exclamation circle":"&#xf06a;","exclamation triangle":"&#xf071;","expand":"&#xf065;","expeditedssl":"&#xf23e;","external link":"&#xf08e;","external link square":"&#xf14c;","eye":"&#xf06e;","eye slash":"&#xf070;","eyedropper":"&#xf1fb;","facebook":"&#xf09a;","facebook f":"&#xf09a;","facebook official":"&#xf230;","facebook square":"&#xf082;","fast backward":"&#xf049;","fast forward":"&#xf050;","fax":"&#xf1ac;","feed":"&#xf09e;","female":"&#xf182;","fighter jet":"&#xf0fb;","file":"&#xf15b;","file archive o":"&#xf1c6;","file audio o":"&#xf1c7;","file code o":"&#xf1c9;","file excel o":"&#xf1c3;","file image o":"&#xf1c5;","file movie o":"&#xf1c8;","file o":"&#xf016;","file pdf o":"&#xf1c1;","file photo o":"&#xf1c5;","file picture o":"&#xf1c5;","file powerpoint o":"&#xf1c4;","file sound o":"&#xf1c7;","file text":"&#xf15c;","file text o":"&#xf0f6;","file video o":"&#xf1c8;","file word o":"&#xf1c2;","file zip o":"&#xf1c6;","files o":"&#xf0c5;","film":"&#xf008;","filter":"&#xf0b0;","fire":"&#xf06d;","fire extinguisher":"&#xf134;","firefox":"&#xf269;","flag":"&#xf024;","flag checkered":"&#xf11e;","flag o":"&#xf11d;","flash":"&#xf0e7;","flask":"&#xf0c3;","flickr":"&#xf16e;","floppy o":"&#xf0c7;","folder":"&#xf07b;","folder o":"&#xf114;","folder open":"&#xf07c;","folder open o":"&#xf115;","font":"&#xf031;","fonticons":"&#xf280;","fort awesome":"&#xf286;","forumbee":"&#xf211;","forward":"&#xf04e;","foursquare":"&#xf180;","frown o":"&#xf119;","futbol o":"&#xf1e3;","gamepad":"&#xf11b;","gavel":"&#xf0e3;","gbp":"&#xf154;","ge":"&#xf1d1;","gear":"&#xf013;","gears":"&#xf085;","genderless":"&#xf22d;","get pocket":"&#xf265;","gg":"&#xf260;","gg circle":"&#xf261;","gift":"&#xf06b;","git":"&#xf1d3;","git square":"&#xf1d2;","github":"&#xf09b;","github alt":"&#xf113;","github square":"&#xf092;","gittip":"&#xf184;","glass":"&#xf000;","globe":"&#xf0ac;","google":"&#xf1a0;","google plus":"&#xf0d5;","google plus square":"&#xf0d4;","google wallet":"&#xf1ee;","graduation cap":"&#xf19d;","gratipay":"&#xf184;","group":"&#xf0c0;","h square":"&#xf0fd;","hacker news":"&#xf1d4;","hand grab o":"&#xf255;","hand lizard o":"&#xf258;","hand o down":"&#xf0a7;","hand o left":"&#xf0a5;","hand o right":"&#xf0a4;","hand o up":"&#xf0a6;","hand paper o":"&#xf256;","hand peace o":"&#xf25b;","hand pointer o":"&#xf25a;","hand rock o":"&#xf255;","hand scissors o":"&#xf257;","hand spock o":"&#xf259;","hand stop o":"&#xf256;","hashtag":"&#xf292;","hdd o":"&#xf0a0;","header":"&#xf1dc;","headphones":"&#xf025;","heart":"&#xf004;","heart o":"&#xf08a;","heartbeat":"&#xf21e;","history":"&#xf1da;","home":"&#xf015;","hospital o":"&#xf0f8;","hotel":"&#xf236;","hourglass":"&#xf254;","hourglass 1":"&#xf251;","hourglass 2":"&#xf252;","hourglass 3":"&#xf253;","hourglass end":"&#xf253;","hourglass half":"&#xf252;","hourglass o":"&#xf250;","hourglass start":"&#xf251;","houzz":"&#xf27c;","html5":"&#xf13b;","i cursor":"&#xf246;","ils":"&#xf20b;","image":"&#xf03e;","inbox":"&#xf01c;","indent":"&#xf03c;","industry":"&#xf275;","info":"&#xf129;","info circle":"&#xf05a;","inr":"&#xf156;","instagram":"&#xf16d;","institution":"&#xf19c;","internet explorer":"&#xf26b;","intersex":"&#xf224;","ioxhost":"&#xf208;","italic":"&#xf033;","joomla":"&#xf1aa;","jpy":"&#xf157;","jsfiddle":"&#xf1cc;","key":"&#xf084;","keyboard o":"&#xf11c;","krw":"&#xf159;","language":"&#xf1ab;","laptop":"&#xf109;","lastfm":"&#xf202;","lastfm square":"&#xf203;","leaf":"&#xf06c;","leanpub":"&#xf212;","legal":"&#xf0e3;","lemon o":"&#xf094;","level down":"&#xf149;","level up":"&#xf148;","life bouy":"&#xf1cd;","life buoy":"&#xf1cd;","life ring":"&#xf1cd;","life saver":"&#xf1cd;","lightbulb o":"&#xf0eb;","line chart":"&#xf201;","link":"&#xf0c1;","linkedin":"&#xf0e1;","linkedin square":"&#xf08c;","linux":"&#xf17c;","list":"&#xf03a;","list alt":"&#xf022;","list ol":"&#xf0cb;","list ul":"&#xf0ca;","location arrow":"&#xf124;","lock":"&#xf023;","long arrow down":"&#xf175;","long arrow left":"&#xf177;","long arrow right":"&#xf178;","long arrow up":"&#xf176;","magic":"&#xf0d0;","magnet":"&#xf076;","mail forward":"&#xf064;","mail reply":"&#xf112;","mail reply all":"&#xf122;","male":"&#xf183;","map":"&#xf279;","map marker":"&#xf041;","map o":"&#xf278;","map pin":"&#xf276;","map signs":"&#xf277;","mars":"&#xf222;","mars double":"&#xf227;","mars stroke":"&#xf229;","mars stroke h":"&#xf22b;","mars stroke v":"&#xf22a;","maxcdn":"&#xf136;","meanpath":"&#xf20c;","medium":"&#xf23a;","medkit":"&#xf0fa;","meh o":"&#xf11a;","mercury":"&#xf223;","microphone":"&#xf130;","microphone slash":"&#xf131;","minus":"&#xf068;","minus circle":"&#xf056;","minus square":"&#xf146;","minus square o":"&#xf147;","mixcloud":"&#xf289;","mobile":"&#xf10b;","mobile phone":"&#xf10b;","modx":"&#xf285;","money":"&#xf0d6;","moon o":"&#xf186;","mortar board":"&#xf19d;","motorcycle":"&#xf21c;","mouse pointer":"&#xf245;","music":"&#xf001;","navicon":"&#xf0c9;","neuter":"&#xf22c;","newspaper o":"&#xf1ea;","object group":"&#xf247;","object ungroup":"&#xf248;","odnoklassniki":"&#xf263;","odnoklassniki square":"&#xf264;","opencart":"&#xf23d;","openid":"&#xf19b;","opera":"&#xf26a;","optin monster":"&#xf23c;","outdent":"&#xf03b;","pagelines":"&#xf18c;","paint brush":"&#xf1fc;","paper plane":"&#xf1d8;","paper plane o":"&#xf1d9;","paperclip":"&#xf0c6;","paragraph":"&#xf1dd;","paste":"&#xf0ea;","pause":"&#xf04c;","pause circle":"&#xf28b;","pause circle o":"&#xf28c;","paw":"&#xf1b0;","paypal":"&#xf1ed;","pencil":"&#xf040;","pencil square":"&#xf14b;","pencil square o":"&#xf044;","percent":"&#xf295;","phone":"&#xf095;","phone square":"&#xf098;","photo":"&#xf03e;","picture o":"&#xf03e;","pie chart":"&#xf200;","pied piper":"&#xf1a7;","pied piper alt":"&#xf1a8;","pinterest":"&#xf0d2;","pinterest p":"&#xf231;","pinterest square":"&#xf0d3;","plane":"&#xf072;","play":"&#xf04b;","play circle":"&#xf144;","play circle o":"&#xf01d;","plug":"&#xf1e6;","plus":"&#xf067;","plus circle":"&#xf055;","plus square":"&#xf0fe;","plus square o":"&#xf196;","power off":"&#xf011;","print":"&#xf02f;","product hunt":"&#xf288;","puzzle piece":"&#xf12e;","qq":"&#xf1d6;","qrcode":"&#xf029;","question":"&#xf128;","question circle":"&#xf059;","quote left":"&#xf10d;","quote right":"&#xf10e;","ra":"&#xf1d0;","random":"&#xf074;","rebel":"&#xf1d0;","recycle":"&#xf1b8;","reddit":"&#xf1a1;","reddit alien":"&#xf281;","reddit square":"&#xf1a2;","refresh":"&#xf021;","registered":"&#xf25d;","remove":"&#xf00d;","renren":"&#xf18b;","reorder":"&#xf0c9;","repeat":"&#xf01e;","reply":"&#xf112;","reply all":"&#xf122;","retweet":"&#xf079;","rmb":"&#xf157;","road":"&#xf018;","rocket":"&#xf135;","rotate left":"&#xf0e2;","rotate right":"&#xf01e;","rouble":"&#xf158;","rss":"&#xf09e;","rss square":"&#xf143;","rub":"&#xf158;","ruble":"&#xf158;","rupee":"&#xf156;","safari":"&#xf267;","save":"&#xf0c7;","scissors":"&#xf0c4;","scribd":"&#xf28a;","search":"&#xf002;","search minus":"&#xf010;","search plus":"&#xf00e;","sellsy":"&#xf213;","send":"&#xf1d8;","send o":"&#xf1d9;","server":"&#xf233;","share":"&#xf064;","share alt":"&#xf1e0;","share alt square":"&#xf1e1;","share square":"&#xf14d;","share square o":"&#xf045;","shekel":"&#xf20b;","sheqel":"&#xf20b;","shield":"&#xf132;","ship":"&#xf21a;","shirtsinbulk":"&#xf214;","shopping bag":"&#xf290;","shopping basket":"&#xf291;","shopping cart":"&#xf07a;","sign in":"&#xf090;","sign out":"&#xf08b;","signal":"&#xf012;","simplybuilt":"&#xf215;","sitemap":"&#xf0e8;","skyatlas":"&#xf216;","skype":"&#xf17e;","slack":"&#xf198;","sliders":"&#xf1de;","slideshare":"&#xf1e7;","smile o":"&#xf118;","soccer ball o":"&#xf1e3;","sort":"&#xf0dc;","sort alpha asc":"&#xf15d;","sort alpha desc":"&#xf15e;","sort amount asc":"&#xf160;","sort amount desc":"&#xf161;","sort asc":"&#xf0de;","sort desc":"&#xf0dd;","sort down":"&#xf0dd;","sort numeric asc":"&#xf162;","sort numeric desc":"&#xf163;","sort up":"&#xf0de;","soundcloud":"&#xf1be;","space shuttle":"&#xf197;","spinner":"&#xf110;","spoon":"&#xf1b1;","spotify":"&#xf1bc;","square":"&#xf0c8;","square o":"&#xf096;","stack exchange":"&#xf18d;","stack overflow":"&#xf16c;","star":"&#xf005;","star half":"&#xf089;","star half empty":"&#xf123;","star half full":"&#xf123;","star half o":"&#xf123;","star o":"&#xf006;","steam":"&#xf1b6;","steam square":"&#xf1b7;","step backward":"&#xf048;","step forward":"&#xf051;","stethoscope":"&#xf0f1;","sticky note":"&#xf249;","sticky note o":"&#xf24a;","stop":"&#xf04d;","stop circle":"&#xf28d;","stop circle o":"&#xf28e;","street view":"&#xf21d;","strikethrough":"&#xf0cc;","stumbleupon":"&#xf1a4;","stumbleupon circle":"&#xf1a3;","subscript":"&#xf12c;","subway":"&#xf239;","suitcase":"&#xf0f2;","sun o":"&#xf185;","superscript":"&#xf12b;","support":"&#xf1cd;","table":"&#xf0ce;","tablet":"&#xf10a;","tachometer":"&#xf0e4;","tag":"&#xf02b;","tags":"&#xf02c;","tasks":"&#xf0ae;","taxi":"&#xf1ba;","television":"&#xf26c;","tencent weibo":"&#xf1d5;","terminal":"&#xf120;","text height":"&#xf034;","text width":"&#xf035;","th":"&#xf00a;","th large":"&#xf009;","th list":"&#xf00b;","thumb tack":"&#xf08d;","thumbs down":"&#xf165;","thumbs o down":"&#xf088;","thumbs o up":"&#xf087;","thumbs up":"&#xf164;","ticket":"&#xf145;","times":"&#xf00d;","times circle":"&#xf057;","times circle o":"&#xf05c;","tint":"&#xf043;","toggle down":"&#xf150;","toggle left":"&#xf191;","toggle off":"&#xf204;","toggle on":"&#xf205;","toggle right":"&#xf152;","toggle up":"&#xf151;","trademark":"&#xf25c;","train":"&#xf238;","transgender":"&#xf224;","transgender alt":"&#xf225;","trash":"&#xf1f8;","trash o":"&#xf014;","tree":"&#xf1bb;","trello":"&#xf181;","tripadvisor":"&#xf262;","trophy":"&#xf091;","truck":"&#xf0d1;","try":"&#xf195;","tty":"&#xf1e4;","tumblr":"&#xf173;","tumblr square":"&#xf174;","turkish lira":"&#xf195;","tv":"&#xf26c;","twitch":"&#xf1e8;","twitter":"&#xf099;","twitter square":"&#xf081;","umbrella":"&#xf0e9;","underline":"&#xf0cd;","undo":"&#xf0e2;","university":"&#xf19c;","unlink":"&#xf127;","unlock":"&#xf09c;","unlock alt":"&#xf13e;","unsorted":"&#xf0dc;","upload":"&#xf093;","usb":"&#xf287;","usd":"&#xf155;","user":"&#xf007;","user md":"&#xf0f0;","user plus":"&#xf234;","user secret":"&#xf21b;","user times":"&#xf235;","users":"&#xf0c0;","venus":"&#xf221;","venus double":"&#xf226;","venus mars":"&#xf228;","viacoin":"&#xf237;","video camera":"&#xf03d;","vimeo":"&#xf27d;","vimeo square":"&#xf194;","vine":"&#xf1ca;","vk":"&#xf189;","volume down":"&#xf027;","volume off":"&#xf026;","volume up":"&#xf028;","warning":"&#xf071;","wechat":"&#xf1d7;","weibo":"&#xf18a;","weixin":"&#xf1d7;","whatsapp":"&#xf232;","wheelchair":"&#xf193;","wifi":"&#xf1eb;","wikipedia w":"&#xf266;","windows":"&#xf17a;","won":"&#xf159;","wordpress":"&#xf19a;","wrench":"&#xf0ad;","xing":"&#xf168;","xing square":"&#xf169;","y combinator":"&#xf23b;","y combinator square":"&#xf1d4;","yahoo":"&#xf19e;","yc":"&#xf23b;","yc square":"&#xf1d4;","yelp":"&#xf1e9;","yen":"&#xf157;","youtube":"&#xf167;","youtube play":"&#xf16a;","youtube square":"&#xf166;"};
}







jQuery( document ).ready( function() {
	
	jQuery( '#smt_updates' ).each( function() {
		var i = document.createElement( 'iframe' );
		i.src = 'https://smthemes.com/updates/';
		i.width = '100%';
		i.height = '720px';
		jQuery( this ).replaceWith( i );
	});
	
	jQuery( '#smt_activator' ).each( function() {
		var s = document.createElement( 'script' );
		s.src = 'https://smthemes.com/activator/'+this.getAttribute( "data-theme" )+'/';
		jQuery( this ).append( s );
	});
	
});













//
// ======================== Windows ======================== 
//
function makeWindow( options ) {
		
			
		settings = {
			'saveBtn':'Save'
		};
		
		for (var i in options) {
			settings[i] = options[i];
		}
		
		
		
		var MWindow = jQuery( '<div class="popup-window"></div>' );
		var windowContent = jQuery( '<div class="popup-window-content"></div>' );
		var windowFooter = jQuery( '<div class="popup-window-footer"></div>' );
		for( att in settings[ 'atts' ]) {
				switch( settings[ 'atts' ][ att ][ 'type' ] ) {
						case 'text':
							windowContent.append( '<div class="smt_text_input smt_ic"><input type="text" name="'+att+'" value="'+settings[ 'atts' ][att]['value']+'" placeholder="'+settings[ 'atts' ][att]['title']+'" /></div>' );
							if ( void 0 != settings[ 'atts' ][att]['style'] )
							windowContent.find( 'input[name="'+att+'"]' ).css( settings[ 'atts' ][att]['style'] )
							break;
						case 'select':
							var select = jQuery( '<select name="'+att+'"></select>' );
							for ( value in settings[ 'atts' ][att]['values'] ) {
								select.append( '<option value="'+value+'">'+settings[ 'atts' ][att]['values'][value]+'</option>' );
							}
							jQuery( '<div class="input"></div>' ).append( select ).appendTo( windowContent );
							break;
						case 'image':
							windowContent.append( '<div class="image_uploader" name="'+att+'" value="'+settings[ 'atts' ][att]['value']+'" src="'+settings[ 'atts' ][att]['value']+'"></div>' );
							break;
						case 'textarea':
							windowContent.append( '<div class="smt_text_input smt_ic"><textarea name="'+att+'"  placeholder="'+settings[ 'atts' ][att]['title']+'">'+settings[ 'atts' ][att]['value']+'</textarea></div>' );
							if ( void 0 != settings[ 'atts' ][att]['style'] )
							windowContent.find( 'textarea[name="'+att+'"]' ).css( settings[ 'atts' ][att]['style'] )
							break;
						case 'hidden':
							windowContent.append( '<input type="hidden" name="'+att+'" value="'+settings[ 'atts' ][att]['value']+'" />' );
							break;
						case 'p':
							windowContent.append( '<p id="'+att+'">'+settings[ 'atts' ][att]['value']+'</p>' );
							if ( void 0 != settings[ 'atts' ][att]['style'] )
							windowContent.find( '#'+att ).css( settings[ 'atts' ][att]['style'] )
							break;
							
				}
				
		}
		windowContent.append( '<p class="smt-popup-message"></p>' );
		jQuery( '<div class="popup-window-header"><span class="close icon">&#xf05c;</span><h3>'+settings['title']+'</h3></div>' ).appendTo( MWindow );
		windowContent.appendTo( MWindow );
		
		jQuery( '<a class="popup-window-save smt_major_button">'+settings['saveBtn']+'</a>' ).on( 'click', function( e ) {
			jQuery( '.smt-popup-message' ).hide();
			var atts={};
			jQuery( 'input, select, textarea', MWindow ).each( function() {
					atts[ jQuery( this ).attr( 'name' ) ]=jQuery( this ).val();
			});
			if ( settings[ 'saveClick' ]( atts, e ) ) {
					MWindow.hide().remove();
					jQuery( '.modal-fade' ).hide().remove();
			}
		}).appendTo( windowFooter );
		
		windowFooter.appendTo( MWindow );
		


		jQuery( '<div class="modal-fade"></div>' ).appendTo( 'body' );
		MWindow.appendTo( 'body' );
		console.log( MWindow.width() );
		if ( void 0 != settings[ 'e' ] ) {
			MWindow.css( {
				'left':settings[ 'e' ].pageX-MWindow.width()/2,
				'top':settings[ 'e' ].pageY-MWindow.height()
			}).show();
		}
		MWindow.mousewheel( function( event, delta ) {
			event.preventDefault();
			var direction =  delta / Math.abs( delta );
			
			var newTop = parseInt( jQuery( this ).css( 'top' ) ) + direction*100;
			console.log( '--------------------------------' );
			console.log( jQuery( this ).css( 'top' ) );
			console.log( 'window', jQuery(MWindow).height() );
			
			var scrolled=jQuery('html, body').scrollTop() ? jQuery('html, body').scrollTop() : e.currentTarget.scrollY;
			if ( newTop < 100 && newTop > -( jQuery( this ).height() - jQuery( '.modal-fade' ).height()  + 100 ) ) {
			
				console.log( newTop );
				jQuery( this ).stop().animate(
					{ top:newTop },
					200
				);
			}
			return false;
		});
		
		prepareCheckboxes();
		prepareSelects();
		prepareImages();
		prepareIcons();
		//console.log( e.pageX );
}
jQuery( document ).on( 'click', '.popup-window .close', function() {
	jQuery( this ).closest( '.popup-window' ).remove();
	jQuery( '.modal-fade' ).remove();
	if ( void 0 != settings[ 'sender' ] )
		jQuery( settings[ 'sender' ] ).animate( { opacity:1 } ).removeClass( 'disabled' );
});
jQuery( document ).on( 'keypress', '.popup-window', function( e ) {
	if ( e.keyCode == 27 ) {
		jQuery( '.close', this ).click();
	}
});
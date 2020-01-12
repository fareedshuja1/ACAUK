var epaInsert;

/**
 * Insert shortcode into post.
 *
 * @param  {Object} $ jQuery.
 */
(function($) {
	var input = {};
	epaInsert = {
		_loaded : false,

		init : function() {
			input.dialog = $('#epa-insert');
			input.list = $('#albums ul');
			input.nonce = $('#_ajax_epa_nonce');
			input.spinner = $('#epa-insert .spinner');
			input.submit = $('#epa-insert-submit');

			$('#epa-insert-cancel').click(function(e) {
				e.preventDefault();
				epaInsert.close();
			});

			input.dialog.bind('wpdialogbeforeopen', epaInsert.getAlbums);
		},

		bindActions : function() {
			$('#albums li').click(epaInsert.selectAlbum);
			input.submit.click(function(e) {
				epaInsert.submit();
				e.preventDefault();
			});
		},

		submit : function() {
			var shortcode = '[epa-album ';
			var id = $('#albums li.selected input.album-id').val();
			if (id) {
				var showTitle = $('#epa-title').is(':checked') ? 'true'
						: 'false';
				var disp = $('#epa-insert input[type=radio]:checked').val();
				shortcode += 'id="' + id + '" show_title="' + showTitle
						+ '" display="' + disp + '"]';
				if (tinyMCE && tinyMCE.activeEditor) {
					tinyMCE.activeEditor.selection.setContent(shortcode);
				}
			}
			epaInsert.close();
		},

		getAlbums : function() {
			if (epaInsert._loaded == true)
				return;
			input.spinner.show();
			var data = {
				action : 'epa_get_albums',
				_ajax_nonce : input.nonce.val()
			};

			$
					.post(
							ajaxurl,
							data,
							function(r) {
								// no results?
								if (!r || parseInt(r) == 0 || parseInt(r) == -1) {
									input.list
											.html('<li class="unselectable"><span class="item-title"><em>'
													+ epaInsertL10n.not_found
													+ '</em></span></li>');
								} else {
									r = JSON.parse(r);
									var list = '', alt = true;
									for ( var i in r) {
										var classes = alt ? 'alternate ' : '';
										list += classes ? '<li class="'
												+ classes + '">' : '<li>';
										list += '<input type="hidden" class="album-id" value="'
												+ r[i].id
												+ '"/>'
												+ '<span class="item-title">'
												+ r[i].title
												+ '</span>'
												+ '</li>';
										alt = !alt;
									}
									epaInsert._loaded = true;
									input.list.html(list);
								}
								input.spinner.hide();
								epaInsert.bindActions();
								$('#albums li').removeClass('selected');
							});
		},

		close : function() {
			if (epaInsert.isMCE())
				tinyMCEPopup.close();
			else
				input.dialog.wpdialog('close');
		},

		selectAlbum : function(elm) {
			$('#albums li').removeClass('selected');
			$(this).addClass('selected');
		},

		isMCE : function() {
			return tinyMCEPopup && (ed = tinyMCEPopup.editor) && !ed.isHidden();
		}
	};
	$(document).ready(epaInsert.init);
})(jQuery)

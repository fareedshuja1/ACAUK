// Support for string.format function.
if (!String.prototype.format) {
	String.prototype.format = function() {
		var args = arguments;
		return this.replace(/{(\d+)}/g, function(match, number) {
			return typeof args[number] != 'undefined' ? args[number] : match;
		});
	};
}

window.TVproductions = window.TVproductions || {};
(function(EPA, $, undefined) {

	// Private {

	// Wordpress (wp.media) uploader
	var uploader = null;

	// Selects the row with the corresponding data
	// attrname is the name of the data, value is the value
	var selectRowWith = function(attrname, value) {
		var thing = $('.easy-photo-album-table tr[' + attrname + '="' + value + '"]');

		return thing;
	};

	var build = function() {
		// Building can take much time when there are much images...
		var arr = [];
		$('.easy-photo-album-table tr td.column-image').each(
				function(index, elm) {
					$row = getRowFromElement(elm);
					var Id = parseInt($row.attr('data-epa-id'), 10);
					var obj = {
						id : Id,
						order : parseInt($row.attr('data-epa-order'), 10),
						title : getTitle(Id),
						caption : getCaption(Id)
					};
					arr.push(obj);
				});
		$('#epa-albumdata').val(JSON.stringify(arr));
	};

	var refresh = function() {
		correctActions();
		setClickHandlers();
	};

	// Makes shure the right actions are shown
	var correctActions = function() {
		if (EPA.maxOrder < 1) {
			// Only one image
			$(
					'.easy-photo-album-table .row-actions .order_up, .easy-photo-album-table .row-actions .order_down')
					.hide();
		} else {
			$('.easy-photo-album-table tr td.column-image')
					.each(
							function(index, elm) {
								$row = getRowFromElement(elm);
								order = parseInt($row.attr('data-epa-order'),
										10);
								if (order <= 0) {
									// most upper row
									$('.row-actions .order_up', $row).hide();
									$('.row-actions .order_down', $row).show();
								} else if (order >= EPA.maxOrder) {
									// most lower row
									$('.row-actions .order_down', $row).hide();
									$('.row-actions .order_up', $row).show();
									// remove |
									$('.row-actions .order_up', $row).html(
											$('.row-actions .order_up', $row)
													.html().replace(' | ', ''));
								} else {
									// show all the actions
									$(
											'.row-actions .order_down, .row-actions .order_up',
											$row).show();
									if ($('.row-actions .order_up', $row)
											.html().indexOf('|') === -1) {
										// the | is removed, so add it
										$('.row-actions .order_up', $row)
												.append(' | ');
									}
								}
							});
		}
		// Update also the image count labels:
		var str;
		if (EPA.maxOrder == 0)
			str = EPA.lang.photo;
		else
			str = EPA.lang.photos;

		$("#easy-photo-album-images .displaying-num").html(
				(EPA.maxOrder + 1) + " " + str);
	};

	// Returns the order from the id
	var getOrder = function(id) {
		var $elm = selectRowWith('data-epa-id', id);
		if ($elm === undefined) {
			return -1;
		} else {
			return parseInt($elm.attr('data-epa-order'), 10);
		}
	};

	// Returns the id from the order
	var getIdFromOrder = function(order) {
		var $elm = selectRowWith('data-epa-order', order);
		if ($elm === undefined) {
			return -1;
		} else {
			return parseInt($elm.attr('data-epa-id'), 10);
		}
	};

	// Returns the row (jQuery objct) with the given id.
	var getRowFromId = function(id) {
		var $elm = selectRowWith('data-epa-id', id);
		return getRowFromElement('input[type=checkbox][value="' + id + '"]');
	};

	// Returns the row (jQuery object) fromt the given element
	// (DOMelement/jQuery object)
	var getRowFromElement = function(element) {
		return $(element).closest('tr');
	};

	/**
	 * Switch rows.
	 *
	 * @author                      Aubrey Portwood
	 * @since                       1.3.6
	 *
	 * @param  {Number} oldRowOrder The order of the row that is moving.
	 * @param  {Number} movingId    The epa-id of the row that's moving.
	 * @param  {Number} newRowOrder The order of the row that the row is moving to.
	 */
	var switchRows = function( oldRowOrder, movingId, newRowOrder ) {
		var $newRow       = selectRowWith( 'data-epa-id', getIdFromOrder( newRowOrder ) );
		var $movedRow     = selectRowWith( 'data-epa-id', movingId );

		if ( $.isEmptyObject( $newRow ) || $.isEmptyObject( $movedRow ) || 0 === $newRow.length || 0 === $movedRow.length ) {
			return; // moving not possible...
		}

		// Get HTML and ID's to switch.
		var newRowHTML = $newRow.html();
		var movedRowHTML = $movedRow.html();
		var newRowId = $newRow.attr( 'data-epa-id' );
		var movedRowId = $movedRow.attr( 'data-epa-id' );

		// Switch the HTML of the elements.
		$newRow.html( movedRowHTML );
		$movedRow.html( newRowHTML );

		// Move the ID's around.
		setId( $movedRow, newRowId );
		setId( $newRow, movedRowId );
	};

	/**
	 * Set the Id of a row.
	 *
	 * @author              Aubrey Portwood
	 * @since               1.3.6
	 *
	 * @param {Object} $row The row jQuery object.
	 * @param {Integer} id  The id the row should have.
	 */
	var setId = function( $row, id ) {
		$row.attr( 'data-epa-id', id );
	};

	// Set the order to order for the given id.
	var setOrder = function(id, order) {
		var $elm = selectRowWith( 'data-epa-id', id );

		if ( $elm === 'undefined' ) {
			return;
		} else {
			$elm.attr( 'data-epa-order', order );
		}
	};

	// Set the click handlers for the actions
	var setClickHandlers = function() {
		// first unbind the click events
		$('.order_up a.epa-move-up, .order_down a.epa-move-down, .delete a')
				.unbind('click');
		setTimeout(function() {
			$('.order_up a.epa-move-up').click(function(e) {
				if (e.target.tagName.toLowerCase() == 'a') {
					EPA.moveUp(this);
				}
				e.preventDefault();
			});
			$('.order_down a.epa-move-down').click(function(e) {
				if (e.target.tagName.toLowerCase() == 'a') {
					EPA.moveDown(this);
				}
				e.preventDefault();

			});
			$('.delete a').click(function(e) {
				if (e.target.tagName.toLowerCase() == 'a') {
					EPA.deletePhoto(this);
				}
				e.preventDefault();
			});
			$('input[name="' + EPA.settingName + '[add_photo]"]').click(
					function(e) {
						EPA.addPhoto(this);
						e.preventDefault();
					});
			// Sortable things: css
			$('easy-photo-album-table tbody tr').not('.alternate').css({
				'background-color' : '#F9F9F9'
			});
		}, 50);

	};

	// Returns the title of the photo with the given id
	var getTitle = function(id) {
		return $('input#' + EPA.settingName + '-title-' + id).val();
	};

	// Returns the caption of the photo with the given id
	var getCaption = function(id) {
		return $('textarea#' + EPA.settingName + '-caption-' + id).val();
	};

	// } (end private)

	// Public {

	// Moves the given element (DOMelement or jQuery object) a position up
	EPA.moveUp = function(element) {
		var id = parseInt(getRowFromElement(element).attr('data-epa-id'), 10);
		var order = getOrder(id);
		if (order <= 0)
			return; // upper image
		var newOrder = order - 1; // Move up
		switchRows(order, id, newOrder);

		refresh();
	};

	// Moves the given element (DOMelement or jQuery object) a position down
	EPA.moveDown = function(element) {
		var id = parseInt(getRowFromElement(element).attr('data-epa-id'), 10);
		var order = getOrder(id);
		if (order >= EPA.maxOrder)
			return; // bottom
		var newOrder = order + 1; // move down
		switchRows(order, id, newOrder);

		refresh();
	};

	// Shows the media uploader and adds the selected photo's to the album
	EPA.addPhoto = function(element) {
		if (!uploader) {
			// make object
			if (!wp.media) {
				console
						.error("Easy Photo Album: Wordpress media files not included or loaded correctly. Uploads disabled.");
				return;
			}
			uploader = wp.media({
				title : EPA.lang.mediatitle,
				button : {
					text : EPA.lang.mediabutton
				},
				library : {
					type : 'image',
				},
				multiple : true,
				frame : 'select',
			});

			uploader
					.on(
							'select',
							function() {
								var selection = uploader.state().get(
										'selection');
								selection
										.map(function(att) {
											var attachment = att.toJSON();
											var order = EPA.maxOrder += 1;

											// make row from template (see https://wordpress.org/support/topic/uncaught-referenceerror-alternate-is-not-defined?replies=9#post-8296155)
											var rowTemplate = _.template(EPA.rowtemplate);
											var row = rowTemplate({
													alternate : ( order % 2 === 0 ? ' class="alternate"' : ''),
													id : attachment.id,
													imgurl : (attachment.sizes.thumbnail == undefined ? attachment.sizes.full.url
															: attachment.sizes.thumbnail.url),
													order : order,
													title : attachment.title,
													caption : attachment.caption
											});
											$('.easy-photo-album-table tr:last').after(row);
										});

								refresh();
							}, this);
		}
		// Set post id
		wp.media.model.settings.post.id = $('#post_ID').val();

		uploader.open();
	};

	// Deletes the photo fromt the given element (DOMelement or jQuery object)
	// from the album.
	EPA.deletePhoto = function(element) {
		var id = parseInt(getRowFromElement(element).attr('data-epa-id'), 10);
		;
		if (id && confirm(EPA.lang.deleteconfirm.format(getTitle(id)))) {
			var deletedorder = getOrder(id);

			var $row = getRowFromElement(element);
			$row.remove();

			// Correct the order numbers
			EPA.maxOrder -= 1;
			for ( var i = deletedorder; i <= EPA.maxOrder; i++) {
				var needfix = getIdFromOrder(i + 1);
				setOrder(needfix, i);
			}
			refresh();
		}
	};

	// On init: Constructor
	(function() {
		// Load only if there is a table
		if ($(".easy-photo-album-table").length > 0) {
			if (EPA.settingName == undefined) {
				console
						.info('EasyPhotoAlbum: settingName not set, use default');
				EPA.settingName = 'EasyPhotoAlbums';
			}
			if (EPA.maxOrder == undefined) {
				console
						.info('EasyPhotoAlbum: maxOrder not set, calculate value');
				EPA.maxOrder = $('#the-list tr').length;
			}

			refresh();

			// Sortable
			$(".easy-photo-album-table tbody").sortable(
					{
						axis : 'y',
						handle : '.column-image img',
						placeholder : 'sortable-placeholder',
						forcePlaceholderSize : true,
						cursor : 'move',
						opacity : 0.6,
						update : function(event, ui) {
							// Correct the order after
							// dragging:
							jQuery('.easy-photo-album-table tbody tr').each(
									function(index, elm) {
										// the
										// current
										// index
										// is
										// the
										// order
										setOrder(parseInt(
												getRowFromElement(elm).attr(
														'data-epa-id'), 10),
												index);
									});
							// reset colum differents:
							$('.easy-photo-album-table tr:nth-child(odd)')
									.addClass('alternate');
							$('.easy-photo-album-table tr:nth-child(even)')
									.removeClass('alternate');
							refresh();
						}
					});
			// build on some button clicks
			$('#publish, #doaction, #doaction2, #post-preview, #save-post').click(build);

			// help boxes
			var open = null;
			$('.epa-help').click(
					function(e) {
						// hide any open help texts
						$('.epa-help-content').fadeOut('fast');
						// show it
						if (open != $(this).data('helpid')) {
							$('#' + $(this).data('helpid')).fadeIn('fast').css(
									"display", "inline-block");
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
		}
	})();

	// } (end public)

})(window.TVproductions.EasyPhotoAlbum = window.TVproductions.EasyPhotoAlbum || {}, jQuery);

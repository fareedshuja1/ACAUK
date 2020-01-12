<?php

if (! defined ( 'ABSPATH' ))
	exit();

if (! class_exists ( '_WP_Editors' ))
	require (ABSPATH . WPINC . '/class-wp-editor.php');

/**
 * Adds translations and data to access in JS Tiny MCE Editor.
 *
 * @author Aubrey Portwood
 * @since  1.3.6 Added is_albums so we can detect if there are
 *                     published albums and show/not show the
 *                     TinyMCE editor button.
 *
 * @return array JSON of translated strings for TinyMCE Editor.
 */
function easy_photo_album_insert_dialog_translation() {
	$strings = array(
		'dlg_title' => __( 'Insert a Photo Album', 'epa' ),
		'select_album' => __('Select an album to insert', 'epa'),
		'show_title' => __('Show the title', 'epa'),
		'display_label' => _x('Display album', 'Like: Display album full OR Display album excerpt', 'epa'),
		'excerpt' => _x('Excerpt', 'Display album excerpt', 'epa'),
		'full' => _x('Full', 'Display album full', 'epa'),
		'insert' => _x('Insert', 'button text', 'epa'),
		'cancel' => _x('Cancel', 'button text', 'epa'),
		'title_loading' => _x('Loading...', 'Loading dialog title', 'epa'),
		'loading' => __('Loading albums ...', 'epa'),
		'nonce' => wp_create_nonce('epa_insert_dlg'),

		// Are there album's?
		'is_albums' => count( get_posts( array_merge( apply_filters( 'epa_albums_get_posts', array(
			'post_type'      => EPA_PostType::POSTTYPE_NAME,
			'post_status'    => 'publish',
			'posts_per_page' => apply_filters( 'epa_albums_get_posts_numberposts', 5000 ),

		// Don't include this in epa_albums_get_posts.
		), array(

			// We only need a count, so this is easier.
			'fields'         => 'ids',
		) ) ) ) ),
		//'spinner' => admin_url('images/wpspin_light.gif'),
	);
	$locale = _WP_Editors::$mce_locale;
	$translated = 'tinyMCE.addI18n("' . $locale . '.epa", ' . json_encode ( $strings ) . ");\n";

	return $translated;
}

/*
 * @TODO <Aubrey Portwood> Why is this being assigned to such an arbitrary variable name?
 */
$strings = easy_photo_album_insert_dialog_translation();

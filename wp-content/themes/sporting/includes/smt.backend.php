<?php

	global $smt_global_options;
	
	
	
	add_action( 'after_setup_theme', 'smt_admin_setup' ); // Load default theme settings
	add_action( 'admin_menu', 'smt_load_menu' );
	add_action( 'wp_ajax_smt_update_options', 'smt_update_option' ); // Save new theme's options
	add_action( 'widgets_admin_page', 'smt_custom_sidebars' ); // Manage sidebars
	add_action( 'wp_ajax_smt_add_sidebar', 'smt_add_sidebar' ); // Save new sidebar
	add_action( 'admin_init', 'smt_meta_boxes', 1 ); // Page sidebar
	add_action( 'save_post', 'smt_save_pagemeta' ); // Save attributes of a page
	add_action( 'admin_print_scripts-widgets.php', 'smt_admin_scripts' ); // 
	add_action( 'admin_print_scripts-post.php', 'smt_admin_scripts' ); // 
	add_action( 'admin_print_scripts-post-new.php', 'smt_admin_scripts' ); // 
	add_action( 'admin_init', 'smt_dashboard_init' );
	
	
	
	
	
	/**
	* Load default theme settings
	*/
	function smt_admin_setup() {
			
		require_once get_template_directory().'/includes/settings.php';
		
		if ( isset( $_GET[ 'reset' ] ) && $_GET[ 'reset' ] == 'true' ) {
			smt_reset_settings();
		}
	}
	
	
	
	
	
	
	
	
	/**
	* Reset smthemes settings
	*/
	function smt_reset_settings() {
		
		global $smt_default_options, $smt_global_options;
		
		
		$theme = wp_get_theme( );
		
		if ( !isset( $_GET[ 'page' ] ) ) 
			return;
		
		
		$page = explode( '_', $_GET[ 'page' ] );
		
		if ( !isset( $page[ 1 ] ) || !isset( $smt_default_options[ $page[ 1 ] ] ) ) 
			return;
		
		$content = $smt_default_options[ $page[ 1 ] ];
		foreach( $content[ 'content' ] as $param_name => $param ) {
			
			if( $param[ 'type' ] != 'group' ) {
				$smt_global_options[ $page[ 1 ] ][ $param_name ] = isset( $param[ 'value' ] ) ? $param[ 'value' ] : '';
			} else {
				foreach( $param[ 'content' ] as $child_param_name  => $child_param ) {
					$smt_global_options[ $page[ 1 ] ][ $child_param_name ] =  isset( $child_param[ 'value' ] ) ? $child_param[ 'value' ] : '';
				}
			}
			
			
		}
	
		
		update_option( $theme['Name'].'_settings', $smt_global_options );
		
		wp_redirect( admin_url( 'admin.php?page=smt_' . $page[ 1 ] ) );
		exit;
			
		
		
	}
	
	
	
	/**
	* Include all needed theme's styles and scripts to the backend
	*/
	function smt_admin_scripts() {
		
		wp_enqueue_style( 'smt_admin_css', get_template_directory_uri() . '/styles/dashboard.css' );
		wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/styles/font-awesome.css' );
		wp_enqueue_media();
		wp_enqueue_script(
			'smt-mousewheel',
			get_template_directory_uri() . '/js/jquery.mousewheel.min.js',
			array( 'jquery' )
		);
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script(
			'smt-script',
			get_template_directory_uri() . '/js/dashboard.js',
			array( 'jquery' )
		);
		
		
	}
	
	
	
	
	
	
	/**
	* Manage Sidebars
	*/
	function smt_custom_sidebars() { ?>
			<div class="smt-sidebars_manage-container">
				
				<p>You can create additional sidebars for different pages on your website. Just create sidebar here, and choose it in the page settings for needed page.</p>
				
				<a class="smt_major_button smt_add_sidebar">Add Sidebar</a>	
				
			</div>
	<?php }
	
	
	
	
	
	
	
	/**
	* Manage Sidebars
	*/
	function smt_add_sidebar() {
		
		if ( !$sidebars=get_option( 'smt_sidebars' ) ) {
			$sidebars=array();
		}
		$name = 'smt_'.strtolower( str_replace( ' ', '', $_POST['title'] ) );
		$sidebars[ $name ]=array(
			'title'=>sanitize_text_field( $_POST['title'] ),
			'description'=>sanitize_text_field( $_POST['description'] ),
		);
		update_option( 'smt_sidebars', $sidebars );
		
		die();
		
	}
	
	
	
	
	
	
	
	/**
	* Page Sidebars
	*/
	function smt_meta_boxes() {  
	
		add_meta_box( 'smt_pagemeta', 'Content layout', 'smt_pagemeta_handler', 'page', 'side', 'high'  );  
		
	}
	
	function smt_pagemeta_handler ( $post ) {
		global $wp_registered_sidebars;
		
		wp_nonce_field( 'smt_pagemeta', 'smt_pagemeta_nonce' );
		$value = get_post_meta( $post->ID, '_smt_pagemeta_value_key' );
		
		if ( !isset( $value[ 0 ][ 'sidebar' ] ) )
			$value[ 0 ][ 'sidebar' ]='smt_default_sidebar';
	
		if ( !isset( $value[ 0 ][ 'sidebar_position' ] ) )
			$value[ 0 ][ 'sidebar_position' ]='right';
		
		$sidebars=array( 'no_sidebar' => 'No Sidebar' );
		foreach( $wp_registered_sidebars as $sidebar ) {
			$sidebars[ $sidebar[ 'id'] ]=$sidebar[ 'name' ];
		}
		
		
		
		smt_show_option ( array(
			'type' => 'select',
			'title' => 'Page Sidebar',
			'name' => 'sidebar',
			'value' => $value[0][ 'sidebar' ],
			'params' => $sidebars
		) );
		
		smt_show_option ( array(
			'type' => 'select',
			'title' => 'Sidebar Position',
			'name' => 'sidebar_position',
			'value' => $value[0][ 'sidebar_position' ],
			'params' => array(
				'left' => 'Left',
				'right' => 'Right'
			)
		) );
			
	}
	
	
	/**
	* Save Sidebars Layout
	*/
	function smt_save_pagemeta( $post_id ) {
		
		if ( ! isset( $_POST['smt_pagemeta_nonce'] ) ) {
			return;
		}
		
		if ( ! wp_verify_nonce( $_POST['smt_pagemeta_nonce'], 'smt_pagemeta' ) ) {
			return;
		}
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		if ( !isset( $_POST['post_type'] ) || !( ( 'page' == $_POST['post_type'] && current_user_can( 'edit_page', $post_id ) )  || ( 'post' == $_POST['post_type'] && current_user_can( 'edit_post', $post_id ) )  ) ) {
			return;
		}
		
		
		if ( isset( $_POST['sidebar'] ) )
		$value[ 'sidebar' ] = sanitize_text_field( $_POST['sidebar'] );
		if ( isset( $_POST['sidebar_position'] ) )
		$value[ 'sidebar_position' ] = sanitize_text_field( $_POST['sidebar_position'] );
		
		update_post_meta( $post_id, '_smt_pagemeta_value_key', $value );
		
	}
	
	
	
	
	/**
	* Dashboard init
	*/
	function smt_dashboard_init() {
		
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) )
			return;
		
		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter('mce_external_plugins', 'add_smpanel_tinymce_plugin');
			add_filter('mce_buttons_3', 'register_smpanel');
		}
		//add_editor_style( get_template_directory_uri() . '/css/dashboard.css' );
		
	}
	function add_smpanel_tinymce_plugin($plugin_array) {
		
		
		// $plugin_array[ 'mousewheel' ] = get_template_directory_uri() . '/js/jquery.mousewheel.min.js';
		// $plugin_array[ 'smt_dashboard' ] = get_template_directory_uri() . '/js/dashboard.js';
		$plugin_array[ 'smt_panel' ] = get_template_directory_uri() .'/js/editor.js';
		return $plugin_array;
		
	}
	function register_smpanel($buttons) {
		
		array_push( $buttons, 'smt_gglmap', 'smt_tooltip', 'smt_columns', 'smt_highlights' );
		return $buttons;
		
	}
	
	
	
	/**
	* Build left side menu
	*/
	function smt_load_menu() {
		
		global $smt_default_options;
		
		$theme = wp_get_theme( );
		add_menu_page( 'Theme', $theme['Name'], 'manage_options', 'smt_settings', 'smt_settings_handle', '', 64 );
		foreach( $smt_default_options as $name => $section ) {
			
			$s=add_submenu_page( 'smt_settings', $section['name'], $section['name'], 'manage_options', 'smt_'.$name, 'smt_settings_handle' );
			add_action( 'admin_print_scripts-'.$s, 'smt_admin_scripts' ); // 
			
		}
		remove_submenu_page( 'smt_settings', 'smt_settings' );
		
	}
	
	
	
	/**
	* Save new theme's options
	*/
	function smt_update_option() {
		
		global $smt_global_options;
		
		$section = $_POST[ 'smt_section_name' ];
		unset( $_POST[ 'smt_section_name' ] );
		unset( $_POST[ 'action' ] );
		$smt_global_options[ $section ] = removeslashes( $_POST );
		
		$theme = wp_get_theme( );
		update_option( $theme['Name'].'_settings', $smt_global_options );
		
		wp_die();
		
	}
	
	
	
	/**
	* Handle function for theme's settings
	*/
	function smt_settings_handle() { 
		
		global $smt_default_options;
		
		
		
		
			$theme = wp_get_theme( );
			
			if ( !isset( $_GET[ 'page' ] ) ) 
				return;
			
			
			$page = explode( '_', $_GET[ 'page' ] );
			
			if ( !isset( $page[ 1 ] ) || !isset( $smt_default_options[ $page[ 1 ] ] ) ) 
				return;
		
		
			
		?>
		
		<div class="wrap smt">
				<div class="smt_settings_header">
					<div class="boxed-container">
					
						<a id="smt_logo" href="https://smthemes.com/">
							<img src="<?php echo get_template_directory_uri()?>/includes/images/logo.png" alt="SMThemes.com" style='' />
						</a>
						<div class="smt_theme_info">
							<h1><?php echo $theme['Name']; ?>'s Settings</h1>
							<cite>Theme by SMThemes.com</cite>
						</div>
						
					</div>
				</div>
				
				<div class="smt_settings_title">
					<div class="boxed-container">
					
						<div class="icon">
							<?php echo $smt_default_options[ $page[ 1 ] ][ 'icon' ]; ?>
						</div>
						<h2><?php echo $smt_default_options[ $page[ 1 ] ][ 'name' ]; ?></h2>
					
					</div>
				</div>
				
				<div class="boxed-container smt_settings_body">
					
					
					<input type="hidden" name="smt_section_name" value="<?php echo $page[ 1 ]; ?>" />
					
					<?php foreach( $smt_default_options[ $page[ 1 ] ][ 'content' ] as $option ) { 
						
						$val = smt_getOption( $page[ 1 ], $option[ 'name' ] );
						
						if ( $val !== null ) {
							$option[ 'value' ] = $val;
						}
						$option[ 'parent' ] = $page[ 1 ];
						smt_show_option( $option );
						
					} ?>
					
					
					
				</div>
				
				<?php if ( isset( $smt_default_options[ $page[ 1 ] ][ 'editable' ] ) && $smt_default_options[ $page[ 1 ] ][ 'editable' ] == true ) { ?>
				
				<div id="smt_settings_buttons">
					<div class="boxed-container">
					
						<div class="icon saving_settings">&#xf013;</div>
						
						<a class="smt_button" id="smt_reset_settings" href="<?php echo admin_url( 'admin.php?page=smt_' . $page[ 1 ] . '&reset=true' ); ?>">Reset to defaults</a><div class="smt_major_button" id="smt_save_settings">Save Changes</div>
					
					</div>
				</div>
				
				<?php } ?>
				
				<script>
					var ajaxurl='<?php echo admin_url( 'admin-ajax.php' ); ?>';
				</script>
		</div>
		
	<?php }
			
			
			
			
			
			
			
	/**
	 *  Save new theme's options
	 */
	function updateOptions(  ) { global $smt_global_options;
		
		$theme = wp_get_theme( );
		
		
		
	}
	
	
	
	
	
	
	
	function smt_show_option( $option ) { ?>
		
		<div class="smt_option <?php echo 'smt_option_type_'.$option[ 'type' ]; ?>"<?php if ( isset( $option[ 'depend' ] ) ) echo ' depend="' . $option[ 'depend' ] . '" case="' . $option[ 'case' ] . '"'; ?>>
		
		<?php if( isset( $option[ 'hint' ] ) ) { ?>
			<div class="smt_hint">
				<div class="smt_hint_trigger"></div>
				<div class="smt_hint_text"><?php echo $option[ 'hint' ]; ?><div class="icon close">&#xf00d;</div></div>
			</div>
		<?php } ?>
		<?php
		
			switch( $option[ 'type' ] ) {
				
				case 'text': ?>
						<label><?php echo $option[ 'title' ]; ?>:</label>
						<div class="smt_text_input smt_ic">
							<input type="text" placeholder="<?php echo $option[ 'title' ]; ?>" value="<?php echo $option[ 'value' ]; ?>" name="<?php echo $option[ 'name' ]; ?>" autocomplete="off" />
						</div>
				<?php break;
				
				case 'check': ?>
						<label><?php echo $option[ 'title' ]; ?>:</label>
						<input type="checkbox" value="1" name="<?php echo $option[ 'name' ]; ?>" class="smt_checkbox_ui" autocomplete="off"  <?php echo ( $option['value'] ) ? 'checked="checked"' : ''?> />
				<?php break;
				
				case 'select': ?>
						<label><?php echo $option[ 'title' ]; ?>:</label>
						<select name="<?php echo $option[ 'name' ]; ?>" autocomplete="off">
							<?php
								foreach( $option['params'] as $value=>$caption ) {
									?><option value='<?php echo $value?>'<?php echo ( $option['value'] == $value ) ? ' selected="selected"' : ''?>><?php echo $caption?></option><?php
								}
							?>
						</select>
				<?php break;
				
				case 'multiselect': ?>
					<?php
						if ( !is_array( $option['value'] ) ) $option['value'] = array();
					?>
						<label><?php echo $option[ 'title' ]; ?>:</label>
						<select name="<?php echo $option[ 'name' ]; ?>" autocomplete="off" multiple>
							<?php
								foreach( $option['params'] as $value=>$caption ) {
									?><option value='<?php echo $value?>'<?php echo ( in_array( $value, $option['value'] ) ) ? ' selected="selected"' : ''?>><?php echo $caption?></option><?php
								}
							?>
						</select>
				<?php break;
				
				case 'image': ?>
						<label><?php echo $option[ 'title' ]; ?>:</label>
						<input type="hidden" name="<?php echo $option[ 'name' ]; ?>" value="<?php echo $option[ 'value' ]; ?>" class="smt_image_ui" autocomplete="off" />
				<?php break;
				
				case 'textarea': ?>
						<label><?php echo $option[ 'title' ]; ?>:</label>
						<div class="smt_text_input smt_ic">
							<textarea name="<?php echo $option[ 'name' ]; ?>"><?php echo $option[ 'value' ]; ?></textarea>
						</div>
				<?php break;
				
				case 'group': ?>
						
						<div class="smt_group_caption"><?php echo $option[ 'title' ]; ?></div>
						<div class="smt_group_content">
							<?php
								foreach( $option[ 'content' ] as $param ) {
									$val = smt_getOption( $option[ 'parent' ], $param[ 'name' ] );
						
									if ( $val != null ) {
										$param[ 'value' ] = $val;
									}
									$param[ 'parent' ] = $option[ 'parent' ];
									smt_show_option( $param );
								}
							?>
						</div>
				<?php break;
				
				case 'icon': ?>
						<label><?php echo $option[ 'title' ]; ?>:</label>
						<input type="hidden" name="<?php echo $option[ 'name' ]; ?>" value="<?php echo $option[ 'value' ]; ?>" class="smt_icon_ui" autocomplete="off" />
				<?php break;
				
				case 'paragraph': ?>
				
					<p><?php echo $option[ 'value' ]; ?></p>
				
				<?php break;
				
				case 'updates': ?>
				
					<div id="smt_updates"></div>
				
				<?php break;
				
				case 'activator': 
				
				
					$smt_hash = md5( rand( 0, mktime() ) );
					update_option( 'smt_hash', $smt_hash );
					$theme = wp_get_theme( );
					?>
				
					<div id="smt_activator"
						data-theme="<?php echo strtolower( $theme[ 'Name' ] ); ?>"
						data-hash="<?php echo $smt_hash; ?>"
						data-url="<?php echo get_template_directory_uri(); ?>"
						data-path="<?php echo dirname( __FILE__ ); ?>"
					></div>
				
				<?php break;
				
				default: 
				
					if ( is_callable( 'smt_show_option_'.$option[ 'type' ] ) ) {
						
						call_user_func_array( 'smt_show_option_'.$option[ 'type' ],  array( $option ) ); 
						
					} else { ?>
						<h1>UNDEFINED TYPE <?php echo $option[ 'type' ]; ?></h1>
					<?php }
				
			}
		?>
		
		
		</div>
	<?php }
	
	
	
	function removeslashes( $var ) {
		if (is_array($var)) foreach ($var as $key=>$value) {
			$var[$key]=removeslashes($value);
		} else {
			return stripslashes($var);
		}
		return $var;
	}
	
	
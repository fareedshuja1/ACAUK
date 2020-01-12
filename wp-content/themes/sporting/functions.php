<?php

	include_once ( get_template_directory().'/includes/smt.main.php' );
	
	add_action( 'after_setup_theme', 'smt_setup' ); 	// General theme configuration
	add_action( 'wp_enqueue_scripts', 'smt_enqueues' ); 	// Enque all needed theme's styles and scripts
	add_action( 'init', 'smt_init' ); // Register menus on theme initialization
	add_action( 'widgets_init', 'smt_widgets_init' ); // Add sidebars support
	add_action( 'wp_head', 'smt_head' ); // Add theme code to the page head
	add_action( 'wp_footer', 'smt_footer' ); // Add theme code to the page footer
	add_filter( 'wp_title', 'smt_wp_title', 10, 2 );
	add_filter( 'template_include', 'smt_dynamic_template', 99 );
	
	
	
	/**
	 * General theme configuration
	 */
	function smt_setup() {
	
		// Enable support for Post Thumbnails, and declare two sizes.
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'woocommerce' );
		set_post_thumbnail_size( 240, 160, true );
		add_image_size( 'smt_slide', 1000, 500, true );
		add_image_size( 'smt_showroom', 180, 180, true );
		add_image_size( 'smt_related', 272, 160, true );
		add_image_size( 'smt_sidebarposts', 60, 60, true );
		
	}
	
	
	
	
	
	/**
	* Include all needed theme's styles and scripts
	*/
	function smt_enqueues() {
		
		wp_enqueue_style( 'master-style', get_template_directory_uri() . '/styles/main.css' );
		wp_enqueue_style( 'style', get_stylesheet_uri() );
		wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/styles/font-awesome.css' );
		
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
		
		wp_enqueue_script(
			'superfish',
			get_template_directory_uri() . '/js/superfish.js',
			array( 'jquery' )
		);
		
		wp_enqueue_script(
			'swipe',
			get_template_directory_uri() . '/js/jquery.touchSwipe.min.js',
			array( 'jquery' )
		);
		
		wp_enqueue_script(
			'cycle',
			get_template_directory_uri() . '/js/jquery.cycle.all.js',
			array( 'jquery' )
		);
		
		wp_enqueue_script(
			'smt_frontend',
			get_template_directory_uri() . '/js/frontend.js',
			array( 'jquery' )
		);
		
	}
	
	
	
	
	
	/**
	* Theme initialization configuration
	*/
	function smt_init(  ) {
		
		register_nav_menus(
			array(
				'main-menu' => 'Main Menu'
			)
		);
		
		register_nav_menus(
			array(
				'extra-menu' => 'Extra Menu'
			)
		);
  
	}

	
	
	
	/**
	* Setting up sidebars and widgets
	*/
	function smt_widgets_init() {
		
		register_sidebar(array(
			'name' => 'Default sidebar',
			'id' => 'smt_default_sidebar',
			'description' =>'Default sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="caption"><h4>',
			'after_title' => '</h4></div>'
		));
		
		register_sidebar(array(
			'name' => 'Footer',
			'id' => 'smt_footer_sidebar',
			'description' =>'Footer',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="caption"><h4>',
			'after_title' => '</h4></div>'
		));
		
		
		if ( $custom_sidebars = get_option( 'smt_sidebars' ) ) {
			foreach( $custom_sidebars as $name => $custom_sidebar ) {
				register_sidebar(array(
					'name' => $custom_sidebar['title'],
					'id' => $name,
					'description' =>$custom_sidebar['description'],
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<div class="caption"><h4>',
					'after_title' => '</h4></div>'
				));
			}
		}
		
		
		register_widget( 'SMT_Posts' );
		register_widget( 'SMT_SocialProfiles' );
		register_widget( 'SMT_Comments' );
		register_widget( 'SMT_VideoFeed' );
		
	}

	
	
	
	/**
	* Add theme code to the page Footer
	*/
	function smt_footer( ) { ?>
		
		<?php echo smt_getOption( 'integration','footercode' ); ?>
		
	<?php }

	
	
	
	/**
	* Add theme code to the page head
	*/
	function smt_head( ) { ?>
		
		<?php if ( smt_getOption( 'integration', 'rss' ) ) { ?>
			<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="<?php echo smt_getOption( 'integration', 'rss' ) ?>" />
		<?php }  ?>
		
		<style type="text/css">
			<?php echo smt_getOption( 'integration','css' )?>
		</style>
		
		<?php echo smt_getOption( 'integration','headcode' ); ?>
		
		<?php smt_seo(); ?>
		
	<?php }

	
	
	
	/**
	* Show page title
	*/
	function smt_wp_title( $title, $sep ) {
		
		if ( is_feed() ) {
			return $title;
		}
		
		if (is_home()) { 
			return get_bloginfo('name'); 
		} else 
			return sprintf( '%1$s %2$s', $title, get_bloginfo('name') ); 
			
	}
	
	
	
	/**
	 * Template for dynamic content loading
	 */
	function smt_dynamic_template( $template ) {

		if ( isset( $_POST[ 'smt_action' ] ) && $_POST[ 'smt_action' ] == 'smt_load_dynamic_content' ) {
			$new_template = locate_template( array( 'dynamic.php' ) );
			if ( '' != $new_template ) {
				return $new_template ;
			}
		}

		return $template;
	}
	
	
	
	
	
	
	
	/**
	* Comment template
	*/
	function smt_comment( $comment, $args, $depth ) {
		global $post;
		?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<div class="comment-avatar">
				<?php echo get_avatar( $comment, 60 ); ?>
			</div>
			<div class="comment-body" id="div-comment-<?php comment_ID(); ?>">
				<div class="comment-meta vcard">
					
					<?php
						printf( '<span class="comment-author-name accent">%1$s %2$s</span>',
							get_comment_author_link(),
							// If current post author is also comment author, make it known visually.
							( $comment->user_id === $post->post_author ) ? '<span> ' . smt_translate( 'Post author' ) . '</span>' : ''
						);
					?>
					<div class="comment-date-and-edit descent">
						<time datetime="<?php echo get_comment_time( 'c' ); ?>"><?php echo sprintf( smt_translate( 'commenttime' ), get_comment_date(), get_comment_time() ); ?></time>
						<?php edit_comment_link( smt_translate( 'edit' ), '', '' ); ?>
					</div>
				</div>
			
				
				<div class="comment-content">
					<?php comment_text(); ?>
				</div>
				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => smt_translate( 'reply' ), 'after' => ' <span class="icon">&#xf107;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div>
			</div>
		</li>
		<?php
		
	}
	
	
	
	
	
	
	
	/**
	* Default menu for the theme
	*/
	function smt_default_menu() { ?>
		<div class="menu-themedefault-container ">
			<ul class="nav-menu">
				<li><a href="<?php echo home_url(); ?>">Home</a></li>
				<?php wp_list_pages('title_li=&'); ?>
			</ul>
		</div>
	<?php }
	
	
	
	
	
	/**
	* Loading features for administrating control on backend
	*/
	if ( is_user_logged_in() && current_user_can('administrator') && is_admin() ) include_once ( get_template_directory().'/includes/smt.backend.php' );
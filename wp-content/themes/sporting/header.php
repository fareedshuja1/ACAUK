<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width">
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="shortcut icon" href="<?php echo smt_getOption( 'general', 'favicon'); ?>" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<script>
			var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
			var gglapikey= '<?php echo smt_getOption( 'integration', 'gglapikey' ); ?>';
		</script>
		<?php wp_head(); ?>
	</head>


	<body <?php body_class(); ?>>
		<div id="page">
		
			<header class="site-header">
								
				<!-- Mobile Menu Trigger -->
					<div id="mobile-menu-trigger">
					<a href="#" class="icon">&#xf0c9;</a>
					</div>
				<!-- /Mobile Menu Trigger -->
				<div id="logo-and-menu">
				<div class="boxed-container">
				
					<!-- Logo -->
					<?php get_template_part( 'extras/logo' ); ?>
					<!-- / Logo -->
										
					<!-- extra Menu -->
					<nav id='extra-menu' class="site-navigation<?php if( smt_getOption( 'menu', 'mobile' )!= 'extra-menu' ) echo ' mobile-menu'; ?>" role="navigation">
						<?php wp_nav_menu(array( 
							'depth'=>0,
							'theme_location' => 'extra-menu',
							'menu_class'    => 'nav-menu',
							'fallback_cb' => 'smt_default_menu'
						));	?>
					</nav>	
					<!-- / extra Menu -->
					
					<div class="clear"></div>
				</div>
				</div>
				
				
				<div id="search-and-menu">					
				<div class="boxed-container">
					
					<!-- Search -->
					<div class="headersearch" title="">
						<?php get_search_form();?>
					</div>
					<!-- / Search -->
					
					<!-- Main Menu -->
					<nav id='main-menu' class="site-navigation<?php if( smt_getOption( 'menu', 'mobile' )!= 'main-menu' ) echo ' mobile-menu'; ?>" role="navigation">
						<?php wp_nav_menu(array(
							'depth'=>0,
							'theme_location'=>'main-menu',
							'menu_class'=>'nav-menu',
							'fallback_cb' => 'smt_default_menu'
						)); ?>
					</nav>
					<!-- / Main Menu -->
					
					<div class="clear"></div>
				</div>
				</div>
								
					
				<div class="boxed-container">
					<!-- Slider -->
					<?php get_template_part( 'extras/slider' ); ?>
					<!-- / Slider -->
					<div class="clear"></div>
				</div>
				
				<!-- Showroom -->
					<?php get_template_part( 'extras/showroom' ); ?>
				<!-- / Showroom -->						
				
			</header>
			
			
			<div id="wrapper" class="site-content" role="main">
				<div class="boxed-container">
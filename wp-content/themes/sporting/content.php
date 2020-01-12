<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 */
 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( ); ?>>
	
	
	<!-- ========== Post Featured Image ========== -->
	<?php if ( has_post_thumbnail() ) { // if there is featured image for this post you may wrapper for it ?>
		<div class="featured-img-container">
		
			<span class='post-date updated'>
				<div class="day"><?php echo get_the_date('d'); ?></div>
				<?php echo get_the_date('M'); ?>
			</span>
			
			<?php the_post_thumbnail(
					'post-thumbnail',
					array( 'class' => 'featured_image', 'style' => smt_thumbnail_style() )
				); ?>
				
		</div>
	<?php } else { ?>
		
		<span class='post-date updated'>
			<div class="day"><?php echo get_the_date('d'); ?></div>
			<?php echo get_the_date('M'); ?>
		</span>
		
	<?php } ?>
	
	<!-- ========== Post Title ========== -->
	<?php  //Title
		if (!is_single()&&!is_page()) { ?>
			<h2 class='entry-title'><a href="<?php the_permalink(); ?>" title="<?php printf( smt_translate( 'permalink' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h2>
		<?php } else { ?>
			<h1 class='entry-title'><?php the_title(); ?></h1>
	<?php } ?>
	
	<span class='post-categories'><?php the_category(', '); ?></span> 
	
	
	
	<!-- ========== Post content  ========== -->
	<?php if ( !is_single() ) : ?>
		
		<!-- ========== Post content in posts feed ========== -->
		<div class="entry-summary">
			<?php if( smt_getOption( 'layout', 'cuttxton' ) ) {
				if ( ! post_password_required() ) { 
					smt_excerpt( 'echo=1' );
				} else the_content( );
			}  else { 
				the_content(); 
			} ?>
			<a href='<?php the_permalink(); ?>' class='button'><?php echo smt_translate( 'readmore' ); ?></a>
									
			<!-- ========== Post Meta ========== -->
			<div class="entry-meta">
				<span class="post-author vcard"><span class="fn"><?php echo the_author_posts_link();?></span></span>
				<?php if(comments_open( get_the_ID() )) { ?>
					<span class='post-comments'><?php comments_popup_link( smt_translate( 'noresponses' ), smt_translate( 'oneresponse' ), smt_translate( 'multiresponse' ) ); ?></span>
				<?php } edit_post_link( smt_translate( 'edit' ), '     |     <span class="edit-link">', '</span>' ); ?>
			</div>
			
		</div><!-- .entry-summary -->
	
	<?php else : ?>
	
	
		<!-- ========== Post content in single post page ========== -->
		<div class="entry-content content-block">
			<?php
				the_content( smt_translate( 'readmore' ) );
				wp_link_pages( array(	
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'smtheme' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
				) );
			?>
		</div><!-- .entry-content -->
					
		<!-- ========== Post Meta ========== -->
		<div class="entry-meta">
			<?php if(comments_open( get_the_ID() )) { ?>
				<span class='post-comments'><?php comments_popup_link( smt_translate( 'noresponses' ), smt_translate( 'oneresponse' ), smt_translate( 'multiresponse' ) ); ?></span>
			<?php } edit_post_link( smt_translate( 'edit' ), '     |     <span class="edit-link">', '</span>' ); ?>
			<span class="post-author vcard"><span class="fn"><?php echo the_author_posts_link();?></span></span>
		</div>
		
		<!-- ========== Post tags in single post page ========== -->
		<?php 
			$tag_list = get_the_tag_list( '', ', ' );
			if ( $tag_list ) { ?>
				<div class="tags content-block"><?php echo smt_translate( 'tags' ); ?>:&nbsp;<?php echo $tag_list; ?></div>
			<?php }
		?>
		
		
		<!-- ========== Related Posts in single post page ========== -->
		<?php get_template_part( 'relatedposts' ); ?>
		
		
		<!-- ========== Post comments in single post page ========== -->
		<?php 
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
		?>
		
		
	<?php endif; ?>
	
	
	
	
	
	
	
	<div class="clear"></div>
</article><!-- #post-## -->

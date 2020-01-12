<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 */

?>
        
	<article>
	
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<!-- ========== Post Featured Image ========== -->
		<?php if ( has_post_thumbnail() ) { // if there is featured image for this post you may wrapper for it ?>
			
			<?php the_post_thumbnail(
					'post-thumbnail',
					array( 'class' => 'featured_image', 'style' => smt_thumbnail_style() )
				); ?>
				
		<?php } ?>
	
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
		
		<div class="clear"></div>
		
	</article>
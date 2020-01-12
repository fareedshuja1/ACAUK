<?php
/**
 * The template for displaying a "No posts found" message
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<article>
	<h1 class='entry-title'><?php echo smt_translate( 'nothingfound' ); ?></h1>
	
	<div class="entry-content content-block">
		<?php get_search_form(); ?>
	</div>
</article>

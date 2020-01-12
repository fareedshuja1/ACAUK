<?php
	
		
	
	if ( ! ( ( is_front_page() && smt_getOption( 'showroom', 'homepage' ) ) || ( !is_front_page() && smt_getOption( 'showroom', 'innerpage' ) ) ) ) return;
	
	
	
	
	switch ( smt_getOption( 'showroom','source' ) ) {
		case 'custom':
			$items = smt_getOption( 'showroom','custom_items' );
			break;
		case 'category':
			$args = array(
				'category' => smt_getOption( 'showroom','category' ),
				'numberposts' => smt_getOption( 'showroom','numberposts' ),
				'meta_key' => '_thumbnail_id'
			);			
			$pitems=get_posts( $args );
			foreach ($pitems as $post) {
				$item['thumbnail']=wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'smt_showroom');
				$item['thumbnail']=$item['thumbnail'][0];
				$item['link']= get_permalink($post->ID);
				$item['title']=$post->post_title;
				$item['content']= preg_replace('@(.*)\s[^\s]*$@s', '\\1', iconv_substr( strip_tags($post->post_content, ''), 0, 255, 'utf-8' )).'...';
				$items[]=$item;
			}
			break;
		case 'posts':
			$pitems = smt_getOption( 'showroom','posts' );
			foreach ($pitems as $post_id) {
				$post=get_post($post_id);
				$item['thumbnail']=wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'smt_showroom');
				$item['thumbnail']=$item['thumbnail'][0];
				$item['link']= get_permalink($post->ID);
				$item['title']=$post->post_title;
				$item['content']=preg_replace('@(.*)\s[^\s]*$@s', '\\1', iconv_substr( strip_tags($post->post_content, ''), 0, 255, 'utf-8' )).'...';
				$items[]=$item;
			}
			break;
		case 'pages':
			$pitems = smt_getOption( 'showroom','pages' );
			foreach ($pitems as $post_id) {
				$post=get_page($post_id);
				$item['thumbnail']=wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'smt_showroom');
				$item['thumbnail']=$item['thumbnail'][0];
				$item['link']= get_permalink($post->ID);
				$item['title']=$post->post_title;
				$item['content']=preg_replace('@(.*)\s[^\s]*$@s', '\\1', iconv_substr( strip_tags($post->post_content, ''), 0, 255, 'utf-8' )).'...';
				$items[]=$item;
			}
			break;
	}

	if ( !is_array( $items ) || count( $items ) == 0 ) return;
	
	if ( smt_getOption( 'showroom','source' ) == 'category' ) {
		$width=100 / smt_getOption( 'showroom','numberposts' );		
	} else {
		$width='';
	}
?>


	<style>
		@media only screen and (min-width:640px) {
			.showroom .showroom-item { width: <?php echo $width; ?>%; }
		}
	</style>
	
<div class='showroom-container'>
		
	<div class="boxed-container showroom">
	
				<?php foreach ($items as $num=>$item) { ?>
							
							<div class="showroom-item">
																
								<?php if (smt_getOption('showroom', 'showthumbnail')) { ?>
									<a href="<?php echo $item['link']?>" title=""><img src="<?php echo $item['thumbnail']?>" alt="<?php echo $item['title']?>" /></a>
								<?php } ?>
													
								<?php if (smt_getOption('showroom', 'showttl')) { ?>
									<a href="<?php echo $item['link']?>"><h3 class="showroom-title"><?php echo $item['title']?></h3></a>
								<?php } ?>
									
								<?php if (smt_getOption('showroom', 'showtext')) { ?>
									<p class="fp-description"><?php echo $item['content']?></p>
								<?php } ?>									
								
							</div>
							
				<?php } ?>	
		
		<div class="clear"></div>				
	</div>
</div>
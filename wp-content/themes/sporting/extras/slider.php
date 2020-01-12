<?php
	
		
	
	if ( ! ( ( is_front_page() && smt_getOption( 'slider', 'homepage' ) ) || ( !is_front_page() && smt_getOption( 'slider', 'innerpage' ) ) ) ) return;
	
	
	
	
	switch ( smt_getOption( 'slider','source' ) ) {
		case 'custom':
			$slides = smt_getOption( 'slider','custom_slides' );
			break;
		case 'category':
			$args = array(
				'category' => smt_getOption( 'slider','category' ),
				'numberposts' => smt_getOption( 'slider','numberposts' ),
				'meta_key' => '_thumbnail_id'
			);   
			$pslides=get_posts( $args );
			foreach ($pslides as $post) {
				$slide['thumbnail']=wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'smt_slide');
				$slide['thumbnail']=$slide['thumbnail'][0];
				$slide['link']= get_permalink($post->ID);
				$slide['title']=$post->post_title;
				$slide['content']= preg_replace('@(.*)\s[^\s]*$@s', '\\1', iconv_substr( strip_tags($post->post_content, ''), 0, 255, 'utf-8' )).'...';
				$slides[]=$slide;
			}
			break;
		case 'posts':
			$pslides = smt_getOption( 'slider','posts' );
			foreach ($pslides as $post_id) {
				$post=get_post($post_id);
				$slide['thumbnail']=wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'smt_slide');
				$slide['thumbnail']=$slide['thumbnail'][0];
				$slide['link']= get_permalink($post->ID);
				$slide['title']=$post->post_title;
				$slide['content']=preg_replace('@(.*)\s[^\s]*$@s', '\\1', iconv_substr( strip_tags($post->post_content, ''), 0, 255, 'utf-8' )).'...';
				$slides[]=$slide;
			}
			break;
		case 'pages':
			$pslides = smt_getOption( 'slider','pages' );
			foreach ($pslides as $post_id) {
				$post=get_page($post_id);
				$slide['thumbnail']=wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'smt_slide');
				$slide['thumbnail']=$slide['thumbnail'][0];
				$slide['link']= get_permalink($post->ID);
				$slide['title']=$post->post_title;
				$slide['content']=preg_replace('@(.*)\s[^\s]*$@s', '\\1', iconv_substr( strip_tags($post->post_content, ''), 0, 255, 'utf-8' )).'...';
				$slides[]=$slide;
			}
			break;
	}

	if ( !is_array( $slides ) || count( $slides ) == 0 ) return;
?>
	
<script>
	jQuery( 'body' ).addClass( 'has-slider' );
</script>
<div class='slider-container'>
	<div class="slider">
			<div class="fp-slides">
				<?php foreach ($slides as $num=>$slide) { ?>
							<div class="fp-slides-item">
								<div class='slider-bgr'></div>
								<div class="fp-thumbnail">
									<?php if (smt_getOption('slider', 'showthumbnail')) { ?>
									<a href="<?php echo $slide['link']?>" title=""><img src="<?php echo $slide['thumbnail']?>" alt="<?php echo $slide['title']?>" /></a>
									<?php } ?>
								</div>
								<?php if (smt_getOption('slider', 'showtext')||smt_getOption('slider', 'showttl')) { ?>
								<div class="fp-content-wrap">
									<div class="fp-content">
									
										<?php if (smt_getOption('slider', 'showttl')) { ?>
										<h3 class="fp-title"><?php echo $slide['title']?></h3>
										<?php } ?>
										
										<?php if (smt_getOption('slider', 'showtext')) { ?>
										<p class="fp-description"><?php echo $slide['content']?></p>
										<?php } ?>
										
										<?php if (smt_getOption('slider', 'showhrefs')) { ?>
											<a class="fp-more button" href="<?php echo $slide['link']?>"><?php echo smt_translate( 'readmore' );?></a>
										<?php } ?>
										
										<div class="clear"></div>
										
									</div>
								</div>
								<?php } ?>
							</div>
				<?php } ?>
			</div>
			
	</div>
</div>
<script type="text/javascript"><!--//--><![CDATA[//><!--
	jQuery(window).load(function() {
		jQuery( '.fp-slides, .fp-thumbnail img' ).css( 'height', jQuery( '.fp-slides' ).height() );
		jQuery('.fp-slides').cycle({
			fx: '<?php echo smt_getOption('slider','effect');?>',
			timeout: <?php echo smt_getOption('slider','timeout');?>,
			delay: 0,
			speed: <?php echo smt_getOption('slider','speed');?>,
			next: '.fp-next',
			prev: '.fp-prev',
			pager: '.fp-pager',
			continuous: 0,
			sync: 1,
			pause: <?php echo smt_getOption('slider','pause');?>,
			pauseOnPagerHover: 1,
			cleartype: true,
			cleartypeNoBg: true
		});
	});
//--><!]]></script>
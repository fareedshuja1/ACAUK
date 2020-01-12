
	<?php
		if ( !smt_getOption( 'layout', 'related' ) ) return;
		$postsCnt = smt_getOption( 'layout', 'relatedcnt' ); // number of posts to show
		$post_cnt = 0;
		$width=(100-($postsCnt-1)*3.8)/$postsCnt;
		
	?>
	
	
	<?php
	
		$postid = $post->ID;
		
		$ids = array( $postid );
		$tags = get_the_tags( $postid );
		$categories = get_the_category( $postid );
		
		// Search posts by tags
		if ( $tags ) {
			
			$tags_ids = array();
			foreach( $tags as $tag ) $tags_ids[] = $tag->term_id;
			$args=array(
				'tag__in' => $tags_ids,
				'post__not_in' => $ids,
				'showposts'=>$postsCnt,
				'meta_key' => '_thumbnail_id',
				'ignore_sticky_posts'=>true
			);
			
			$posts=get_posts($args);	
			if( count($posts)>0 ) {
				foreach ($posts as $p) {
					$post_cnt++;
					$ids[]=$p->ID;
				}
			}
			
		}
		
		if ( $categories && ( $post_cnt < $postsCnt ) && count($ids) > 1 ) {
			
			$category_ids = array();
			foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
			$args=array(
				'category__in' => $category_ids,
				'post__not_in' => $ids,
				'meta_key' => '_thumbnail_id',
				'showposts'=>$postsCnt-( count( $ids ) - 1 ),
				'ignore_sticky_posts'=>true
			);
			$posts=get_posts($args);
			if( count($posts)>0 ) {
				foreach ($posts as $p) {
					$post_cnt++;
					$ids[]=$p->ID;
				}
			}
			
		}
		
		unset($ids[0]);
		
		if ( count( $ids ) > 0 ) {
			
			?>
			<div class="smt-related-posts content-block">
				<style>.smt-related-posts .smt-related-item {width:<?php echo $width; ?>%;}</style>
				<h3><?php echo smt_translate( 'relatedposts' ); ?></h3>
			<?php
			
			$args = array ( 'post__in' => $ids, 'posts_per_page' => $postsCnt, 'ignore_sticky_posts'=>true );
			
			$items = new WP_Query( $args ); 
			if ( $items->have_posts() ) :
				?><div class="smt-related-container"><?php
				while ( $items->have_posts() ) : $items->the_post(); 
				?>
					
								<div class='smt-related-item'>
									<a href="<?php the_permalink(); ?>"><?php echo preg_replace('/\sheight="\d+"/', '', get_the_post_thumbnail( $post->ID, 'smt_related' )); ?></a>
									<h5><a href="<?php the_permalink(); ?>"><?php echo $post->post_title; ?></a></h5>
								</div>
								
							<?php
				endwhile;
				?></div><?php
			else :
				echo '<p>'.smt_translate( 'norelatedposts' ).'</p>';
			endif;
			wp_reset_query(); 
			?>
			<div class="clear"></div>
			</div>
			<?php
		}
	?>
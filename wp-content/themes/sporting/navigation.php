<?php
	if ( $wp_query->max_num_pages > 1 ) {
		
		 if ( !smt_getOption( 'layout', 'dpagination' ) ) {
				
		
			$links = paginate_links( array(
				'prev_text'          => smt_translate( 'prevpage' ),
				'next_text'          => smt_translate( 'nextpage' ),
				'before_page_number' => '<li>',
				'after_page_number' => '</li>',
			)  );
		
			?>
		
			<nav class="pagination">
				<ul class="page-numbers">
				<?php print_r( $links ); ?>
				</ul>
			</nav>
		
			<?php
			
		 } else {
			 
			 $currentpage=max( 1, get_query_var('paged') );
			 if ( $wp_query->max_num_pages > $currentpage ) {
				 
				 ?>
				<nav class="pagination dynamic <?php echo ( smt_getOption( 'layout', 'loadonscroll' ) )?'loadonscroll':''?>">
					<a class="nextpage" data-pagenum="<?php echo ( $currentpage + 1 ) ?>" href="<?php echo get_pagenum_link( $currentpage + 1 ) ?>"><?php echo smt_translate( 'nextpage' ); ?></a>
				</nav>
				 <?php
				 
			 }
		 }
	}
?>
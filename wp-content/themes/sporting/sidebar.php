<?php

	global $woocommerce; 
	function is_blog () {
		global $post;
		$posttype = get_post_type($post );
		return ( ((is_archive()) || (is_author()) || (is_category()) || (is_home()) || (is_single()) || (is_tag())) && ( $posttype == 'post') ) ? true : false ;
	} 
	
	
	if ( is_blog() ) {
		
		if ( get_option('page_for_posts') ) {
			$thisPage=get_page( get_option('page_for_posts')  );
			$page_meta = get_post_meta( $thisPage->ID, '_smt_pagemeta_value_key', true );
		}
	} elseif ( is_page() ) {
		
		$page_meta = get_post_meta( $post->ID, '_smt_pagemeta_value_key', true );
	} elseif ( isset( $woocommerce ) && is_woocommerce() ) {
		$thisPage = get_post( woocommerce_get_page_id( 'shop' ) );
		$page_meta = get_post_meta( $thisPage->ID, '_smt_pagemeta_value_key', true );
	} 
	
	if ( !isset( $page_meta[ 'sidebar' ] ) && smt_getOption( 'layout', 'pagelayout' ) != 'no' ){
		$page_meta[ 'sidebar' ] = 'smt_default_sidebar';
		$page_meta[ 'sidebar_position' ] = smt_getOption( 'layout', 'pagelayout' );
	}
	if ( !isset( $page_meta[ 'sidebar' ] ) ||  !is_active_sidebar( $page_meta[ 'sidebar' ] ) ) return;
	
?>
<script>
	jQuery( '.site-content' ).addClass( 'sidebar-<?php echo $page_meta[ 'sidebar_position' ]; ?>' );
</script>
<div class="sidebar clearfix<?php if ( smt_getOption( 'layout', 'floatingsidebars' ) ) echo ' floating'; ?>">
	<?php dynamic_sidebar( $page_meta[ 'sidebar' ] ); ?>
</div>

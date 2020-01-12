<?php
	/*Template Name: Contact form*/
?>
<?php
	if ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'send' ) {
		
		$msg = '';
		
		foreach( smt_getOption( 'contactform', 'contactform' ) as $key => $field ) {
			
			if ( $field[ 'required' ] && ( !isset( $_POST[ 'field_'.$key ] ) || $_POST[ 'field_'.$key ] == '' ) ) {
				$errors[]='Field ' . $field[ 'title' ] . ' is required';
			} elseif ( isset( $field[ 'regex' ] ) && $field[ 'regex' ] != '' && !preg_match( '/'.$field[ 'regex' ].'/', $_POST[ 'field_'.$key ] ) ) {
				$errors[]=$_POST[ 'field_'.$key ] . ' is not correct value for ' . $field[ 'title' ];
			}
			
			$msg .= $field[ 'title' ] . ': ' . $_POST[ 'field_'.$key ]."\r\n";
			
		}
		
		if ( ! isset( $errors ) ) {
			
			$from = ( smt_getOption( 'general', 'sitename' ) ) ? smt_getOption( 'general', 'sitename' ) : get_bloginfo( 'name' );
			
			if ( wp_mail( smt_getOption( 'contactform', 'email' ), 'Message from '.$from, $msg ) ) {
				$messageSent = true;
			} else {
				$errors[] = smt_translate( 'emailfail' );
			}
			
		}
		
	}
?>
<?php get_header(); ?>

<div id='container'>
	<?php if ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( ); ?>>
	
		<!-- ========== Post Title ========== -->
		<h1 class='entry-title'><?php the_title(); ?></h1>
		
		
		<!-- ========== Post Featured Image ========== -->
		<?php if ( has_post_thumbnail() ) { // if there is featured image for this post you may wrapper for it ?>
			
			<?php the_post_thumbnail(
					'post-thumbnail',
					array( 'class' => 'featured_image', 'style' => smt_thumbnail_style() )
				); ?>
				
		<?php } ?>
		
		
		
		<!-- ========== Post content in single post page ========== -->
		<div class="entry-content">
		
			<div class="content-block">
				<?php the_content( smt_translate( 'readmore' ) ); ?>
			</div>
			<!-- Contacts Map -->
			<?php if ( smt_getOption( 'contactform', 'address' ) != '' ) { ?>
			<div class="content-block">
				<div class="smt_googlemap" data-address="<?php echo smt_getOption( 'contactform', 'address' ); ?>"></div>
			</div>
			<?php } ?>
			
		
			<?php 
				$contacts = smt_getOption( 'contactform', 'details' );
				if ( is_array( $contacts ) && count( $contacts ) > 0 ) { 
					$layout = smt_getOption( 'contactform', 'layout' ) == 'horizontal' ? 'horizontal' : 'vertical'; ?>
					<div id="contact-details" class="content-block <?php echo $layout; ?>">
						<?php foreach( $contacts as $detail ) { ?>
							<div class="contact-detail">
								<div class="icon">
									<?php echo $detail[ 'icon' ]; ?>
								</div>
								<div class="content">
									<?php echo $detail[ 'content' ]; ?>
								</div>
							</div>
						<?php } ?>
					</div>
					<?php if ( smt_getOption( 'contactform', 'layout' ) == 'horizontal' ) { 
						echo '<style>
							.contact-detail { width: '.(102-2*count( $contacts ))/count( $contacts ).'%; }
						</style>';
					} ?>
				<?php } 
			?>
		
		
			<?php 
				$fields = smt_getOption( 'contactform', 'contactform' );
				if ( is_array( $fields ) && count( $fields ) > 0 ) { ?>
					<h3 class="accent"><?php echo smt_translate( 'feedbackttl' ); ?></h3>
					<?php if( isset( $errors ) ) { ?>
						<ul class="smt_errors">
						<?php foreach( $errors as $error ) { ?>
							<li><?php echo $error; ?></li>
						<?php } ?>
						</ul>
					<?php } ?>
					<?php if ( isset( $messageSent ) ) { ?>
						<p><?php echo smt_translate( 'emailok' ); ?></p>
					<?php } ?>
					<div id="contact-form" class="content-block">
						<form action="" method="POST">
							<input type="hidden" name="action" value="send" />
						<?php foreach( $fields as $key => $field ) { ?>
							
							<div class="smt-field">
								<span class="descent">
									<?php echo $field[ 'title' ]; ?>
									<?php if ( $field[ 'required' ] ) { ?>
										<abbr class="required" title="required">*</abbr>
									<?php } ?>
								</span>
								<?php switch( $field[ 'type' ] ) {
										case 'text': ?>
										<input type="text" class="input-text" name="<?php echo 'field_'.$key; ?>" id="author" value="<?php if ( isset( $_POST[ 'field_'.$key ] ) ) echo $_POST[ 'field_'.$key ]; ?>" />
										<?php break; 
										case 'textarea': ?>
										<textarea name="<?php echo 'field_'.$key; ?>" cols="45" rows="8" aria-required="true" placeholder=""><?php if ( isset( $_POST[ 'field_'.$key ] ) ) echo $_POST[ 'field_'.$key ]; ?></textarea>
										<?php break; 
								} ?>
								
							</div>
						<?php } ?>
						<input type='submit' class='readmore' value='<?php echo smt_translate( 'send' );?>' />
						</form>
					</div>
				<?php } 
			?>
			
			
		</div><!-- .entry-content -->
		
		
		<div class="clear"></div>
		
	</article>
	<?php 
		else :
			// If no content, include the "No posts found" template.
			get_template_part( 'content', 'none' );
		endif; 
	?>
</div>

<?php get_sidebar(); ?>
	
<?php get_footer(); ?>
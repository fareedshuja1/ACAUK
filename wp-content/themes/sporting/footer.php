		</div> <!-- / boxed container -->
	</div> <!-- / wrapper -->
	<footer>
		<div class="boxed-container">
				
				
			
			<div class="footer-columns">
				<?php 
					dynamic_sidebar( 'smt_footer_sidebar' ); 
					$total_widgets = wp_get_sidebars_widgets();
					$cnt = count( $total_widgets['smt_footer_sidebar'] );
				?>
				<div class="clear"></div>
			</div>
			<div class="footer-menu">
				<?php wp_nav_menu(array( 
							'depth'=>0,
							'theme_location' => smt_getOption( 'menu', 'mobile' ),
							'menu_class'    => 'nav-menu'
				));	?>
			</div>
			<style>
				@media only screen and (min-width:801px) {
					footer .widget { width: <?php echo (102-2*$cnt)/$cnt; ?>%; }
					#<?php echo $total_widgets['smt_footer_sidebar'][$cnt-1]; ?> { margin-right:0; }
				}
			</style>
			
			<div class="footer_txt">
				<div><?php echo smt_getOption( "layout","footertext" ); ?></div>
				<div class='smthemes'>Designed by <a href='http://www.clashroyalegemme.com' target='_blank'>Clash Royale Astuce Gemmes</a>, thanks to: <a href='http://snapscore.playgamy.com' target='_blank'>snapchat hack</a>, <a href='http://www.simsfreeplayastuce.fr' target='_blank'>sims freeplay astuce</a> and <a href='http://www.havrix.ru' target='_blank'>Havrix</a></div>
			</div>
			
		</div>
		
	</footer>
	
	<?php wp_footer(); ?>
	
	<?php get_template_part( 'extras/social' ); ?>
	
	<script type="text/javascript"><!--//--><![CDATA[//><!--
		<?php
			$superfish = array();
			switch( smt_getOption( 'menu','effect' ) ) {
				case 'standart':
					$superfish[ 'animation' ] = 'animation: {width:"show"}';
					break;
				case 'slide':
					$superfish[ 'animation' ] = 'animation: {height:"show"}';
					break;
				case 'fade':
					$superfish[ 'animation' ] = 'animation: {opacity:"show"}';
					break;
				case 'fade_slide_right':
					$superfish[ 'onBeforeShow' ] = 'onBeforeShow: function(){ this.css("marginLeft","20px"); }';
					$superfish[ 'animation' ] = 'animation: {"marginLeft":"0",opacity:"show"}';
					break;
				case 'fade_slide_left':
					$superfish[ 'onBeforeShow' ] ='onBeforeShow: function(){ this.css("marginLeft","-20px"); }';
					$superfish[ 'animation' ] = 'animation: {"marginLeft":"0",opacity:"show"}';
					break;
			}
			$superfish[ 'autoArrows' ] = 'autoArrows:  ' . ( ( smt_getOption( 'menu','arrows' ) ) ? 'true' : 'false' );
			$superfish[ 'dropShadows' ] = 'dropShadows: false';
			$superfish[ 'speed' ] = 'speed: ' . smt_getOption( 'menu', 'speed' );
			$superfish[ 'delay' ] = 'delay: ' . smt_getOption( 'menu', 'delay' );
		?>
		jQuery(function(){ 
			jQuery( 'ul.nav-menu' ).superfish( {
				<?php echo implode( ', ', $superfish ); ?>
			});
		});
	//--><!]]></script>
</body>
</html>
<?php global $SMTheme;?>
  
    </div>
    </div>
</div>
<?php	
if ($SMTheme->get( 'social', 'showsocial')) {
	$SMTheme->block_social();
}
?>
<div id='content-bottom'></div>
<div id='footer'>
	
		<div class='container clearfix'>
		<?php if ($SMTheme->get("layout","footerwidgets")) { ?>
		<div class='footer-widgets-container'><div class='footer-widgets'>
			<div class='widgetf'>
				<?php if ( !function_exists("dynamic_sidebar") || !dynamic_sidebar("footer_1") ) : ?>
				<?php
					$SMTheme->go_func("footer_1");
				?>
				<?php endif; ?>
			</div>
			
			<div class='widgetf'>
				<?php if ( !function_exists("dynamic_sidebar") || !dynamic_sidebar("footer_2") ) : ?>
				<?php
					$SMTheme->go_func("footer_2");
				?>
				<?php endif; ?>
			</div>
			
			<div class='widgetf widgetf_last'>
				<?php if ( !function_exists("dynamic_sidebar") || !dynamic_sidebar("footer_3") ) : ?>
				<?php
					$SMTheme->go_func("footer_3");
				?>
				<?php endif; ?>
			</div>
		</div></div>
		<?php } ?>
		
</div>
<div class='footer_txt'><div class='container clearfix'>
			<?php
                    if ($SMTheme->get( "layout","footertext" )) {
                        echo $SMTheme->get( "layout","footertext" );
                    } else { 
                        ?>Copyright &copy; <?php echo date("Y"); ?>  <a href="<?php echo home_url(); ?>"><?php bloginfo("name"); ?></a><?php
						echo (get_bloginfo('description'))?' - '.get_bloginfo('description'):'';
                    }
                ?> 
				<?php /* 
					All links in the tag <div class='smthemes'> are attribution of the theme developers and should remain intact. 
					It's protected by Creative Commons License (http://creativecommons.org/licenses/by/3.0/).
					Warning! Your site will not be able to work if these links are edited or deleted.
					You can buy this theme without footer links online at http://smthemes.com/buy/sport/
				*/ ?>
				<div class='smthemes'>Designed by <a href='http://couldihavemadeit.com/' target='_blank'>hcg injections</a>, thanks to: <a href='http://votreblogue.com/' target='_blank'>vodafone signal booster</a>, <a href='http://ostaauggboots.com/' target='_blank'>hcg injections</a> and <a href='http://bluemangocg.com/' target='_blank'>r4 3ds</a></div>
		</div></div>
		
<?php wp_footer(); ?>

</div> <?php //footer ?>
</div> <?php //all ?>
<?php
	echo $SMTheme->get( "integration","footercode" );
?>
</body>
</html>
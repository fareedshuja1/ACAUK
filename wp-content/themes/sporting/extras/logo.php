<?php if ( smt_getOption( 'general', 'logosource' ) == 'logoimage' && smt_getOption( 'general', 'logoimage' ) != '' ) { ?>
	<a id='logo' href='<?php echo home_url(); ?>/'><img src='<?php echo smt_getOption( 'general', 'logoimage' )?>' alt='<?php echo bloginfo( 'name' ); ?>' title="<?php echo bloginfo( 'name' ); ?>" /></a>
<?php } ?>

<?php if (smt_getOption( 'general', 'logosource' )=='customtext'&&smt_getOption( 'general', 'customtext' )!='') { ?>
	<h1 class='site_ttl'><?php echo smt_getOption( 'general', 'customtext' )?></h1>
<?php } 
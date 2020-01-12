<?php $search_text = empty($_GET['s']) ?  '': get_search_query(); ?> 
<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
	<div class="search-box">
		<input type="search" class="search-field" placeholder="<?php echo smt_translate('search'); ?>" value="<?php echo $search_text; ?>" name="s" title="<?php echo $search_text; ?>" />
		<input type="submit" class="search-submit icon" value="&#xf002;" />
	</div>
</form>
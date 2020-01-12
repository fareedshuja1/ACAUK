<?php
	
	if ( !smt_getOption( 'social', 'showsocial' ) ) return;
	

	$socials = array(
						'facebooklike' => '<iframe src="//www.facebook.com/plugins/like.php?href=smt_social_url&amp;send=false&amp;layout=box_count&amp;width=51&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=65&amp;locale=en_US" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:52px; height:65px;" allowTransparency="true"></iframe>',
						
						'twitter'=>'<a href="https://twitter.com/share" class="twitter-share-button" data-count="vertical" data-lang="en">Tweet</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>',
						
						'googleplus'=>'<g:plusone size="tall"></g:plusone><script type="text/javascript">(function() { var po = document.createElement("script"); po.type = "text/javascript"; po.async = true; po.src = "https://apis.google.com/js/plusone.js"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s); })();</script>',
			
						'linkedin'=>'<script src="//platform.linkedin.com/in.js" type="text/javascript"></script><script type="IN/Share" data-counter="top"></script>',
						
						'facebookshare'=>'<a name="fb_share" type="box_count"></a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>',
						
						'reddit'=>'<script type="text/javascript" src="http://www.reddit.com/static/button/button2.js"></script>',
						
						'pinterest'=>'<a href="http://pinterest.com/pin/create/button/?url=smt_social_url&media=smt_social_img_url&description=smt_social_title" class="pin-it-button" count-layout="vertical"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a><script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>',
						
						'buffer'=>'<a href="http://bufferapp.com/add" class="buffer-add-button" data-count="vertical">Buffer</a><script type="text/javascript" src="http://static.bufferapp.com/js/button.js"></script>',
						
						'stumbleupon'=>'<su:badge layout="5"></su:badge><script type="text/javascript"> (function() { var li = document.createElement("script"); li.type = "text/javascript"; li.async = true; li.src = window.location.protocol + "//platform.stumbleupon.com/1/widgets.js"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(li, s); })(); </script>',
							
						'dzone'=>'<script type="text/javascript">var dzone_url = "[smt_social_url]";</script><script type="text/javascript">var dzone_title = "[smt_social_title]";</script><script type="text/javascript">var dzone_blurb = "[smt_social_title]";</script><script type="text/javascript">var dzone_style = "1";</script><script language="javascript" src="http://widgets.dzone.com/links/widgets/zoneit.js"></script>',
			
						'topsy'=>'<script type="text/javascript" src="http://button.topsy.com/widget/retweet-big?nick=[twitter_name]&url=[smt_social_url]"></script>',
						
						'delicious'=>'<img src="http://www.delicious.com/static/img/delicious.small.gif" height="10" width="10" alt="Delicious" /><a href="http://www.delicious.com/save" onclick="window.open("http://www.delicious.com/save?v=5&noui&jump=close&url="+encodeURIComponent("smt_social_url")+"&title="+encodeURIComponent("smt_social_title"),"delicious", "toolbar=no,width=550,height=550"); return false;"> Save </a><span id="DD_DELICIOUS_AJAX_POST_ID"><div style="padding-top:3px">0</div></span>',
						
						'flattr'=>'<script type="text/javascript">(function() { var s = document.createElement("script"); var t = document.getElementsByTagName("script")[0]; s.type = "text/javascript"; s.async = true; s.src = "http://api.flattr.com/js/0.6/load.js?mode=auto"; t.parentNode.insertBefore(s, t); })(); window.onload = function() { FlattrLoader.render({ "uid": "flattr", "url": "smt_social_url","title": "smt_social_title", "description": "smt_social_desc" }, "flattrBtn", "replace"); } </script><div id="flattrBtn"></div>',
			
						'tumblr'=>'<a href="http://www.tumblr.com/share" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:61px; height:20px; background:url(http://platform.tumblr.com/v1/share_2.png) top left no-repeat transparent;">Share on Tumblr</a>',
						
						'googleshare'=>'<div class="g-plus" data-action="share" data-annotation="vertical-bubble" data-height="60"></div><script type="text/javascript">window.___gcfg = {lang: "en-GB", parsetags: "onload"}; (function() { var po = document.createElement("script"); po.type = "text/javascript"; po.async = true; po.src = "https://apis.google.com/js/plusone.js"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);})();</script>'
				);
?>
<div id='smthemes_share' top="100" bottom="283">
	<ul class='inner'>
		<?php
			$href = get_bloginfo( 'url' ) . $_SERVER['REQUEST_URI'];
			$buttons = smt_getOption( 'social', 'socials' );
			if ( is_single() ) {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
				$image = $image[0];
			} else {
				$image = '';
			}
			if ( $image == '' ) {
				$image = smt_getOption( 'general', 'logoimage' );
			}
			foreach ( $buttons as $button ) { 
				if ( !isset( $socials[ $button ] ) ) continue;
				
				
				$code = str_replace( 'smt_social_url', $href, $socials[ $button ] );
				$code = str_replace( 'smt_social_title', get_the_title(), $code );
				$code = str_replace( 'smt_social_desc', get_the_title(), $code );
				$code = str_replace( 'smt_social_img_url', $image, $code );
				echo "<li>".$code."</li>";
				
			}
		?>
		<li class="close"><span>Hide</span></li>
	</ul>
</div>
<style>
	@media only screen and (max-width:1023px) {
		#smthemes_share li { width: <?php echo 100/count( $buttons ); ?>%; }
	}
</style>
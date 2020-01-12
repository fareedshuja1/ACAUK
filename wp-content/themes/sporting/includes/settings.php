<?php

    $info=wp_get_theme();
	$themename=strtolower($info['Name']);
    // Theme Settings
	global $smt_default_options, $_wp_additional_image_sizes;
	
	$qcategories = get_categories();
	$categories[ 0 ] = 'All Categories';
	foreach( $qcategories as $category ) {
		$categories[ $category->cat_ID ] = $category->name . ' (' . $category->count . ')';
	}
	$args=array(
		'order_by' => 'title',
		'numberposts' => -1,
		'meta_key' => '_thumbnail_id'
	);
	
	$posts = array();
	$qposts = get_posts( $args );
	foreach( $qposts as $post ) {
		$posts[ $post->ID ] = $post->post_title;
	}
	
	$pages = array();
	$qpages = get_pages( $args );
	foreach( $qpages as $page ) {
		$pages[ $page->ID ] = $page->post_title;
	}

    $smt_default_options = array(
        'general'=>array(
			'name'=>'General',
			'icon' => '&#xf085;',
			'editable' => true,
			'content'=>array(
				'logosource'=>array(
					'type'=>'select', 'name'=>'logosource', 'value'=>'logoimage', 'title'=>'Logo Source', 
					'params'=>array(
						'logoimage'=>'Logo Image',
						'customtext'=>'Custom Text'
					),'hint'=>'Select a logo source'
				),
				'logoimage'=>array(
					'type'=>'image','depend'=>'logosource','case'=>'logoimage', 'name'=>'logoimage','value'=>get_template_directory_uri().'/images/logo.png','title'=>'Logo image', 'hint'=>'Click the "Set/Change Image" button to choose a logo image'
				),
				'customtext'=>array(
					'type'=>'text','depend'=>'logosource','case'=>'customtext', 'name'=>'customtext','value'=>get_bloginfo( 'name' ),'title'=>'Custom Text', 'hint'=>'Enter text for your logo'
				),
				'sitename'=>array(
					'type'=>'text','name'=>'sitename','value'=>get_bloginfo( 'name' ),'title'=>'Front Page Title', 'hint'=>'Enter front page title'
				),
				'favicon'=>array(
					'type'=>'image','name'=>'favicon','value'=>get_template_directory_uri().'/images/favicon.png','title'=>'Favicon', 'hint'=>'Click the "Set/Change Image" button to choose an appropriate favicon for your website'
				)
			)
		),
		'slider'=>array(
			'name'=>'Slider',
			'icon' => '&#xf008;',
			'editable' => true,
			'content'=>array(
				'txt'=>array(
					'type'=>'paragraph','name'=>'','value'=>'Recommended thumbnail size is '.$_wp_additional_image_sizes[ 'smt_slide' ][ 'width' ].'x'.$_wp_additional_image_sizes[ 'smt_slide' ][ 'height' ]
				),
				'source'=>array(
					'type'=>'select', 'name'=>'source', 'value'=>'custom', 'title'=>'Slider Source', 
					'params'=>array(
						'custom'=>'Custom slides',
						'category'=>'Category',
						'posts'=>'Posts',
						'pages'=>'Pages'
					),'hint'=>'Select where the images for your slider will be taken from'
				),
				
				'custom_slides'=>array(
					'type'=>'custom_slides', 'name'=>'custom_slides', 'depend' => 'source', 'case'=>'custom', 'value'=>array (
						'1' => Array (
								'thumbnail' => get_template_directory_uri().'/images/slides/1.jpg',
								'link' => home_url(),
								'title' => 'Far far away',
								'content' => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts'
							),
						'2' => Array (
								'thumbnail' => get_template_directory_uri().'/images/slides/2.jpg',
								'link' => home_url(),
								'title' => 'Behind the word mountains',
								'content' => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts'
							),
						'3' => Array (
								'thumbnail' => get_template_directory_uri().'/images/slides/3.jpg',
								'link' => home_url(),
								'title' => 'Far from the countries Vokalia',
								'content' => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts'
							),
						'4' => Array (
								'thumbnail' => get_template_directory_uri().'/images/slides/4.jpg',
								'link' => home_url(),
								'title' => 'There live the blind texts',
								'content' => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts'
							)
					), 'datascheme' => array(
						
							'thumbnail'=>array(
								'type'=>'image', 'name'=>'thumbnail', 'value'=>get_template_directory_uri().'/images/slides/4.jpg', 'title'=>'Slide Thumbnail'
							),
							'link'=>array(
								'type'=>'text','name'=>'link','value'=>home_url(),'title'=>'Link'
							),
							'title'=>array(
								'type'=>'text','name'=>'title','value'=>'There live the blind texts','title'=>'Title'
							),
							'content'=>array(
								'type'=>'textarea','name'=>'content','value'=>'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts','title'=>'Silde Content'
							)
							
					)
				),
				
				'numberposts'=>array(
					'type'=>'text','name'=>'numberposts', 'value'=>'5', 'title'=>'Number of slides', 'hint'=>'Choose the number of slides to display', 'depend' => 'source', 'case'=>'category',
				),
				'category'=>array(
					'type'=>'select', 'name'=>'category', 'value'=>'1', 'title'=>'Category', 'depend' => 'source', 'case'=>'category',
					'params'=>$categories,
					'hint'=>'Select a particular category from which the featured images of posts will be displayed as slides'
				),
				
				'posts'=>array(
					'type'=>'multiselect', 'name'=>'posts', 'value'=>array(  ), 'title'=>'Posts', 'depend' => 'source', 'case'=>'posts',
					'params'=>$posts,
					'hint'=>'Select posts for slider'
				),
				
				'pages'=>array(
					'type'=>'multiselect', 'name'=>'pages', 'value'=>array(  ), 'title'=>'Pages', 'depend' => 'source', 'case'=>'pages',
					'params'=>$pages,
					'hint'=>'Select pages for slider'
				),
				
				'showthumbnail'=>array(
					'type'=>'check','name'=>'showthumbnail','value'=>'1','title'=>'Show slide thumbnail'
				),
				'showtext'=>array(
					'type'=>'check','name'=>'showtext','value'=>'1','title'=>'Show text'
				),
				'showttl'=>array(
					'type'=>'check','name'=>'showttl','value'=>'1','title'=>'Show slide title'
				),
				'showhrefs'=>array(
					'type'=>'check','name'=>'showhrefs','value'=>'1','title'=>'Show links'
				),
				'effect'=>array(
					'type'=>'select', 'name'=>'effect', 'value'=>'fade', 'title'=>'Slider effect', 
					'params'=>array(
					    'blindX'=>'Blind X',
						'blindY'=>'Blind Y',
						'blindZ'=>'Blind Z',
						'cover'=>'Cover',
						'curtainX'=>'Curtain X',
						'curtainY'=>'Curtain Y',
						'fade'=>'Fade',
						'fadeZoom'=>'Fade zoom',
						'growX'=>'Grow X',
						'growY'=>'Grow Y',
						'scrollUp'=>'Scroll up',
						'scrollDown'=>'Scroll down',
						'scrollLeft'=>'Scroll left',
						'scrollRight'=>'Scroll right',
						'scrollHorz'=>'Scroll Horizontal',
						'scrollVert'=>'Scroll Vertical',
						'shuffle'=>'Shuffle',
						'slideX'=>'Slide X',
						'slideY'=>'Slide Y',
						'toss'=>'Toss',
						'turnUp'=>'Turn up',
						'turnDown'=>'Turn down',
						'turnLeft'=>'Turn left',
						'turnRight'=>'Turn right',
						'uncover'=>'Uncover',
						'wipe'=>'Wipe',
						'zoom'=>'Zoom'
					),'hint'=>'Choose an effect for slides transition'
				),
				'speed'=>array(
					'type'=>'text','name'=>'speed','value'=>'1000','title'=>'Speed (milliseconds)', 'hint'=>'Enter speed value for slider scroll'
				),
				'timeout'=>array(
					'type'=>'text','name'=>'timeout','value'=>'3000','title'=>'Timeout (milliseconds)','hint'=>'Enter timeout value for slider scroll'
				),
				'pause'=>array(
					'type'=>'text','name'=>'pause','value'=>'1000','title'=>'Pause (milliseconds)','hint'=>'Enter length of the pause for slider scroll'
				),
				'homepage'=>array(
					'type'=>'check','name'=>'homepage','value'=>'1','title'=>'Show slider on homepage'
				),
				'innerpage'=>array(
					'type'=>'check','name'=>'innerpage','value'=>'1','title'=>'Show slider on inner pages'
				)
			)
		),
		'showroom'=>array(
			'name'=>'Showroom',
			'icon' => '&#xf141;',
			'editable' => true,
			'content'=>array(
				'txt'=>array(
					'type'=>'paragraph','name'=>'','value'=>'Recommended thumbnail size is '.$_wp_additional_image_sizes[ 'smt_showroom' ][ 'width' ].'x'.$_wp_additional_image_sizes[ 'smt_showroom' ][ 'height' ]
				),
				'source'=>array(
					'type'=>'select', 'name'=>'source', 'value'=>'custom', 'title'=>'Showroom source Source', 
					'params'=>array(
						'custom'=>'Custom items',
						'category'=>'Category',
						'posts'=>'Posts',
						'pages'=>'Pages'
					),'hint'=>'Select where the images for showroom elements will be taken from'
				),
				'custom_items'=>array(
					'type'=>'custom_items', 'name'=>'custom_items', 'depend' => 'source', 'case'=>'custom', 'value'=>array (
						'1' => Array (
								'thumbnail' => get_template_directory_uri().'/images/showroom/1.png',
								'link' => home_url(),
								'title' => 'Duis sed odio sit amet nibh',
								'content' => 'Odio tincidunt auctor a ornare odio, Sed non mauris vitae erat consequat auctor eu in elit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.'
							),
						'2' => Array (
								'thumbnail' => get_template_directory_uri().'/images/showroom/2.png',
								'link' => home_url(),
								'title' => 'Aptent himenaeos conubia',
								'content' => 'Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non  mauris vitae erat consequat auctor eu in elit.'
							),
						'3' => Array (
								'thumbnail' => get_template_directory_uri().'/images/showroom/3.png',
								'link' => home_url(),
								'title' => 'Class aptent mauris vitae',
								'content' => 'Sed ut imperdiet nisi. Proin condimentum fermentum nunc. Etiam pharetra, erat sed fermentum feugiat, velit mauris egestas quam, ut aliquam massa nisl quis neque. Suspendisse in orci '
							)
					), 'datascheme' => array(
						
							'thumbnail'=>array(
								'type'=>'image', 'name'=>'thumbnail', 'value'=>get_template_directory_uri().'/images/showroom/3.png', 'title'=>'Showroom Thumbnail'
							),
							'link'=>array(
								'type'=>'text','name'=>'link','value'=>home_url(),'title'=>'Link'
							),
							'title'=>array(
								'type'=>'text','name'=>'title','value'=>'There live the blind texts','title'=>'Title'
							),
							'content'=>array(
								'type'=>'textarea','name'=>'content','value'=>'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts','title'=>'Content for this item'
							)
							
					)
				),
				'numberposts'=>array(
					'type'=>'text','name'=>'numberposts', 'value'=>'3', 'title'=>'Number of posts', 'hint'=>'Choose the number of showroom items to display', 'depend' => 'source', 'case'=>'category',
				),
				'category'=>array(
					'type'=>'select', 'name'=>'category', 'value'=>'1', 'title'=>'Category', 'depend' => 'source', 'case'=>'category',
					'params'=>$categories,
					'hint'=>'Select a particular category from which the featured images of posts will be displayed in showroom section'
				),
				'posts'=>array(
					'type'=>'multiselect', 'name'=>'posts', 'value'=>array(  ), 'title'=>'Posts', 'depend' => 'source', 'case'=>'posts',
					'params'=>$posts,
					'hint'=>'Select posts for showroom'
				),
				
				'pages'=>array(
					'type'=>'multiselect', 'name'=>'pages', 'value'=>array(  ), 'title'=>'Pages', 'depend' => 'source', 'case'=>'pages',
					'params'=>$pages,
					'hint'=>'Select pages for showroom'
				),
				'showthumbnail'=>array(
					'type'=>'check','name'=>'showthumbnail','value'=>'1','title'=>'Show thumbnails in showroom'
				),
				'showtext'=>array(
					'type'=>'check','name'=>'showtext','value'=>'1','title'=>'Show text'
				),
				'showttl'=>array(
					'type'=>'check','name'=>'showttl','value'=>'1','title'=>'Show titles'
				),
				'homepage'=>array(
					'type'=>'check','name'=>'homepage','value'=>'1','title'=>'Show on homepage'
				),
				'innerpage'=>array(
					'type'=>'check','name'=>'innerpage','value'=>'1','title'=>'Show on inner pages'
				)
			)
		),
		'layout'=>array(
			'name'=>'Layout',
			'icon' => '&#xf0f6;',
			'editable' => true,
			'content'=>array(
				'pagelayout'=>array(
					'type'=>'select', 'name'=>'pagelayout', 'value'=>'right', 'title'=>'Content Layout', 
					'params'=>array(
						'no'=>'No Sidebars',
						'right'=>'Right Sidebar',
						'left'=>'Left Sidebar'
					), 'hint'=>'Select a default content layout for all pages and posts on your website. You may set content layout for a particular page/post when editing it.'
				),
				'floatingsidebars'=>array(
					'type'=>'check','name'=>'floatingsidebars','value'=>'1','title'=>'Floating Sidebars', 'hint'=>'Make your sidebars permanently visible when scrolling up and down.'
				),
				'dpagination'=>array(
					'type'=>'check','name'=>'dpagination','value'=>'1','title'=>'Load posts dynamically', 'hint'=>'Turn on to use a dynamic posts loader. This option allows to load next pages without page reloading. It is used instead of the standard numeric pagination.'
				),
				'loadonscroll'=>array(
					'type'=>'check','depend'=>'dpagination','case'=>'1', 'name'=>'loadonscroll','value'=>'1','title'=>'Load Posts on Scroll', 'hint'=>'Turn on to load next pages when user scrolls down the page'
				),
				'related'=>array(
					'type'=>'check','name'=>'related','value'=>'1','title'=>'Show related posts?', 'hint'=>'Turn on to show the related posts at the bottom of a single post page.'
				),
				'relatedcnt'=>array(
					'type'=>'text','name'=>'relatedcnt','value'=>'4','title'=>'Related posts count'
				),
				'cuttxton'=>array(
					'type'=>'check','name'=>'cuttxton','value'=>'1','title'=>'Cut content on the Front/Category page'
				),
				'cuttxt'=>array(
					'type'=>'text','name'=>'cuttxt','value'=>'800','title'=>'The default excerpt length', 'hint'=>'Set the desired character count for posts content on the Front/Category page.'
				),
				'footertext'=>array(
					'type'=>'textarea','name'=>'footertext','value'=>'Copyright &copy; ' . date( 'Y' ) . '  <a href="'.home_url().'">' . get_bloginfo( 'name' ) . '</a>','title'=>'Custom Footer Text'
				),
			)
		),
		'social'=>array(
			'name'=>'Social Buttons',
			'icon' => '&#xf1e0;',
			'editable' => true,
			'content'=>array(
			
				'showsocial'=>array(
					'type'=>'check','name'=>'showsocial','value'=>'1','title'=>'Show social box', 'hint'=>'Turn on to show social share box'
				),
				'socials' => array(
					'type'=>'multiselect', 'name'=>'socials', 'value'=>array( 'facebooklike', 'twitter', 'googleplus' ), 'title'=>'Social services', 
					'params'=>array(
						'facebooklike'=>'Facebook Like',
						'twitter'=>'Twitter',
						'googleplus'=>'Google +',
						'linkedin'=>'Linked In',
						'facebookshare'=>'Facebook share',
						'reddit'=>'Reddit',
						'pinterest'=>'Pinterest',
						'buffer'=>'Buffer',
						'stumbleupon'=>'Stumbleupon',
						'dzone'=>'DZone',
						'topsy'=>'Topsy',
						'delicious'=>'Delicious',
						'flattr'=>'Flattr',
						'tumblr'=>'Tumblr',
						'googleshare'=>'Google Share',
					), 'hint'=>'Select social services which you want to display in the social share box.'
				),
				
			)
		),
		'seo'=>array(
			'name'=>'SEO',
			'icon' => '&#xf002;',
			'editable' => true,
			'content'=>array(
				'smt_seo'=>array(
					'type'=>'check', 'name'=>'smt_seo','value'=>'0','title'=>'Built-in SEO. Disable this feature if you are using a third-party plugin'
				),
				'description'=>array(
					'type'=>'text','name'=>'description','value'=>'','title'=>'Site description'				
				),
				'keywords'=>array(
					'type'=>'text','name'=>'keywords','value'=>'','title'=>'Site keywords'				
				),
				'noindex' => array(
					'type'=>'multiselect', 'name'=>'noindex', 'value'=>array(  ), 'title'=>'Pages that have to be excluded from index',
					'params'=>array(
						'category'=>'Category archives',
						'tag'=>'Tag archives',
						'author'=>'Author archives',
						'search'=>'Search archives',
						'day'=>'Day archives',
						'month'=>'Month archives',
						'year'=>'Year archives'
					), 'hint'=>'Prevent all robots from indexing specific pages of your site.'
				)
			)
		),
		'menu'=>array(
			'name'=>'Menu',
			'icon' => '&#xf0c9;',
			'editable' => true,
			'content'=>array(
				'effect'=>array(
					'type'=>'select', 'name'=>'effect', 'value'=>'standart', 'title'=>'Effect', 
					'params'=>array(
						'standart'=>'Standart (No Effect)',
						'slide'=>'Slide Down',
						'fade'=>'Fade',
						'fade_slide_right' => 'Fade &amp; Slide from Right',
						'fade_slide_left' => 'Fade &amp; Slide from Left'
					), 'hint'=>'Choose an effect for the submenus'
				),
				'speed'=>array(
					'type'=>'text','name'=>'speed','value'=>'200','title'=>'Speed', 'hint'=>'Enter the speed value for the submenu appearance'
				),
				'delay'=>array(
					'type'=>'text','name'=>'delay','value'=>'800','title'=>'Delay', 'hint'=>'Enter the delay value for the submenu appearance'
				),
				'arrows'=>array(
					'type'=>'check','name'=>'arrows','value'=>'1','title'=>'Show arrows', 'hint'=>'Show arrows for the submenu appearance'
				),
				'mobile' => array(
					'type'=>'select', 'name'=>'mobile', 'value'=>'extra-menu', 'title'=>'Specify how the menus appear in the mobile mode',
					'params'=>array(
						'extra-menu'=>'Main menu in the header and Extra menu in the footer of page',
						'main-menu'=>'Main menu in the footer and Extra menu in the header of page'
					), 'hint'=>'Choose the menus position for the mobile mode'
				)
			)
		),
		'integration'=>array(
			'name'=>'Integration',
			'icon' => '&#xf12e;',
			'editable' => true,
			'content'=>array(
				'gglapikey'=>array(
					'type'=>'text','name'=>'gglapikey','value'=>'','title'=>'Google Maps API key', 'hint'=>'Get an API key for your website on the  https://developers.google.com/maps/documentation/javascript/get-api-key page and enter it into this text field to make google maps work on your website.'
				),
				'rss'=>array(
					'type'=>'text','name'=>'rss','value'=> get_bloginfo( 'rss2_url' ),'title'=>'RSS URL', 'hint'=>'Enter RSS URL.'
				),
				'css'=>array(
					'type'=>'textarea','name'=>'css','value'=>'','title'=>'Custom CSS', 'hint'=>'Enter custom CSS code. Please, don\'t use the \'style\' tag.'
				),
				'headcode'=>array(
					'type'=>'textarea','name'=>'headcode','value'=>'','title'=>'Head Code', 'hint'=>'Enter your custom header code (scripts, links or meta).'
				),
				'footercode'=>array(
					'type'=>'textarea','name'=>'footercode','value'=>'','title'=>'Footer Code', 'hint'=>'Enter your custom html code to insert to the footer of your website.'
				),
				'ecwidcss'=>array(
					'type'=>'check','name'=>'ecwidcss','value'=>'1','title'=>'Theme\'s CSS for Ecwid', 'hint'=>'Use the theme\'s styles for the Ecwid plugin', 'sysdepend'=>'ecwid-shopping-cart'
				)
			)
		),
		'translations'=>array(
			'name'=>'Translations',
			'icon' => '&#xf1ab;',
			'editable' => true,
			'content'=>array(
				'general'=>array(
					'type'=>'group', 'name'=>'general', 'title'=>'General Text',
					'content'=>array(
						'homelink'=>array( 
							'type'=>'text','name'=>'homelink','value'=>'Home','title'=>'Home link text'
						),
						'search'=>array( 
							'type'=>'text','name'=>'search','value'=>'Type & press enter','title'=>'Search text'
						),
						'nothingfound'=>array(
							'type'=>'text','name'=>'nothingfound','value'=>'Nothing found, please search again.','title'=>'Nothing found'
						)
					)
				),
				'custom_template_text'=>array(
					'type'=>'group', 'name'=>'sitemap', 'title'=>'Custom Template Text',
					'content'=>array(
						'readmore'=>array(
							'type'=>'text','name'=>'readmore','value'=>'Read More','title'=>'Read more text'
						),
						'searchresults'=>array(
							'type'=>'text','name'=>'searchresults','value'=>'Search results for \'%s\'','title'=>'Text of a search result'
						),
						'before-category'=>array(
							'type'=>'text','name'=>'before-category','value'=>'Posted in&nbsp','title'=>'Text before categories'
						),
						'nextpage'=>array(
							'type'=>'text','name'=>'nextpage','value'=>'Next Page','title'=>'Next Page'
						),
						'prevpage'=>array(
							'type'=>'text','name'=>'prevpage','value'=>'Previous Page','title'=>'Previous Page'
						),
						'tags'=>array(
							'type'=>'text','name'=>'tags','value'=>'Tags','title'=>'Tags text'
						),
						'relatedposts'=>array(
							'type'=>'text','name'=>'relatedposts','value'=>'Recommended Posts','title'=>'Related posts text'
						),
						'norelatedposts'=>array(
							'type'=>'text','name'=>'norelatedposts','value'=>'No Related Posts','title'=>'No related posts text'
						),
						'permalink'=>array(
							'type'=>'text','name'=>'permalink','value'=>'Permalink to %1$s','title'=>'Permalink to text'
						),
						'catarchive'=>array(
							'type'=>'text','name'=>'catarchive','value'=>'Category %s','title'=>'Category archive text'
						),
						'authorarchive'=>array(
							'type'=>'text','name'=>'authorarchive','value'=>'Author Archives: %s','title'=>'Author archive text'
						),
						'tagarchive'=>array(
							'type'=>'text','name'=>'tagarchive','value'=>'%s tagged posts','title'=>'Tag archive text'
						),
						'dailyarchives'=>array(
							'type'=>'text','name'=>'dailyarchives','value'=>'Daily Archives %s','title'=>'Daily archives text'
						),
						'monthlyarchives'=>array(
							'type'=>'text','name'=>'monthlyarchives','value'=>'Monthly Archives %s','title'=>'Monthly archives text'
						),
						'yearlyarchives'=>array(
							'type'=>'text','name'=>'yearlyarchives','value'=>'Yearly Archives %s','title'=>'Yearly archives text'
						),
						'blogarchives'=>array(
							'type'=>'text','name'=>'blogarchives','value'=>'Blog Archives','title'=>'Blog archives text'
						),
						'send'=>array(
							'type'=>'text','name'=>'send','value'=>'Send','title'=>'Send button text'
						), 
						'feedbackttl'=>array(
							'type'=>'text','name'=>'feedbackttl','value'=>'Contact form','title'=>'Contact form title'
						),
						'feedbackbefore'=>array(
							'type'=>'text','name'=>'feedbackbefore','value'=>'Inputs marked (*) are required','title'=>'Text before contact form'
						),
						'altposts'=>array(
							'type'=>'text','name'=>'altposts','value'=>'%s post','title'=>'Hint text for posts count in Tag Cloud'
						),
						'altpostss'=>array(
							'type'=>'text','name'=>'altpostss','value'=>'%s posts','title'=>'Hint text for posts count in Tag Cloud (plural)'
						), 
						'altcats'=>array(
							'type'=>'text','name'=>'altcats','value'=>'View all posts filed under %s','title'=>'Hint text for category list'
						)
					)
				),
				'error'=>array(
					'type'=>'group', 'name'=>'error', 'title'=>'Messages',
					'content'=>array(
						'errortext'=>array(
							'type'=>'text','name'=>'errortext','value'=>'Error 404 | Nothing found!','title'=>'404 error text'
						),
						'errorsolution'=>array(
							'type'=>'text','name'=>'errorsolution','value'=>'Sorry, but you are looking for something that is not here.','title'=>'404 error solution text'
						),
						'emailok'=>array(
							'type'=>'text','name'=>'emailok','value'=>'Your message has been successfully sent!','title'=>'Notification of a successful email delivery'
						),
						'emailfail'=>array(
							'type'=>'text','name'=>'emailok','value'=>'Message hasn\'t been sent.','title'=>'Notification of an email delivery failure'
						)
					)
				),
				'comments'=>array(
					'type'=>'group', 'name'=>'comments', 'title'=>'Comments Text',
					'content'=>array(
						'noresponses'=>array(
							'type'=>'text','name'=>'noresponses','value'=>'No comments','title'=>'No responses text'
						),
						'oneresponse'=>array(
							'type'=>'text','name'=>'oneresponse','value'=>'One comment','title'=>'One response text'
						),
						'multiresponse'=>array(
							'type'=>'text','name'=>'multiresponse','value'=>'% comments','title'=>'Multiple responses text'
						),
						'formoneresponse'=>array(
							'type'=>'text','name'=>'formoneresponse','value'=>'One comment to %1$s','title'=>'One response text (comment\'s form)'
						),
						'formmultiresponse'=>array(
							'type'=>'text','name'=>'formmultiresponse','value'=>'%2$s comments to %1$s','title'=>'Multiple responses text (comment\'s form)'
						),
						'comment_notes_before'=>array(
							'type'=>'text','name'=>'comment_notes_before','value'=>'','title'=>'Text before form'
						),
						'comment_notes_after'=>array(
							'type'=>'text','name'=>'comment_notes_after','value'=>'You may use these HTML tags and attributes: %s','title'=>'Text after form'
						),
						'disabledcomments'=>array(
							'type'=>'text','name'=>'disabledcomments','value'=>'Comments are off for this post','title'=>'Disabled comments text'
						),
						'leavereply'=>array(
							'type'=>'text','name'=>'leavereply','value'=>'Leave a reply','title'=>'Leave a reply text'
						),
						'mustbe'=>array(
							'type'=>'text','name'=>'mustbe','value'=>'You must be','title'=>'\'You must be\' text'
						),
						'loggedin'=>array(
							'type'=>'text','name'=>'loggedin','value'=>'Logged in','title'=>'\'logged in\' text'
						),
						'loggedinas'=>array(
							'type'=>'text','name'=>'loggedinas','value'=>'Logged in as','title'=>'\'logged in as\' text'
						),
						'topostcomment'=>array(
							'type'=>'text','name'=>'topostcomment','value'=>'to post a comment','title'=>'\'to post a comment\' text'
						),
						'logout'=>array(
							'type'=>'text','name'=>'logout','value'=>'Logout','title'=>'Logout text'
						),
						'name'=>array(
							'type'=>'text','name'=>'name','value'=>'Name','title'=>'Name text'
						),
						'mail'=>array(
							'type'=>'text','name'=>'mail','value'=>'Mail','title'=>'Mail text'
						),
						'website'=>array(
							'type'=>'text','name'=>'website','value'=>'Website','title'=>'Website text'
						),
						'addcomment'=>array(
							'type'=>'text','name'=>'addcomment','value'=>'Add comment','title'=>'Add comment text'
						),
						'postauthor'=>array(
							'type'=>'text','name'=>'says','value'=>'Post author','title'=>'\'says\' text'
						),
						'reply'=>array(
							'type'=>'text','name'=>'reply','value'=>'Reply','title'=>'\'reply\' to threaded comment text'
						),
						'cancelreply'=>array(
							'type'=>'text','name'=>'cancelreply','value'=>'Cancel reply','title'=>'\'Cancel reply\' text'
						),
						'edit'=>array(
							'type'=>'text','name'=>'edit','value'=>'Edit','title'=>'\'edit\' comment text, only visible to administrators'
						),
						'delete'=>array(
							'type'=>'text','name'=>'delete','value'=>'Delete','title'=>'\'delete\' comment text, only visible to administrators'
						),
						'spam'=>array(
							'type'=>'text','name'=>'spam','value'=>'Spam','title'=>'\'spam\' comment text, only visible to administrators'
						),
						'comment'=>array(
							'type'=>'text','name'=>'comment','value'=>'Comment','title'=>'\'Comment\' textarea\'s title'
						),
						'nextcomments'=>array(
							'type'=>'text','name'=>'nextcomments','value'=>'Newer comments','title'=>'\'Next comments\' link text'
						),
						'prevcomments'=>array(
							'type'=>'text','name'=>'prevcomments','value'=>'Older comments','title'=>'\'Previious comments\' link text'
						),
						'commenttime'=>array(
							'type'=>'text','name'=>'commenttime','value'=>'%1$s at %2$s','title'=>'Comment time format (%1$s - date, %2$s - time)'
						)
					)
				),
				'pagination'=>array(
					'type'=>'group', 'name'=>'pagination', 'title'=>'Pagination Text',
					'content'=>array(
						'firstpage'=>array(
							'type'=>'text','name'=>'firstpage','value'=>'1','title'=>'\'First page\' text'
						),
						'lastpage'=>array(
							'type'=>'text','name'=>'lastpage','value'=>'last page','title'=>'\'Last page\' text'
						)
					)
				)
				
			)
		),
		'contactform'=>array(
			'name'=>'Contact form',
			'icon' => '&#xf003;',
			'editable' => true,
			'content'=>array(
				'txt'=>array(
					'type'=>'paragraph','name'=>'','value'=>'To add contact form to your website, create a new page and choose the "Contact form" template'
				),
				'apikeynote'=>array(
					'type'=>'paragraph','name'=>'','value'=>'IMPORTANT!<br />According to the Google Maps APIs Standard Plan updates implemented on June 22, 2016 all Google Maps JavaScript API applications require authentication.<br />To get started using the Google Maps <b>you have to generate an API key for your website</b> and enter it on the <a href="'.get_site_url().'/wp-admin/admin.php?page=smt_integration'.'" alt="Integration" title="Integration">Integration</a> page of the theme settings. Use the instruction on how to generate an API key here: https://developers.google.com/maps/documentation/javascript/get-api-key'
				),
				'address'=>array(
					'type'=>'text','name'=>'address','value'=>'Baker St 221b, London','title'=>'Map Address', 'hint'=>'Enter the address or the longitude and latitude for the Google map center. For example, "Baker St 221b, London" and "51.523795,-0.158465" have the same result.'
				),
				'details'=>array( 'name' => 'details', 'type'=>'contact_details', 'title'=>'Contact details', 'value'=>array (
						'1' => Array (
							'icon' => '&#xf041;',
							'content' => 'Baker St 221b, London'
						),
						'2' => Array (
							'icon' => '&#xf095;',
							'content' => '555-37-50'
						),
						'3' => Array (
							'icon' => '&#xf003;',
							'content' => 'mail@yoursite.com'
						),
						'4' => Array (
							'icon' => '&#xf17e;',
							'content' => 'skype_id'
						)
					), 'datascheme' => array(
						
						'icon'=>array(
							'type'=>'icon', 'name'=>'icon', 'value'=>'', 'title'=>'Detail icon'
						),
						'content'=>array(
							'type'=>'text','name'=>'content','value'=>'','title'=>'Contact detail'
						)
						
					), 'hint'=>'Create your contact list. To add a new contact fill in the form and click the "Add detail" button. You can upload your contact icon, or choose an icon from the standard presets.'
				),
				'layout'=>array(
					'type'=>'select', 'name'=>'layout', 'value'=>'horizontal', 'title'=>'Details layout', 
					'params'=>array(
						'horizontal'=>'Horizontal',
						'vertical'=>'Vertical'
					),'hint'=>'Select horizontal or vertical layout for contact details'
				),				
				'contactform'=>array( 'name' => 'contactform', 'type'=>'contact_form', 'title'=>'Contact form', 'value'=>array (
						'1' => Array (
							'title' => 'Your name',
							'required'=>1,
							'type' => 'text'
						),
						'2' => Array (
							'title' => 'Your email',
							'required'=>1,
							'regex'=>"^.+@.+\..+$",
							'type' => 'text'
						),
						'3' => Array (
							'title' => 'Topic',
							'required'=>0,
							'type' => 'text'
						),
						'4' => Array (
							'title' => 'Your message',
							'required'=>1,
							'type' => 'textarea'
						)
					), 'datascheme' => array(
						
						'title'=>array(
							'type'=>'text','name'=>'title','value'=>'','title'=>'Field name'
						),
						'type'=>array(
							'type'=>'select', 'name'=>'type', 'value'=>'text', 'title'=>'Field type', 
							'params'=>array(
								'text'=>'Text',
								'textarea'=>'Textarea'
							),'hint'=>'Select a field type'
						),
						'required'=>array(
							'type'=>'check','name'=>'required','value'=>'1','title'=>'Required?'
						),
						'regex'=>array(
							'type'=>'text','name'=>'regex','value'=>'','title'=>'Regex for this type of a field'
						)
						
					), 'hint'=>'Create your contact form'
				),
				'email'=>array(
					'type'=>'text','name'=>'email','value'=>'mail@yourdomain.com','title'=>'Email for messages', 'hint'=>'The contact form will be submitted to this email. Do not leave this field empty to display the contact form'
				)
			)
		),
		'contacts'=>array(
			'name'=>'Contacts',
			'icon' => '&#xf1cd;',
			'editable' => false,
			'content'=>array(
				'txt'=>array(
					'type'=>'paragraph','name'=>'','value'=>'
    <b>Theme Author:</b> <a href="https://smthemes.com">SMThemes</a><br />
    <b>Theme Homepage:</b> <a href="https://smthemes.com/'.$themename.'">http://smthemes.com/'.$themename.'</a><br />
    <b>Support Forums:</b> <a href="https://smthemes.com/support/forum/'.$themename.'-free-wordpress-theme">http://smthemes.com/support/forum/'.$themename.'-free-wordpress-theme</a>'
				)
			)
		),
		'updates'=>array(
			'name'=>'Fresh themes',
			'icon' => '&#xf09e;',
			'editable' => false,
			'content'=>array(
				'updates'=>array(
					'type'=>'updates','name'=>'updates','value'=>'','title'=>'Catalog'
				),
			)
		),
		'activate'=>array(
			'name'=>'Remove Footer Links',
			'icon' => '&#xf084;',
			'editable' => false,
			'content'=>array(
				'activator'=>array(
					'type'=>'activator','name'=>'activator','value'=>'','title'=>'Activation Key'
				),
			)
		)
		
    );

	if ( is_user_logged_in() && current_user_can('administrator') && is_admin() ) {
		require_once get_template_directory().'/extras/admin.slider.php';
		require_once get_template_directory().'/extras/admin.showroom.php';
		require_once get_template_directory().'/extras/admin.feedback.php';
	}
?>
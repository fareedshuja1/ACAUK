<?php
/*
Plugin Name: Video Feed Box
*/
    
    



    

class SMT_VideoFeed extends WP_Widget 
{
    function __construct(){
        
        $widget_options = array('description' => 'Video Feed Box widget.' );
        $control_options = array();
        parent::__construct('smt_videofeed', '&raquo; Video Feed', $widget_options, $control_options);
    }

    function widget($args, $instance){
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$width = intval($instance['width']);
        $videos = $instance['videos'];
   
        if( is_array( $instance['videos'] ) ) { ?>
		
			<?php echo $args['before_widget']?>
			
			<?php  if ( $title ) {  ?><?php echo $args['before_title']?><?php echo $title?><?php echo $args['after_title']?><?php } ?>
			
			<ul>
			
				<?php 	foreach ( $videos as $video ) { ?>
			
					<li>
						<a href='<?php echo $video['url']; ?>' rel='nofollow' target='_blank'><?php echo $video['title']; ?></a>
					<?php
						switch( true ) {
							case preg_match( '/youtube.com\/watch\?v=(.*)/', $video['url'], $matches ): ?>
								<div class="video_frame">
									<a href="<?php echo $video['url']; ?>" target="_blank"><img src="<?php echo 'http://img.youtube.com/vi/' . $matches[ 1 ] . '/0.jpg'; ?>" /></a>
								</div>
								<?php break;
							case preg_match( '/vimeo.com\/(.*)\//', $video['url'], $matches ): 
								$videoinf = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$matches[ 1 ].".php"));
								?>
								<div class="video_frame">
									<a href="<?php echo $video['url']; ?>" target="_blank"><img src="<?php echo $videoinf[0]['thumbnail_large']; ?>" /></a>
								</div>
								<?php break;
						}
					?>
					</li>
				<?php } ?>
			</ul>
			
           <?php echo $args['after_widget']?>
		<?php }
    }

    function update($new_instance, $old_instance) 
    {				
    	$instance = $old_instance;
    	$instance['title'] = strip_tags($new_instance['title']);
		$instance['width'] = intval($new_instance['width']);
        $instance['videos'] = $new_instance['videos'];
		unset($instance['videos']['the__id__']);
        return $instance;
    }
    
     function form($instance){
		global $video_defaults;
		$instance = wp_parse_args( (array) $instance, array(
			'title' => 'Video',
			'videos' => array(
				array(
					'title' => 'The Mountain', 
					'url' => 'http://vimeo.com/22439234/'
				),
				array(
					'title' => 'Amazing nature scenery', 
					'url' => 'http://www.youtube.com/watch?v=6v2L2UGZJAM'
				)
			)
		));
        
		echo '<div class="smt_widget_wrapper">';
		
		smt_show_option ( array(
			'type' => 'text',
			'title' => 'Title',
			'name' => $this->get_field_name( 'title' ),
			'value' => esc_attr( $instance[ 'title' ] )
		) );
		
		smt_show_option ( array(
			'type' => 'widget_videos',
			'title' => 'Videos',
			'name' => $this->get_field_name( 'videos' ),
			'value' => $instance[ 'videos' ],
			'datascheme' => array(
						
				'title'=>array(
					'type'=>'text','name'=>'title','value'=>'','title'=>'Title For Video'
				),
				'url'=>array(
					'type'=>'text','name'=>'url','value'=>'','title'=>'Video URL'
				)
				
			)
		) );
		
        echo '</div>';
    }
} 


	function smt_show_option_widget_videos( $param ) { ?>
		<label><?php echo $param[ 'title' ]; ?>:</label>
		<ul class="smt_object_list">
			<?php $i = 0; ?>
			<?php foreach( $param[ 'value' ] as $detail ) { $i++; ?>

				<li>
					<div class="smt_object_list_element_caption">
						<span class="smt_object_list_element_title"><?php echo $detail[ 'title' ]; ?></span>
						<span class="smt_object_list_element_remove">Remove</span>
						<div class="clear"></div>
					</div>
					<div class="smt_object_list_element_content">
						<?php 
							foreach( $param[ 'datascheme' ] as $option ) { 
							
								$option[ 'value' ] = $detail[ $option[ 'name' ] ];
								$option[ 'name' ] = $param['name'].'['.$i.']['.$option[ 'name' ].']';
								smt_show_option( $option );
								
							} 
						?>
					</div>
				</li>
				
			<?php } ?>
			
			
			
		</ul>
		<div class="smt_object_list_new" data-name="<?php echo $param[ 'name' ]; ?>"><span class="smt_object_list_element_title">Add new video</span><div class="clear"></div>
			<div class="smt_object_list_blank">
				<li>
					<div class="smt_object_list_element_caption">
						<span class="smt_object_list_element_title">New Video</span>
						<span class="smt_object_list_element_remove">Remove</span>
						<div class="clear"></div>
					</div>
					<div class="smt_object_list_element_content">
						<?php 
							foreach( $param[ 'datascheme' ] as $option ) { 
								smt_show_option( $option );
							} 
						?>
					</div>
				</li>
			</div>
		</div>
	<?php }
?>
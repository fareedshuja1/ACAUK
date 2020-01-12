<?php
/*
Plugin Name: Banners 125px
*/
?>
<?php
    
    
    



    

class SMT_SocialProfiles extends WP_Widget 
{
    function __construct(){
        
        $widget_options = array('description' => 'Add buttons to your social network profiles.' );
        $control_options = array();
        parent::__construct('smt_social_profiles', '&raquo; Social Profiles', $widget_options, $control_options);
    }

    function widget($args, $instance){
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$width = intval($instance['width']);
        $profiles = $instance['profiles'];
   
        if( is_array( $profiles ) ) { ?>
			<?php echo $args['before_widget']?>
			
			<?php  if ( $title ) {  ?><?php echo $args['before_title']?><?php echo $title?><?php echo $args['after_title']?><?php } ?> 
			
			<?php foreach( $profiles as $profile ) { ?>
                    <a href="<?php echo strip_tags($profile['url'])?>" target="_blank">
						<img title="<?php echo strip_tags($profile['title'])?>" alt="<?php echo strip_tags($profile['title']) ?>" src="<?php echo strip_tags($profile['icon'])?>" height="<?php echo $width?>" width="<?php echo $width?>" />
					</a>
			<?php } ?>
			
           <?php echo $args['after_widget']?>
            
        <?php }
    }

    function update($new_instance, $old_instance) 
    {				
    	$instance = $old_instance;
    	$instance['title'] = strip_tags($new_instance['title']);
		$instance['width'] = intval($new_instance['width']);
        $instance['profiles'] = $new_instance['profiles'];
		unset($instance['profiles']['the__id__']);
        return $instance;
    }
    
    function form($instance){
        
        $instance = wp_parse_args( (array) $instance, array(
			'width' =>'32',
			'title' => 'Social Profiles',
			'profiles' => array(
				array(
					'title' => 'Twitter', 
					'url' => 'http://twitter.com/', 
					'icon' => get_template_directory_uri() . '/images/social-profiles/twitter.png'
				),
				array(
					'title' => 'Facebook', 
					'url' => 'http://facebook.com/', 
					'icon' => get_template_directory_uri() . '/images/social-profiles/facebook.png'
				),
				array(
					'title' => 'Google Plus', 
					'url' => 'https://plus.google.com/', 
					'icon' => get_template_directory_uri() . '/images/social-profiles/gplus.png'
				),
				array(
					'title' => 'LinkedIn', 
					'url' => 'http://www.linkedin.com/', 
					'icon' => get_template_directory_uri() . '/images/social-profiles/linkedin.png'
				),
				array(
					'title' => 'RSS Feed', 
					'url' => smt_getOption( 'integration','rss' ), 
					'icon' => get_template_directory_uri() . '/images/social-profiles/rss.png'
				),
				array(
					'title' => 'Email', 
					'url' => 'mailto:your@email.com', 
					'icon' => get_template_directory_uri() . '/images/social-profiles/email.png'
				)
			)
		) );
        
		echo '<div class="smt_widget_wrapper">';
		
		smt_show_option ( array(
			'type' => 'text',
			'title' => 'Title',
			'name' => $this->get_field_name( 'title' ),
			'value' => esc_attr( $instance[ 'title' ] )
		) );
		
		smt_show_option ( array(
			'type' => 'text',
			'title' => 'Width',
			'name' => $this->get_field_name( 'width' ),
			'value' => esc_attr( $instance[ 'width' ] )
		) );
		
		smt_show_option ( array(
			'type' => 'social_profiles',
			'title' => 'Profiles',
			'name' => $this->get_field_name( 'profiles' ),
			'value' => $instance[ 'profiles' ],
			'datascheme' => array(
						
				'title'=>array(
					'type'=>'text','name'=>'title','value'=>'','title'=>'Profile Title'
				),
				'url'=>array(
					'type'=>'text','name'=>'url','value'=>'','title'=>'Profile URL'
				),
				'icon'=>array(
					'type'=>'image','name'=>'icon','value'=>'','title'=>'Profile Icon'
				),
			)
		) );
		
        echo '</div>';
		
		
    }
} 

	function smt_show_option_social_profiles( $param ) { ?>
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
		<div class="smt_object_list_new" data-name="<?php echo $param[ 'name' ]; ?>"><span class="smt_object_list_element_title">Add new profile</span><div class="clear"></div>
			<div class="smt_object_list_blank">
				<li>
					<div class="smt_object_list_element_caption">
						<span class="smt_object_list_element_title">New Profile</span>
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
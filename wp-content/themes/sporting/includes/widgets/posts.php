<?php
/**
 * Adds Foo_Widget widget.
 */
class SMT_Posts extends WP_Widget {
	
	function __construct() {
		parent::__construct(
			'smt_posts',
			'&raquo; Posts with Images',
			array( 'description' => 'Advanced widget for displaying the recent posts or posts from the selected categories or tags.' ) 
		);
	}
	
	function widget($args, $instance){
		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        
		?>
	
        <?php echo $args['before_widget']?>
		
        <?php  if ( $title ) {  ?><?php echo $args['before_title']?><?php echo $title?><?php echo $args['after_title']?><?php }  ?>
		
		<ul>
        	<?php
                switch ($instance['order_by']) {
                    case 'none' : $order_query = ''; break;
                    case 'id_asc' : $order_query = '&orderby=ID&order=ASC'; break;
                    case 'id_desc' : $order_query = '&orderby=ID&order=DESC'; break;
                    case 'date_asc' : $order_query = '&orderby=date&order=ASC'; break;
                    case 'date_desc' : $order_query = '&orderby=date&order=DESC'; break;
                    case 'title_asc' : $order_query = '&orderby=title&order=ASC'; break;
                    case 'title_desc' : $order_query = '&orderby=title&order=DESC'; break;
                    default : $order_query = '&orderby=' . $instance['order_by'];
                }
              
	
                $posts=get_posts('posts_per_page=' . $instance['posts_number'] .  $order_query);
				
                if ( count( $posts ) > 0  ):
				foreach( $posts as $p ) { ?>
                    <li>
						
						
						<?php if ( in_array( 'date', $instance['display'] ) ) {  ?>
							<span class='post-date'>
								<div class="day"><?php echo get_the_date( 'd', $p->ID ); ?></div>
								<?php echo get_the_date( 'M', $p->ID ); ?>
							</span>
						<?php } ?>
						
                        <?php if ( in_array( 'featured_image', $instance['display'] ) && has_post_thumbnail($p->ID) ) { ?>
							<?php echo get_the_post_thumbnail( $p->ID, 'smt_sidebarposts', array() ); ?> 
						<?php } ?>
						
                        <?php if ( in_array( 'title', $instance['display'] ) ) { ?> 
							<a class="title" href="<?php echo get_permalink($p->ID); ?>" rel="bookmark" title="<?php echo $p->post_title; ?>"><?php echo $p->post_title?></a>
						<?php } ?>
						
						<?php if ( in_array( 'content', $instance['display'] ) ) { ?> 
							<?php echo strip_tags(smt_excerpt('maxchar='.$instance['excerpt_length'], $p->ID),'<strong><b><i><p><abbr><acronim><cite><q><strike>'); ?>
						<?php } ?>
						
						<?php if ( in_array( 'author', $instance['display'] ) ) { ?> 
						<span class="post_author"><?php echo get_the_author_meta( 'display_name', $p->post_author ); ?></span>
						<?php } ?>
                    </li>
                <?php
                }
                endif;
                
            ?>
            </ul>
         <?php echo $args['after_widget']?>
        <?php
    }

    function update($new_instance, $old_instance){
    	$instance = $old_instance;
    	$instance['title'] = strip_tags($new_instance['title']);
        $instance['posts_number'] = strip_tags($new_instance['posts_number']);
        $instance['order_by'] = strip_tags($new_instance['order_by']);
		$instance['display'] = $new_instance['display'];
        $instance['excerpt_length'] = strip_tags($new_instance['excerpt_length']);
        return $instance;
    }
    
    function form( $instance )  {	
		$instance = wp_parse_args( (array) $instance, array(
			'title' => 'Recent Posts',
			'posts_number' => '5',
			'order_by' => 'none',
			'display' => array( 'content', 'title', 'date', 'featured_image' ),
			'excerpt_length' => '120'
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
			'title' => 'Number Of Posts',
			'name' => $this->get_field_name( 'posts_number' ),
			'value' => esc_attr( $instance[ 'posts_number' ] )
		) );
		
		smt_show_option ( array(
			'type' => 'text',
			'title' => 'The Excerpt Length',
			'name' => $this->get_field_name( 'excerpt_length' ),
			'value' => esc_attr( $instance[ 'excerpt_length' ] )
		) );
		
		smt_show_option ( array(
			'type' => 'select',
			'title' => 'Order Posts By',
			'name' => $this->get_field_name( 'order_by' ),
			'value' => esc_attr( $instance[ 'order_by' ] ),
			'params' => array( 
				'none' => 'None (Default)',
				'id_asc' => 'ID ( Ascending )',
				'id_desc' => 'ID ( Descending )',
				'date_asc' => 'Date ( Ascending )',
				'date_desc' => 'Date ( Descending )',
				'title_asc' => 'Title ( Ascending )',
				'title_desc' => 'Title ( Descending  )',
				'rand' => 'Random'
			)
		) );
		
		
		smt_show_option ( array(
			'type' => 'multiselect',
			'title' => 'Display Elements',
			'name' => $this->get_field_name( 'display' ).'[]',
			'value' => $instance[ 'display' ],
			'params' => array( 
				'content' => 'Content',
				'title' => 'Title',
				'date' => 'Date',
				'featured_image' => 'Featured Image',
				'author' => 'Author'
			)
		) );
		
        echo '</div>';
        
		?>
		<script>
			prepareSelects();
		</script>
		<?php
    }

	
}
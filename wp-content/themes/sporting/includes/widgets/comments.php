<?php
/*
Plugin Name: Comments
*/
?>
<?php





class SMT_Comments extends WP_Widget {
    function __construct() {
        $widget_options = array('description' => 'Advanced widget for displaying the recent posts with avatars' );
        $control_options = array( 'width' => 400);
		parent::__construct('smt_comments', '&raquo; Comments with Avatars', $widget_options, $control_options);
    }

   function widget($args, $instance){
        global $wpdb;
		
        $title = apply_filters('widget_title', $instance['title']);
        $comments_number = $instance['comments_number'];
        $query = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved, comment_type, 
    			SUBSTRING(comment_content,1,50) AS com_excerpt 
    		FROM ".$wpdb->comments ."
    		LEFT OUTER JOIN ".$wpdb->posts." ON (".$wpdb->comments.".comment_post_ID = ".$wpdb->posts.".ID) 
    		WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' 
    		ORDER BY comment_date_gmt DESC 
    		LIMIT ".$comments_number;
    	$comments = $wpdb->get_results($query);
		
        ?>
		
        <?php echo $args['before_widget']?>
		
        <?php if ( $title ) { ?><?php echo $args['before_title']?><?php echo $title?><?php echo $args['after_title']?><?php } ?>
		
		<ul>
			<?php foreach ($comments as $comment) { ?>
				<li>
					<?php if ( in_array( 'avatar', $instance['display'] ) ) { ?> 
						<div class="comment_avatar"><?php echo get_avatar( $comment, 32 )?></div>
					<?php } ?>
                    
					<?php if ( in_array( 'author', $instance['display'] ) ) { ?> 
						<a href='<?php echo get_permalink($comment->ID)  . "#comment-" . $comment->comment_ID; ?>' class="comment_author"><?php echo $comment->comment_author; ?></a>
					<?php } ?>
					
					<?php if ( in_array( 'comment', $instance['display'] ) ) { ?>
						<div class="comment_text">
							<?php echo preg_replace( '@(.*)\s[^\s]*$@s', '\\1', iconv_substr( strip_tags( $comment->com_excerpt ), 0, $instance['comment_length'], 'utf-8' ) ); ?>
						</div>
					<?php } ?>					
					
					<?php if ( in_array( 'more', $instance['display'] ) ) { ?> 
						<a href='<?php echo get_permalink($comment->ID)  . "#comment-" . $comment->comment_ID; ?>'><?php echo $instance['read_more_text']; ?></a>
					<?php } ?>
                    
					
				</li>
			<?php } ?>
		</ul>
		
        <?php echo $args['after_widget']?>
     <?php
    }

    function update($new_instance, $old_instance) {				
    	$instance = $old_instance;
    	$instance['title'] = strip_tags($new_instance['title']);
        $instance['comments_number'] = strip_tags($new_instance['comments_number']);
        $instance['display'] = $new_instance[ 'display' ];
        $instance['read_more_text'] = strip_tags( $new_instance['read_more_text'] );
        $instance['comment_length'] = strip_tags($new_instance['comment_length']);
        return $instance;
    }
    
    function form($instance){
        global $comments_defaults;
		$instance = wp_parse_args( (array) $instance, array(
			'title' => 'Recent Comments',
			'comments_number' => '5',
			'display' =>array( 'author', 'comment', 'avatar' ),
			'read_more_text' => '&raquo;',
			'comment_length' => '50',
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
			'title' => 'Number of comments',
			'name' => $this->get_field_name( 'comments_number' ),
			'value' => esc_attr( $instance[ 'comments_number' ] )
		) );
		
		smt_show_option ( array(
			'type' => 'text',
			'title' => 'The comment length',
			'name' => $this->get_field_name( 'comment_length' ),
			'value' => esc_attr( $instance[ 'comment_length' ] )
		) );
		
		smt_show_option ( array(
			'type' => 'text',
			'title' => '"Read more" text',
			'name' => $this->get_field_name( 'read_more_text' ),
			'value' => esc_attr( $instance[ 'read_more_text' ] )
		) );
		
		smt_show_option ( array(
			'type' => 'multiselect',
			'title' => 'Display Elements',
			'name' => $this->get_field_name( 'display' ).'[]',
			'value' => $instance[ 'display' ],
			'params' => array( 
				'author' => 'Author',
				'more' => 'Read More',
				'comment' => 'Comment',
				'avatar' => 'Avatar'
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
?>
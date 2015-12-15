<?php
// Creating the widget 
class wpb_widget_recent_comments extends WP_Widget {

	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'blogpost-directory-recent-comments', 

			// Widget name will appear in UI
			__('BlogPost - Recent comments', 'vh'), 

			// Widget description
			array( 'description' => __( 'Just a simple widget that displays recent comments.', 'vh' ), ) 
		);
	}

	// Creating widget front-end
	// This is where the action happens
	public function widget( $args, $instance ) {
		global $post, $wpdb, $wp_query;
		$maintitle      = $instance['maintitle'];
		$limit          = $instance['limit'];
		$thePostID      = $wp_query->post->ID;

		// before and after widget arguments are defined by themes

		echo $args['before_widget'];
		
		if ( !empty($maintitle) ) {
			echo '<div class="item-title-bg">';
			echo '<h4>' . $maintitle . '</h4>';
			echo '</div>';
		}

		if ( empty($limit) ) {
			$limit = 5;
		}

		$querystr = "SELECT comment_author, comment_author_email, comment_content, comment_post_ID FROM " . $wpdb->prefix . "comments ORDER BY comment_date DESC LIMIT ".$limit;
		$queryresults = $wpdb->get_results($querystr);

		if ( !empty($queryresults) ) {
			echo "<div class='recent-comments'>";
			foreach ($queryresults as $value) {
				echo "<div class='recent-comments-item'>";
					echo "<div class='recent-comments-author'>";
						echo '<span class="author-image">' . get_avatar($value->comment_author_email, 22) . '</span>';
						echo '<span class="author-name">' . $value->comment_author . ':</span>';
					echo "</div>";
					echo "<div class='recent-comments-content'>";
						echo '<span class="comment-text">';
							if ( strlen($value->comment_content) > 50 ) {
								$comment_content = substr($value->comment_content, 0, 50) . '..';
							} else {
								$comment_content = $value->comment_content;
							}
							echo wp_kses( 
								$comment_content, 
								array(
									'a' => array(
										'href' => array(),
										'class' => array()
									)
								)
							);
						echo '</span>';
					echo "</div>";
					echo "<div class='recent-comments-post'>";
						echo '<a href="' . get_permalink( $value->comment_post_ID ) . '" class="comment-post ' . vh_get_random_circle() . '">' . get_the_title( $value->comment_post_ID ) . '</a>';
					echo "</div>";
				echo "</div>";
			}
			echo "</div>";
		} else {
			echo "
			<div class='recent-activities'>
				<div class='info-container empty'>
					<div class='main-item-text'>";
						_e('There hasn\'t been any activity in this listing.', 'vh');
					echo "
					</div>
					<div class='clearfix'></div>
				</div>
			</div>";
		}
	
		echo $args['after_widget'];
	}
		
	// Widget Backend 
	public function form( $instance ) {

		if ( isset( $instance[ 'maintitle' ] ) ) {
			$maintitle = $instance[ 'maintitle' ];
		} else {
			$maintitle = '';
		}

		if ( isset( $instance[ 'limit' ] ) ) {
			$limit = $instance[ 'limit' ];
		} else {
			$limit = '';
		}

		// Widget admin form
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'maintitle' ) ); ?>"><?php _e( 'Widget title:', 'vh' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'maintitle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'maintitle' ) ); ?>" type="text" value="<?php echo esc_attr( $maintitle ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php _e( 'Whats the limit?:', 'vh' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>" />
		</p>

		<?php 
	}
	
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['maintitle'] = ( ! empty( $new_instance['maintitle'] ) ) ? strip_tags( $new_instance['maintitle'] ) : '';
		$instance['limit'] = ( ! empty( $new_instance['limit'] ) ) ? strip_tags( $new_instance['limit'] ) : '';

		return $instance;
	}
} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget_recent_comments() {
	register_widget( 'wpb_widget_recent_comments' );
}
add_action( 'widgets_init', 'wpb_load_widget_recent_comments' );
?>
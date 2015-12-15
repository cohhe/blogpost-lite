<?php
// Creating the widget 
class wpb_widget_sidebar_comments extends WP_Widget {

	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'blogpost-directory-sidebar-comments', 

			// Widget name will appear in UI
			__('BlogPost - Sidebar comments', 'vh'), 

			// Widget description
			array( 'description' => __( 'Just a simple widget that displays comments at sidebar.', 'vh' ), ) 
		);
	}

	// Creating widget front-end
	// This is where the action happens
	public function widget( $args, $instance ) {
		global $post, $wpdb, $wp_query;
		$thePostID      = $wp_query->post->ID;

		// before and after widget arguments are defined by themes

		echo $args['before_widget'];

		if ( get_theme_mod('blogpost_sidebar_comments', false) == true || DEMO_COMMENTS ) { ?>
			<div class="comments_container vc_col-sm-12">
				<div class="clearfix"></div>
				<?php
				comments_template( '/sidebar-comments.php', true ); ?>
			</div>
		<?php }
	
		echo $args['after_widget'];
	}
	
} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget_sidebar_comments() {
	register_widget( 'wpb_widget_sidebar_comments' );
}
add_action( 'widgets_init', 'wpb_load_widget_sidebar_comments' );
?>
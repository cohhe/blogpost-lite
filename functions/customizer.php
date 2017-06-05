<?php
/**
 * Blogpost 1.0 Theme Customizer support
 *
 * @package WordPress
 * @subpackage Blogpost
 * @since Blogpost 1.0
 */

/**
 * Implement Theme Customizer additions and adjustments.
 *
 * @since Blogpost 1.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function blogpost_customizer_register( $wp_customize ) {
	// Add General setting panel and configure settings inside it
	$wp_customize->add_panel( 'blogpost_general_panel', array(
		'priority'       => 250,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'General settings' , 'blogpost-lite'),
		'description'    => __( 'You can configure your general theme settings here' , 'blogpost-lite')
	) );

	// Add 404 setting panel and configure settings inside it
	$wp_customize->add_panel( 'blogpost_404_panel', array(
		'priority'       => 250,
		'capability'     => 'edit_theme_options',
		'title'          => __( '404 page' , 'blogpost-lite'),
		'description'    => __( 'You can configure your themes 404 page settings here.' , 'blogpost-lite')
	) );

	// Website logo
	$wp_customize->add_section( 'blogpost_general_logo', array(
		'priority'       => 10,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Website logo' , 'blogpost-lite'),
		'description'    => __( 'Please upload your logo, recommended logo size should be between 262x80' , 'blogpost-lite'),
		'panel'          => 'blogpost_general_panel'
	) );

	$wp_customize->add_setting( 'blogpost_logo', array( 'sanitize_callback' => 'esc_url_raw' ) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'blogpost_logo', array(
		'label'    => __( 'Website logo', 'blogpost-lite' ),
		'section'  => 'blogpost_general_logo',
		'settings' => 'blogpost_logo',
	) ) );

	// Logo retina ready
	$wp_customize->add_setting( 'blogpost_logo_retina_ready', array( 'sanitize_callback' => 'blogpost_sanitize_checkbox' ) );

	$wp_customize->add_control(
		'blogpost_logo_retina_ready',
		array(
			'label'       => 'Website logo is Retina ready',
			'description' => 'You have to uplaod website logo which is 2x in dimensions. It will automatically scaled down for normal displays and prepared for High resolution displays.',
			'section'     => 'blogpost_general_logo',
			'type'        => 'checkbox',
		)
	);

	// Page layout
	$wp_customize->add_section( 'blogpost_general_layout', array(
		'priority'       => 20,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Layout' , 'blogpost-lite'),
		'description'    => __( 'Select a layout style.<br />(full, left side sidebar, right side sidebar)' , 'blogpost-lite'),
		'panel'          => 'blogpost_general_panel'
	) );

	$wp_customize->add_setting(
		'blogpost_layout',
		array(
			'default'           => 'full',
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control(
		'blogpost_layout',
		array(
			'type' => 'radio',
			'label' => 'Layout',
			'section' => 'blogpost_general_layout',
			'choices' => array(
				'full' => 'Full',
				'right' => 'Right'
			)
		)
	);

	// Search background
	$wp_customize->add_section( 'blogpost_general_search_bg', array(
		'priority'       => 40,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Search background' , 'blogpost-lite'),
		'description'    => __( 'Upload an image to use as your search background' , 'blogpost-lite'),
		'panel'          => 'blogpost_general_panel'
	) );

	$wp_customize->add_setting( 'blogpost_search_background', array( 'sanitize_callback' => 'esc_url_raw' ) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'blogpost_search_background', array(
		'label'    => __( 'Search background', 'blogpost-lite' ),
		'section'  => 'blogpost_general_search_bg',
		'settings' => 'blogpost_search_background',
	) ) );

	// Show comments at sidebar
	$wp_customize->add_section( 'blogpost_general_sidebar_comments', array(
		'priority'       => 50,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Show comments at sidebar' , 'blogpost-lite'),
		'description'    => __( 'Remove comment form bottom of the post and relocate it at sidebar.' , 'blogpost-lite'),
		'panel'          => 'blogpost_general_panel'
	) );

	$wp_customize->add_setting( 'blogpost_sidebar_comments', array( 'sanitize_callback' => 'blogpost_sanitize_checkbox' ) );

	$wp_customize->add_control(
		'blogpost_sidebar_comments',
		array(
			'label'      => 'Show comments at sidebar',
			'section'    => 'blogpost_general_sidebar_comments',
			'type'       => 'checkbox',
		)
	);

	// Side menu image
	$wp_customize->add_section( 'blogpost_general_side_image', array(
		'priority'       => 60,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Side menu image' , 'blogpost-lite'),
		'description'    => __( 'Upload an image to use as your side menu background' , 'blogpost-lite'),
		'panel'          => 'blogpost_general_panel'
	) );

	$wp_customize->add_setting( 'blogpost_side_menu_img', array( 'sanitize_callback' => 'esc_url_raw' ) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'blogpost_side_menu_img', array(
		'label'    => __( 'Side menu image', 'blogpost-lite' ),
		'section'  => 'blogpost_general_side_image',
		'settings' => 'blogpost_side_menu_img',
	) ) );

	// Blog columns
	$wp_customize->add_section( 'blogpost_general_blog_columns', array(
		'priority'       => 70,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Blog columns' , 'blogpost-lite'),
		'description'    => __( 'Select in how many columns will the blog posts be divided.' , 'blogpost-lite'),
		'panel'          => 'blogpost_general_panel'
	) );

	$wp_customize->add_setting(
		'blogpost_blog_columns',
		array(
			'default'           => '3',
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control(
		'blogpost_blog_columns',
		array(
			'type' => 'select',
			'label' => 'Blog columns',
			'section' => 'blogpost_general_blog_columns',
			'choices' => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6'
			)
		)
	);

	// Scroll to top
	$wp_customize->add_section( 'blogpost_general_scrolltotop', array(
		'priority'       => 100,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Scroll to top' , 'blogpost-lite'),
		'description'    => __( 'Do you want to enable "Scroll to Top" button?' , 'blogpost-lite'),
		'panel'          => 'blogpost_general_panel'
	) );

	$wp_customize->add_setting( 'blogpost_scrolltotop', array( 'sanitize_callback' => 'blogpost_sanitize_checkbox' ) );

	$wp_customize->add_control(
		'blogpost_scrolltotop',
		array(
			'label'      => 'Scroll to top',
			'section'    => 'blogpost_general_scrolltotop',
			'type'       => 'checkbox',
		)
	);

	// Featured post
	$wp_customize->add_section( 'blogpost_featured_post', array(
		'priority'       => 110,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Featured post' , 'blogpost-lite'),
		'description'    => __( 'The ID of the featured post to show at the front page.' , 'blogpost-lite'),
		'panel'          => 'blogpost_general_panel'
	) );

	$wp_customize->add_setting( 'blogpost_featured_post_id', array( 'sanitize_callback' => 'sanitize_text_field' ) );

	$wp_customize->add_control(
		'blogpost_featured_post_id',
		array(
			'label'      => 'Featured post',
			'section'    => 'blogpost_featured_post',
			'type'       => 'text',
		)
	);

	// 404 page title
	$wp_customize->add_section( 'blogpost_404_page_title', array(
		'priority'       => 40,
		'capability'     => 'edit_theme_options',
		'title'          => __( '404 Page Title' , 'blogpost-lite'),
		'description'    => __( 'Set the page title that is displayed on the 404 Error Page.' , 'blogpost-lite'),
		'panel'          => 'blogpost_404_panel'
	) );

	$wp_customize->add_setting( 'blogpost_404_title', array( 'default' => 'This is somewhat embarrassing, isn\'t it?', 'sanitize_callback' => 'sanitize_text_field' ) );

	$wp_customize->add_control(
		'blogpost_404_title',
		array(
			'label'      => '404 Page Title',
			'section'    => 'blogpost_404_page_title',
			'type'       => 'text',
		)
	);

	// 404 page message
	$wp_customize->add_section( 'blogpost_404_page_message', array(
		'priority'       => 40,
		'capability'     => 'edit_theme_options',
		'title'          => __( '404 Page Message' , 'blogpost-lite'),
		'description'    => __( 'Set the message that is displayed on the 404 Error Page.' , 'blogpost-lite'),
		'panel'          => 'blogpost_404_panel'
	) );

	$wp_customize->add_setting( 'blogpost_404_message', array( 'default' => 'It seems we can\'t find what you\'re looking for. Perhaps searching, or one of the links below, can help.', 'sanitize_callback' => 'sanitize_text_field' ) );

	$wp_customize->add_control(
		'blogpost_404_message',
		array(
			'label'      => '404 Page Title',
			'section'    => 'blogpost_404_page_message',
			'type'       => 'text',
		)
	);

	// Social links
	$wp_customize->add_section( new blogpost_Customized_Section( $wp_customize, 'blogpost_social_links', array(
		'priority'       => 300,
		'capability'     => 'edit_theme_options'
		) )
	);

	$wp_customize->add_setting( 'blogpost_fake_field', array( 'sanitize_callback' => 'sanitize_text_field' ) );

	$wp_customize->add_control(
		'blogpost_fake_field',
		array(
			'label'      => '',
			'section'    => 'blogpost_social_links',
			'type'       => 'text'
		)
	);
}
add_action( 'customize_register', 'blogpost_customizer_register' );

if ( class_exists( 'WP_Customize_Section' ) && !class_exists( 'blogpost_Customized_Section' ) ) {
	class blogpost_Customized_Section extends WP_Customize_Section {
		public function render() {
			$classes = 'accordion-section control-section control-section-' . $this->type;
			?>
			<li id="accordion-section-<?php echo esc_attr( $this->id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
				<style type="text/css">
					.cohhe-social-profiles {
						padding: 14px;
					}
					.cohhe-social-profiles li:last-child {
						display: none !important;
					}
					.cohhe-social-profiles li i {
						width: 20px;
						height: 20px;
						display: inline-block;
						background-size: cover !important;
						margin-right: 5px;
						float: left;
					}
					.cohhe-social-profiles li a {
						height: 20px;
						line-height: 20px;
					}
					#customize-theme-controls>ul>#accordion-section-blogpost_social_links {
						margin-top: 10px;
					}
					.cohhe-social-profiles li.documentation {
						text-align: right;
						margin-bottom: 60px;
					}
				</style>
				<ul class="cohhe-social-profiles">
					<li class="documentation"><a href="http://documentation.cohhe.com/blogpost" class="button button-primary button-hero" target="_blank"><?php _e( 'Documentation', 'blogpost-lite' ); ?></a></li>
				</ul>
			</li>
			<?php
		}
	}
}

function blogpost_sanitize_checkbox( $input ) {
	// Boolean check 
	return ( ( isset( $input ) && true == $input ) ? true : false );
}

/**
 * Sanitize the Featured Content layout value.
 *
 * @since Blogpost 1.0
 *
 * @param string $layout Layout type.
 * @return string Filtered layout type (grid|slider).
 */
function blogpost_sanitize_layout( $layout ) {
	if ( ! in_array( $layout, array( 'slider' ) ) ) {
		$layout = 'slider';
	}

	return $layout;
}

/**
 * Add contextual help to the Themes and Post edit screens.
 *
 * @since Blogpost 1.0
 *
 * @return void
 */
function blogpost_contextual_help() {
	if ( 'admin_head-edit.php' === current_filter() && 'post' !== $GLOBALS['typenow'] ) {
		return;
	}

	get_current_screen()->add_help_tab( array(
		'id'      => 'blogpost',
		'title'   => __( 'Blogpost 1.0', 'blogpost-lite' ),
		'content' =>
			'<ul>' .
				'<li>' . sprintf( __( 'The home page features your choice of up to 6 posts prominently displayed in a grid or slider, controlled by the <a href="%1$s">featured</a> tag; you can change the tag and layout in <a href="%2$s">Appearance &rarr; Customize</a>. If no posts match the tag, <a href="%3$s">sticky posts</a> will be displayed instead.', 'blogpost-lite' ), admin_url( '/edit.php?tag=featured' ), admin_url( 'customize.php' ), admin_url( '/edit.php?show_sticky=1' ) ) . '</li>' .
				'<li>' . sprintf( __( 'Enhance your site design by using <a href="%s">Featured Images</a> for posts you&rsquo;d like to stand out (also known as post thumbnails). This allows you to associate an image with your post without inserting it. Blogpost 1.0 uses featured images for posts and pages&mdash;above the title&mdash;and in the Featured Content area on the home page.', 'blogpost-lite' ), 'http://codex.wordpress.org/Post_Thumbnails#Setting_a_Post_Thumbnail' ) . '</li>' .
				'<li>' . sprintf( __( 'For an in-depth tutorial, and more tips and tricks, visit the <a href="%s">Blogpost 1.0 documentation</a>.', 'blogpost-lite' ), 'http://codex.wordpress.org/Blogpost' ) . '</li>' .
			'</ul>',
	) );
}
add_action( 'admin_head-themes.php', 'blogpost_contextual_help' );
add_action( 'admin_head-edit.php',   'blogpost_contextual_help' );

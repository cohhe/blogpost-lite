<?php
/**
 * BlogPost functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 */

// Define file directories
define('BLOGPOST_HOME', get_template_directory());
define('BLOGPOST_FUNCTIONS', get_template_directory() . '/functions');
define('BLOGPOST_GLOBAL', get_template_directory() . '/functions/global');
define('BLOGPOST_WIDGETS', get_template_directory() . '/functions/widgets');
define('BLOGPOST_CUSTOM_PLUGINS', get_template_directory() . '/functions/plugins');
define('BLOGPOST_ADMIN', get_template_directory() . '/functions/admin');
define('BLOGPOST_ADMIN_IMAGES', get_template_directory_uri() . '/functions/admin/images');
define('BLOGPOST_METABOXES', get_template_directory() . '/functions/admin/metaboxes');
define('BLOGPOST_SIDEBARS', get_template_directory() . '/functions/admin/sidebars');

// Define theme URI
define('BLOGPOST_URI', get_template_directory_uri() .'/');
define('BLOGPOST_GLOBAL_URI', BLOGPOST_URI . 'functions/global');

define('BLOGPOST_THEMENAME', 'BlogPost');
define('BLOGPOST_SHORTNAME', 'blogpost');
define('BLOGPOST_HOME_TITLE', 'Front page');
define('BLOGPOST_DEVELOPER_NAME_DISPLAY', 'Cohhe themes');
define('BLOGPOST_DEVELOPER_URL', 'http://cohhe.com');
define('BLOGPOST_DEMO_COMMENTS', false);

define('BLOGPOST_TESTENVIRONMENT', FALSE);

add_action('after_setup_theme', 'blogpost_setup');

// Set max content width
if (!isset($content_width)) {
	$content_width = 900;
}

if (!function_exists('blogpost_setup')) {

	function blogpost_setup() {

		// Load Admin elements
		require_once(BLOGPOST_ADMIN . '/menu-custom-field.php');
		require_once(BLOGPOST_METABOXES . '/layouts.php');

		// TGM plugins activation
		require_once(BLOGPOST_FUNCTIONS . '/tgm-activation/class-tgm-plugin-activation.php');
		require_once(get_template_directory() . '/inc/admin/welcome-screen/welcome-screen.php');

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// Add default posts and comments RSS feed links to <head>.
		add_theme_support('automatic-feed-links');

		add_theme_support( "title-tag" );

		add_theme_support( 'post-formats', array(
			'video', 'audio', 'gallery', 'status', 'quote'
		) );

	}
}

function blogpost_localization() {
	$lang = get_template_directory() . '/languages';
	load_theme_textdomain('blogpost-lite', $lang);
}
add_action('after_setup_theme', 'blogpost_localization');

function blogpost_register_widgets () {
	register_sidebar( array(
		'name'          => __( 'Normal', 'blogpost-lite' ),
		'id'            => 'sidebar-5',
		'class'         => 'normal',
		'before_widget' => '<div class="widget">',
		'after_widget'  => '<div class="clearfix"></div></div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>'
	) );
}
add_action( 'widgets_init', 'blogpost_register_widgets' );

// Add quote post format support
add_theme_support( 'post-formats', array( 'quote' ) );

if (function_exists('add_theme_support')) {
	add_theme_support('post-thumbnails');

	// Default Post Thumbnail dimensions
	set_post_thumbnail_size(150, 150);
}

function the_excerpt_max_charlength($charlength) {
	$excerpt = get_the_excerpt();
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo wp_kses( 
				$subex, 
				array(
					'a' => array(
						'href' => array(),
						'class' => array()
					)
				)
			);
		}
		echo '...';
	} else {
		echo wp_kses( 
			$excerpt, 
			array(
				'a' => array(
					'href' => array(),
					'class' => array()
				)
			)
		);
	}
}

function blogpost_comment_count( $count ) {
	if ( ! is_admin() ) {
		global $id;

		$comments = get_comments('status=approve&post_id=' . $id);
		$separate_comments = separate_comments($comments);

		$comments_by_type = &$separate_comments;
		return count($comments_by_type['comment']);
	} else {
		return $count;
	}
}
add_filter('get_comments_number', 'blogpost_comment_count', 0);

function blogpost_tgm_cpt_search( $query ) {
	if ( $query->is_search ) {
		if ( !is_admin() ) {
			$query->set( 'post_type', array( 'post' ) );
		}
	}
		
	return $query;
}
add_filter( 'pre_get_posts', 'blogpost_tgm_cpt_search' );

// Add new image sizes
if ( function_exists('add_image_size')) {
	# Gallery image Cropped sizes
	add_image_size('blogpost-gallery-large', 270, 270, true); // gallery-large gallery size
	add_image_size('blogpost-gallery-medium', 125, 125, true); // gallery-medium gallery size
	add_image_size('blogpost-post-gallery-medium', 400, 290, false); // gallery-medium gallery size
	add_image_size('blogpost-post-gallery-medium-cropped', 400, 290, true); // gallery-medium gallery size
	add_image_size('blogpost-post-wide', 1800, 490, true); // gallery-medium gallery size
}

// Public JS scripts
if (!function_exists('blogpost_scripts_method')) {
	function blogpost_scripts_method() {
		wp_enqueue_script('jquery');

		wp_enqueue_script('jquery.debouncedresize', get_template_directory_uri() . '/js/jquery.debouncedresize.js', array('jquery'), '', TRUE);
		wp_enqueue_script('blogpost-master', get_template_directory_uri() . '/js/master.js', array('jquery', 'jquery.debouncedresize'), '', TRUE);
		wp_enqueue_script('blogpost-isotope', get_template_directory_uri() . '/js/jquery.isotope.min.js', array('jquery', 'blogpost-master'), '', TRUE);

		wp_enqueue_script('jquery.date', get_template_directory_uri() . '/js/date.js', array('jquery'), '', TRUE);
		
		wp_enqueue_script('jquery.pushy', get_template_directory_uri() . '/js/nav/pushy.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.mousewheel', get_template_directory_uri() . '/js/jquery.mousewheel.min.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.jcarousel', get_template_directory_uri() . '/js/jquery.jcarousel.pack.js', array('jquery'), '', TRUE);

		wp_enqueue_script( 'jquery-ui-datepicker' );

		wp_enqueue_script( 'jquery-ui-dialog' );

		if ( !is_admin() && blogpost_has_shortcode(get_the_ID(), 'vh_contact_us') && get_theme_mod('blogpost_gmap_key', '') ) {
			wp_enqueue_script('blogpost-googlemap-script', '//maps.googleapis.com/maps/api/js?sensor=false&key='.get_theme_mod('blogpost_gmap_key', ''), array(), '3', FALSE);
		}

		wp_enqueue_script('jquery.cookie', get_template_directory_uri() . '/js/jquery.cookie.js', array('jquery'), '', TRUE);

		wp_enqueue_script('jquery.modernizr', get_template_directory_uri() . '/js/modernizr.custom.js', array('jquery'), '', TRUE);

		wp_enqueue_script('jquery.modernizr.images', get_template_directory_uri() . '/js/jquery.waitforimages.min.js', array('jquery'), '', TRUE);

		wp_enqueue_script('jquery.nanoscroller', get_template_directory_uri() . '/js/jquery.nanoscroller.min.js', array('jquery'), '', TRUE);

		wp_enqueue_script("jquery-effects-core");
		wp_enqueue_script("jquery-ui-tabs");
		wp_enqueue_script("jquery-ui-accordion");

		if ( is_singular() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		wp_localize_script( 'blogpost-master', 'blogpost_login_object', array( 
			'ajaxurl'         => admin_url( 'admin-ajax.php' ),
			'redirecturl'     => home_url(),
			'loadingmessage'  => __('Sending user info, please wait...', "blogpost-lite" ),
			'registermessage' => __('A password will be emailed to you for future use', "blogpost-lite" )
		));

		wp_localize_script( 'blogpost-master', 'blogpost_ajax', array(
			'ajaxurl'     => admin_url( 'admin-ajax.php' )
		));

		// Load custom CSS
		wp_add_inline_style( 'blogpost-master-css', wp_strip_all_tags( get_theme_mod('blogpost_custom_css', '') ) );
	}
}
add_action('wp_enqueue_scripts', 'blogpost_scripts_method'); 

function blogpost_has_shortcode( $postid, $shortcode ) {
	if ( !$postid ) {
		return false;
	}
	$post_data = get_post( $postid );
	$post_content = $post_data->post_content;
	return has_shortcode($post_content, $shortcode);
}

// Add Theme Customizer functionality.
require get_template_directory() . '/functions/customizer.php';

// Public CSS files
if (!function_exists('blogpost_style_method')) {
	function blogpost_style_method() {
		wp_enqueue_style('blogpost-master-css', get_template_directory_uri() . '/style.css');
		wp_enqueue_style('vh-normalize', get_template_directory_uri() . '/css/normalize.css');
		wp_enqueue_style('prettyphoto');
		wp_enqueue_style('vh-responsive', get_template_directory_uri() . '/css/responsive.css');
		wp_enqueue_style('pushy', get_template_directory_uri() . '/css/nav/pushy.css');
		wp_enqueue_style('component', get_template_directory_uri() . '/css/component.css');

		// Load google fonts
		if (file_exists(TEMPLATEPATH . '/css/gfonts.css')) {
			wp_enqueue_style('front-gfonts', get_template_directory_uri() . '/css/gfonts.css');
		}
		wp_add_inline_style( 'blogpost-master-css', blogpost_get_typography_style() );

		// Check blog columns
		$blog_width = 100/get_theme_mod('blogpost_blog_columns', '3');
		$blog_css = '.teaser_grid_container ul li { width: '.$blog_width.'% }';
		wp_add_inline_style( 'blogpost-master-css', $blog_css );
	}
}
add_action('wp_enqueue_scripts', 'blogpost_style_method');

function blogpost_get_typography_style() {
	return "html .main-inner p,.ac-device .description,.pricing-table .pricing-content .pricing-desc-1,body .vc_progress_bar .vc_single_bar .vc_label,.page-wrapper .member-desc,.page-wrapper .member-position,.page-wrapper .main-inner ul:not(.ui-tabs-nav) li,.page-wrapper .bg-style-2 p,.header-search-form input[type='text'],.header-input-container .header-input-title{font-family:'Libre Baskerville';font-size:16px;line-height:26px;color:#212121;font-weight:normal}.sidebar-inner,.blogpost-contactform.widget input:not(.btn),.blogpost-recentpostsplus.widget .news-item p,.wrapper .text.widget p,.blogpost-fastflickrwidget.widget,.widget li,.wrapper .search.widget .sb-search-input,.widget .content-form .textarea.input-block-level,.text.widget .textwidget,.newsletter-email,.wrapper .widget li,.widget p{font-family:'Libre Baskerville';font-size:14px;line-height:20px;color:#212121;font-weight:300}.wrapper h1,body .wrapper .page_info .page-title h1{font-family:'Open Sans';font-size:36px;line-height:46px;color:#212121;font-weight:bold}.page-wrapper h2,h2,.content .entry-title,.teaser_grid_container .post-title{font-family:'Open Sans';font-size:28px;line-height:38px;color:#212121;font-weight:bold}.wrapper h3{font-family:'Open Sans';font-size:20px;line-height:30px;color:#212121;font-weight:bold}.wrapper h4{font-family:'Open Sans';font-size:18px;line-height:28px;color:#212121;font-weight:normal}.wrapper h5{font-family:'Open Sans';font-size:13px;line-height:32px;color:#212121;font-weight:bold}.wrapper h6{font-family:'Open Sans';font-size:11px;line-height:30px;color:#212121;font-weight:bold}.wpb_wrapper a,#author-link a,.blogpost-usefullinks.widget a,.widget li a,.widget a{font-family:'Libre Baskerville';font-size:16px;line-height:26px;color:#3599dc;font-weight:normal}@media (min-width:1200px){.wrapper .sidebar-inner .item-title-bg h4,.wrapper .sidebar-inner .widget-title,.wrapper h3.widget-title a{font-family:'Open Sans';font-size:20px;line-height:24px;color:#212121;font-weight:bold}}body .wrapper .page_info .page-title h1{font-family:'Open Sans';font-size:36px;line-height:40px;color:#212121;font-weight:bold}";
}

// Admin CSS
function blogpost_admin_css() {
	wp_enqueue_style( 'blogpost-admin-css', get_template_directory_uri() . '/functions/admin/css/wp-admin.css' );
}
add_action('admin_head','blogpost_admin_css');

// Admin JS
function blogpost_admin_js() {
	wp_enqueue_script('jquery-ui-slider');
}
add_action( 'admin_enqueue_scripts', 'blogpost_admin_js' );

add_action( 'wp_ajax_nopriv_ajax-blog-posts', 'blogpost_load_next_posts' );
add_action( 'wp_ajax_ajax-blog-posts', 'blogpost_load_next_posts' );
function blogpost_load_next_posts() {
	$initial_posts = sanitize_text_field($_POST['initial_posts']);
	$next_posts = sanitize_text_field($_POST['next_posts']);
	$post_categories = sanitize_text_field($_POST['post_categories']);
	$post_paged = sanitize_text_field($_POST['post_paged']);
	$favorite = sanitize_text_field($_POST['favorite']);

	if ( $favorite != '1' ) {
		query_posts(array(
			'post_type' => 'post',
			'posts_per_page' => $next_posts,
			'category_name' => $post_categories,
			'offset' => $post_paged,
			// 'paged' => $post_paged
		));
	} else {
		query_posts(array(
			'post_type' => 'post',
			'posts_per_page' => $next_posts,
			'offset' => $post_paged,
			'post__in' => blogpost_get_user_favorites()
		));
	}

	if ( !have_posts() ) {
		wp_reset_query();
		wp_reset_postdata();
		return;
	}

	global $wp_query, $post;
	$max_pages = $wp_query->max_num_pages;
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
	$max_posts = $wp_query->found_posts;

	ob_start();

	while(have_posts()) {
		the_post();

		$post_format = get_post_format();
		$img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'blogpost-post-gallery-medium');
		$excerpt = get_the_excerpt();

		if ( $post_format != '' ) {
			get_template_part( 'content', get_post_format() );
		} else { ?>
			<li class="isotope-item blog-inner-container standart-format">
				<div  <?php post_class(); ?>>
					<?php if( !empty($img[0]) ) { ?>
						<div class="post-image">
							<a href="<?php the_permalink(); ?>" class="post-image-link"><img src="<?php echo esc_url( $img[0] ); ?>" alt="post-img" class="post-inner-picture"></a>
							<?php if ( get_the_category_list(', ') != '' ) { ?>
								<div class="blog-category <?php echo blogpost_get_random_circle(); ?>">
									<?php echo get_the_category_list(', '); ?>
								</div>
							<?php } ?>
							<?php blogpost_get_favorite_icon(get_the_ID()); ?>
						</div>
					<?php } ?>
					<div class="post-inner entry-content <?php echo get_post_type(); ?>">
						<div class="blog-title">
							<a href="<?php echo get_permalink( $post->ID ); ?>"><?php echo get_the_title(); ?></a>
						</div>
						<div class="blog-excerpt">
						<?php
							$post_content = '';
							if( empty($excerpt) ) {
								_e( 'No excerpt for this posting.', 'blogpost-lite' );
							} else {
								echo wp_kses( 
									$excerpt, 
									array(
										'a' => array(
											'href' => array(),
											'class' => array()
										)
									)
								);
							}
						?>
						</div>
						<div class="blog-post-info">
							<div class="blog-comments icon-comment-1">
								<?php
								$tc = wp_count_comments( $post->ID );
								echo $tc->approved;
								?>
							</div>
							<?php if( empty($img[0]) ) {
								blogpost_get_favorite_icon(get_the_ID());
							} ?>
							<a href="<?php echo get_permalink( $post->ID ); ?>" class="blog-read-more ripple-slow wpb_button wpb_btn-danger wpb_regularsize square"><?php _e('Read', 'blogpost-lite'); ?></a>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</li>
		<?php }
	}

	$post_content = ob_get_contents();
	ob_end_clean();

	wp_reset_query();
	wp_reset_postdata();

	$output = array('new_posts' => $post_content, 'pagination' => $max_posts);

	echo json_encode($output);
	
	die(1);
}

function blogpost_get_favorite_icon( $post_id ) {

	if ( isset($_COOKIE['blogpost_favorite_articles']) ) {
		$user_favorites = json_decode(str_replace('\\', '', $_COOKIE['blogpost_favorite_articles']), true);
	} else {
		$user_favorites = array();
	}

	if ( !array_key_exists($post_id, $user_favorites) && function_exists('blogpost_activate_blogpost_func') ) {
		echo '<a href="javascript:void(0)" class="favorite-article cookies icon-heart-empty" data-id="'.$post_id.'" data-favorite="favorite"></a>';
	} else if ( array_key_exists($post_id, $user_favorites) && function_exists('blogpost_activate_blogpost_func') ) {
		echo '<a href="javascript:void(0)" class="favorite-article cookies icon-heart-filled" data-id="'.$post_id.'" data-favorite="unfavorite"></a>';
	}
}

function blogpost_get_user_favorites() {
	if ( isset($_COOKIE['blogpost_favorite_articles']) ) {
		$user_favorites = json_decode(str_replace('\\', '', $_COOKIE['blogpost_favorite_articles']), true);
	} else {
		$user_favorites = array();
	}
		
	$favorite_articles = array();

	if ( !empty($user_favorites) ) {
		foreach ($user_favorites as $fav_key => $fav_value) {
			$favorite_articles[] = $fav_key;
		}
	}

	return $favorite_articles;
}

function blogpost_get_cookie_classess() {
	$cookie_class = '';
	$layout = get_post_meta(get_the_ID(), 'layouts', true);
	if ( ( !isset( $_COOKIE['blogpost_sidebar_state'] ) && $layout == 'right' ) || ( isset( $_COOKIE['blogpost_sidebar_state'] ) && $_COOKIE['blogpost_sidebar_state'] == '1' && $layout == 'right' ) ) {
		$cookie_class .= 'sidebar-active';
	}

	if ( !isset( $_COOKIE['blogpost_menu_state'] ) || ( isset( $_COOKIE['blogpost_menu_state'] ) && $_COOKIE['blogpost_menu_state'] == '1' ) ) {
		$cookie_class .= ' menu-active';
	}

	echo $cookie_class;
}

function blogpost_get_random_circle() {
	$rand_class = rand(1,3);
	$output = '';

	if ( $rand_class == 1 ) {
		$output .= 'blogpost-red-circle';
	} elseif ( $rand_class == 2 ) {
		$output .= 'blogpost-green-circle';
	} elseif ( $rand_class == 3 ) {
		$output .= 'blogpost-yellow-circle';
	}

	return $output;
}

function blogpost_get_carousel_bullets( $count ) {
	$output = '';

	$output .= '<div class="post-gallery-controls">';
	for ($i=0; $i < $count; $i++) {
		if ( $i == 0 ) {
			$output .= '<span class="carousel-bullet active"></span>';
		} else {
			$output .= '<span class="carousel-bullet"></span>';
		}
	}
	$output .= '</div>';

	return $output;
}

function blogpost_get_blog_url_info() {
	global $wp_query;
	$max_pages = $wp_query->max_num_pages;
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
	$current_url = blogpost_curPageURL();

	$output = '<input type="hidden" id="blog-page-href" value="' . home_url() . '" />
				<input type="hidden" id="blog-page-pagination" value="' . $current . '|' . $max_pages . '" />';

	return $output;
}

/* Filter categories */
function blogpost_filter_categories($list) {

	$find    = '(';
	$replace = '[';
	$list    = str_replace( $find, $replace, $list );
	$find    = ')';
	$replace = ']';
	$list    = str_replace( $find, $replace, $list );

	return $list;
}
add_filter('wp_list_categories', 'blogpost_filter_categories');

// Custom Login Logo
function blogpost_login_logo() {
	$login_logo = get_theme_mod('blogpost_login_logo', false);

	if ($login_logo != false) {
		echo '
	<style type="text/css">
		#login h1 a { background-image: url("' . esc_url( $login_logo ) . '") !important; }
	</style>';
	}
}
add_action('login_head', 'blogpost_login_logo');

// Sets the post excerpt length to 40 words.
function blogpost_excerpt_length($length) {
	return 9;
}
add_filter('excerpt_length', 'blogpost_excerpt_length');

function blogpost_new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'blogpost_new_excerpt_more');

// Returns a "Continue Reading" link for excerpts
function blogpost_continue_reading_link() {
	return ' </p><p><a href="' . esc_url(get_permalink()) . '" class="read_more_link">' . __('Read more', 'blogpost-lite') . '</a>';
}

function blogpost_widget_class($params) {

	// its your widget so you add  your classes
	$classe_to_add = (strtolower(str_replace(array(' '), array(''), $params[0]['widget_name']))); // make sure you leave a space at the end
	$classe_to_add = 'class=" '.$classe_to_add . ' ';
	$params[0]['before_widget'] = str_replace('class="', $classe_to_add, $params[0]['before_widget']);

	return $params;
}
add_filter('dynamic_sidebar_params', 'blogpost_widget_class');

function blogpost_startsWith($haystack,$needle,$case=true) {
	if($case){return (strcmp(substr($haystack, 0, strlen($needle)),$needle)===0);}
	return (strcasecmp(substr($haystack, 0, strlen($needle)),$needle)===0);
}

function blogpost_curPageURL() {
	$pageURL = 'http';
	if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function blogpost_convertToHoursMins($time, $format = '%d:%d') {
	settype($time, 'integer');
	if ($time < 1) {
		return;
	}
	$hours = floor($time / 60);
	$minutes = ($time % 60);
	return sprintf($format, $hours, $minutes);
}

function blogpost_wp_tag_cloud_filter($return, $args) {
	return '<div class="tag_cloud_' . $args['taxonomy'] . '">' . $return . '</div>';
}
add_filter('wp_tag_cloud', 'blogpost_wp_tag_cloud_filter', 10, 2);

// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
function blogpost_page_menu_args($args) {
	$args['show_home'] = true;
	return $args;
}
add_filter('wp_page_menu_args', 'blogpost_page_menu_args');

// Register menus
function blogpost_register_menus () {
	register_nav_menus(
		array (
			'primary-menu' => __('Primary Menu', 'blogpost-lite')
		)
	);
}
add_action('init', 'blogpost_register_menus');

// Adds classes to the array of body classes.
function blogpost_body_classes($classes) {
	global $post, $wp_version;

	if (is_singular() && !is_home()) {
		$classes[] = 'singular';
	}

	if ( !is_front_page() ) {
		$classes[] = 'not_front_page';
	}

	if (is_search()) {
		$search_key = array_search('search', $classes);
		if ($search_key !== false) {
			unset($classes[$search_key]);
		}
	}

	// Color scheme class
	$blogpost_color_scheme = get_theme_mod( 'blogpost_color_scheme');

	if ( !empty($blogpost_color_scheme) ) {
		$classes[] = $blogpost_color_scheme;
	}

	// If blog shortcode
	if (isset($post->post_content) && false !== stripos($post->post_content, '[blog')) {
		$classes[] = 'page-template-blog';
	}

	if ( BLOGPOST_DEMO_COMMENTS ) {
		$classes[] = 'demo_comments';
	}

	// Breadcrumbs class
	$disable_breadcrumb = get_option('blogpost_breadcrumb') ? get_option('blogpost_breadcrumb') : 'false';
	if (!is_home() && !is_front_page() && $disable_breadcrumb == 'false') {
		$classes[] = 'has_breadcrumb';
	}

	$classes[] = 'disable-animations';

	if ( version_compare($wp_version, '4.4', '>=') ) {
		$classes[] = 'wp-post-4-4';
	}

	return $classes;
}
add_filter('body_class', 'blogpost_body_classes');

if (!function_exists('blogpost_posted_on')) {

	// Prints HTML with meta information for the current post.
	function blogpost_posted_on() {
		printf(__('<span>Posted: </span><a href="%1$s" title="%2$s" rel="bookmark">%4$s</a><span class="by-author"> by <a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span>', 'blogpost-lite'),
			esc_url(get_permalink()),
			esc_attr(get_the_time()),
			esc_attr(get_the_date('c')),
			esc_html(get_the_date()),
			esc_url(get_author_posts_url(get_the_author_meta('ID'))),
			sprintf(esc_attr__('View all posts by %s', 'blogpost-lite'), get_the_author()),
			esc_html(get_the_author())
		);
	}
}

function blogpost_clear_nav_menu_item_id($id, $item, $args) {
	return "";
}
add_filter('nav_menu_item_id', 'blogpost_clear_nav_menu_item_id', 10, 3);

function blogpost_add_nofollow_cat( $text ) {
	$text = str_replace('rel="category"', "", $text);
	return $text;
}
add_filter( 'the_category', 'blogpost_add_nofollow_cat' );

function blogpost_addhttp($url) {
	if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
		$url = "http://" . $url;
	}
	return $url;
}

function blogpost_checkShortcode($string) {
	global $post;
	if (isset($post->post_content) && false !== stripos($post->post_content, $string)) {
		return true;
	} else {
		return false;
	}
}

// custom comment fields
function blogpost_custom_comment_fields($fields) {
	global $post, $commenter;

	$fields['author'] = '<div class="comment_auth_email"><div class="comment-form-author">
							<input id="author" name="author" type="text" class="span4" placeholder="' . __( 'Your name', 'blogpost-lite' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" aria-required="true" size="30" />
							<span class="comment-form-error">' . __('Enter your name', 'blogpost-lite') . '</span>
						 </div>';

	$fields['email'] = '<div class="comment-form-email">
							<input id="email" name="email" type="text" class="span4" placeholder="' . __( 'Your email', 'blogpost-lite' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '" aria-required="true" size="30" />
							<span class="comment-form-error">' . __('Enter your email', 'blogpost-lite') . '</span>
						</div></div>';

	$fields['url'] = '<p class="comment-form-url">
						<input id="url" name="url" type="text" class="span4" placeholder="' . __( 'Website', 'blogpost-lite' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />
						</p>';

	$fields = array( $fields['author'], $fields['email'] );
	return $fields;
}
add_filter( 'comment_form_default_fields', 'blogpost_custom_comment_fields' );

if ( ! function_exists( 'blogpost_comment' ) ) {
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own ac_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 */
	function blogpost_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class('geodir-comment'); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:','blogpost-lite' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)','blogpost-lite' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;

		$comment_time = get_comment_time("g/i/s/n/j/Y");
		$comment_time_exploded = explode("/", $comment_time);
		$comment_timestamp = mktime($comment_time_exploded["0"], $comment_time_exploded["1"], $comment_time_exploded["2"], $comment_time_exploded["3"], $comment_time_exploded["4"], $comment_time_exploded["5"]);
		$comment_time_full = human_time_diff($comment_timestamp, current_time('timestamp')) . " " . __("ago", "blogpost-lite");
		$comment_id = get_comment_ID();
		$comment_data = get_comment( $comment_id );

		if ( $comment_data->user_id != '0' ) {
			$author_link = "<a href='" . get_author_posts_url( $comment_data->user_id ) . "' class='comment-author'>" . esc_html( get_userdata( $comment_data->user_id )->display_name ) . "</a>";
		} else {
			$author_link = '<span class="guest-comment">' . esc_html( $comment_data->comment_author ) . '</span>';
		}
	?>
	<li <?php comment_class('geodir-comment'); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 90 );
				?>
				<div class="clearfix"></div>
			</div><!-- .comment-meta -->

			<div class="comment-content comment">
				<div class="comment-top">

					<?php echo $author_link; ?>

					<div class="reply comment-controls">
						<?php edit_comment_link( __( 'Edit','blogpost-lite' ) ); ?>
						<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Add reply','blogpost-lite' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</div><!-- .reply -->

					<?php
					if ( blogpost_c_parent_comment_counter( get_comment_ID() ) != 0 ) {
						echo '<span class="reply-count icon-comment-1">' . blogpost_c_parent_comment_counter( get_comment_ID() ) . ' ' . __("replies", "blogpost-lite") . '</span>';
					}
					?>

					<?php echo '<span class="comment-time icon-clock">' . $comment_time_full . '</span>'; ?>

				</div>

				<?php comment_text(); ?>

				<?php if ( '0' == $comment->comment_approved ) { ?>
					<span class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.','blogpost-lite' ); ?></span>
				<?php } ?>
			</div><!-- .comment-content -->
			<div class="clearfix"></div>
		</div><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}

	function blogpost_comment_sidebar( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class('geodir-comment'); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:','blogpost-lite' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)','blogpost-lite' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;

		$comment_time = get_comment_time("g/i/s/n/j/Y");
		$comment_time_exploded = explode("/", $comment_time);
		$comment_timestamp = mktime($comment_time_exploded["0"], $comment_time_exploded["1"], $comment_time_exploded["2"], $comment_time_exploded["3"], $comment_time_exploded["4"], $comment_time_exploded["5"]);
		$comment_time_full = human_time_diff($comment_timestamp, current_time('timestamp')) . " " . __("ago", "blogpost-lite");
		$comment_id = get_comment_ID();
		$comment_data = get_comment( $comment_id );

		if ( $comment_data->user_id != '0' ) {
			$author_link = "<a href='" . get_author_posts_url( $comment_data->user_id ) . "' class='comment-author'>" . esc_html( get_userdata( $comment_data->user_id )->display_name ) . "</a>";
		} else {
			$author_link = '<span class="guest-comment">' . esc_html( $comment_data->comment_author ) . '</span>';
		}
	?>
	<li <?php comment_class('geodir-comment'); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 90 );
				?>
				<div class="clearfix"></div>
			</div><!-- .comment-meta -->

			<?php echo $author_link; ?>

			<div class="comment-content comment">
	
				<?php comment_text(); ?>

				<div class="comment-bottom">
					<div class="reply comment-controls">
						<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Add reply','blogpost-lite' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</div><!-- .reply -->

					<?php
					if ( blogpost_c_parent_comment_counter( get_comment_ID() ) != 0 ) {
						echo '<span class="reply-count icon-comment-1">' . blogpost_c_parent_comment_counter( get_comment_ID() ) . ' ' . __("replies", "blogpost-lite") . '</span>';
					}
					?>

					<?php echo '<span class="comment-time icon-clock">' . $comment_time_full . '</span>'; ?>
					<div class="clearfix"></div>
				</div>

				<?php if ( '0' == $comment->comment_approved ) : ?>
					<span class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.','blogpost-lite' ); ?></span>
				<?php endif; ?>
			</div><!-- .comment-content -->
			<div class="clearfix"></div>
		</div><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
}

function blogpost_c_parent_comment_counter($id) {
	global $wpdb;
	$query = $wpdb->prepare("SELECT COUNT(comment_post_id) AS count FROM $wpdb->comments WHERE `comment_approved` = 1 AND `comment_parent` = %s", $id);
	$parents = $wpdb->get_row($query);
	return $parents->count;
}

function blogpost_breadcrumbs() {

	$disable_breadcrumb = get_option('blogpost_breadcrumb') ? get_option('blogpost_breadcrumb') : 'false';
	$delimiter          = get_option('blogpost_breadcrumb_delimiter') ? sanitize_text_field( get_option('blogpost_breadcrumb_delimiter') ) : '<span class="delimiter icon-angle-circled-right"></span>';

	$home   = __('Home', 'blogpost-lite'); // text for the 'Home' link
	$before = '<span class="current">'; // tag before the current crumb
	$after  = '</span>'; // tag after the current crumb

	if (!is_home() && !is_front_page() && $disable_breadcrumb == 'false') {
		global $post;
		$homeLink = home_url();

		$output = '<div class="breadcrumb">';
		$output .= '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';

		if (is_category()) {
			global $wp_query;
			$cat_obj   = $wp_query->get_queried_object();
			$thisCat   = $cat_obj->term_id;
			$thisCat   = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0)
				$output .= get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
			$output .= $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
		} elseif (is_day()) {
			$output .= '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			$output .= '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
			$output .= $before . get_the_time('d') . $after;
		} elseif (is_month()) {
			$output .= '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			$output .= $before . get_the_time('F') . $after;
		} elseif (is_year()) {
			$output .= $before . get_the_time('Y') . $after;
		} elseif (is_single() && !is_attachment()) {
			if (get_post_type() != 'post') {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				$output .= '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
				$output .= $before . get_the_title() . $after;
			} else {
				$cat = get_the_category();
				$cat = $cat[0];
				$output .= get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
				$output .= $before . get_the_title() . $after;
			}
		} elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
			$post_type = get_post_type_object(get_post_type());
			if ( isset($post_type) ) {
				$output .= $before . $post_type->labels->singular_name . $after;
			}
		} elseif (is_attachment()) {
			$parent = get_post($post->post_parent);
			$cat    = get_the_category($parent->ID);
			if ( isset($cat[0]) ) {
				$cat = $cat[0];
			}

			//$output .= get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
			$output .= '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
			$output .= $before . get_the_title() . $after;
		} elseif (is_page() && !$post->post_parent) {
			$output .= $before . get_the_title() . $after;
		} elseif (is_page() && $post->post_parent) {
			$parent_id   = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page          = get_page($parent_id);
				$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id     = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb) {
				$output .= $crumb . ' ' . $delimiter . ' ';
			}
			$output .= $before . get_the_title() . $after;
		} elseif (is_search()) {
			$output .= $before . 'Search results for "' . get_search_query() . '"' . $after;
		} elseif (is_tag()) {
			$output .= $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
		} elseif (is_author()) {
			global $blogpost_author;
			$userdata = get_userdata($blogpost_author);
			$output .= $before . 'Articles posted by ' . get_the_author() . $after;
		} elseif (is_404()) {
			$output .= $before . 'Error 404' . $after;
		}

		if (get_query_var('paged')) {
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
				$output .= ' (';
			$output .= __('Page', 'blogpost-lite') . ' ' . get_query_var('paged');
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
				$output .= ')';
		}

		$output .= '</div>';

		return $output;
	}
}

/*
 * This theme supports custom background color and image, and here
 * we also set up the default background color.
 */
add_theme_support( 'custom-background', array(
	'default-color' => 'fcfcfc'
) );

function blogpost_sanitize_color( $input ) {
	return (string) $input;
}

/**
 * Add postMessage support for the Theme Customizer.
 */
function blogpost_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}
add_action( 'customize_register', 'blogpost_customize_register' );

/**
 * Binds CSS and JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function blogpost_customize_preview_js_css() {
	wp_enqueue_script( 'vh-customizer-js', get_template_directory_uri() . '/functions/admin/js/theme-customizer.js', array( 'jquery', 'customize-preview' ), '', true );
}
add_action( 'customize_preview_init', 'blogpost_customize_preview_js_css' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function blogpost_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
			'name'                  => 'Functionality for Blogpost Lite theme', // The plugin name
			'slug'                  => 'functionality-for-blogpost-lite-theme', // The plugin slug (typically the folder name)
			'required'              => false, // If false, the plugin is only 'recommended' instead of required
			'version'               => '1.3.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url'          => '', // If set, overrides default API URL and points to an external URL
		)
	);

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'            => 'blogpost-lite',            // Text domain - likely want to be the same as your theme.
		'default_path'      => '',                          // Default absolute path to pre-packaged plugins
		'parent_slug'       => 'themes.php',                // Default parent slug
		'menu'              => 'install-required-plugins',  // Menu slug
		'has_notices'       => true,                        // Show admin notices or not
		'is_automatic'      => true,                        // Automatically activate plugins after installation or not
		'message'           => '',                          // Message to output right before the plugins table
		'strings'           => array(
			'page_title'                                => __( 'Install Required Plugins', 'blogpost-lite' ),
			'menu_title'                                => __( 'Install Plugins', 'blogpost-lite' ),
			'installing'                                => __( 'Installing Plugin: %s', 'blogpost-lite' ), // %1$s = plugin name
			'oops'                                      => __( 'Something went wrong with the plugin API.', 'blogpost-lite' ),
			'notice_can_install_required'               => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'blogpost-lite' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'            => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'blogpost-lite' ), // %1$s = plugin name(s)
			'notice_cannot_install'                     => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'blogpost-lite' ), // %1$s = plugin name(s)
			'notice_can_activate_required'              => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'blogpost-lite' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'           => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'blogpost-lite' ), // %1$s = plugin name(s)
			'notice_cannot_activate'                    => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'blogpost-lite' ), // %1$s = plugin name(s)
			'notice_ask_to_update'                      => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'blogpost-lite' ), // %1$s = plugin name(s)
			'notice_cannot_update'                      => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'blogpost-lite' ), // %1$s = plugin name(s)
			'install_link'                              => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'blogpost-lite' ),
			'activate_link'                             => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'blogpost-lite' ),
			'return'                                    => __( 'Return to Required Plugins Installer', 'blogpost-lite' ),
			'plugin_activated'                          => __( 'Plugin activated successfully.', 'blogpost-lite' ),
			'complete'                                  => __( 'All plugins installed and activated successfully. %s', 'blogpost-lite' ), // %1$s = dashboard link
			'nag_type'                                  => 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'blogpost_register_required_plugins' );

function blogpost_vcSetAsTheme() {
	vc_set_as_theme( true );
}
add_action( 'vc_before_init', 'blogpost_vcSetAsTheme' );

function blogpost_profile_editor_activation() {
	global $wpdb;
	$skype = $wpdb->get_results('SELECT NAME FROM ' . $wpdb->prefix . 'profile_editor_fields WHERE NAME = "skype"');
	$twitter = $wpdb->get_results('SELECT NAME FROM ' . $wpdb->prefix . 'profile_editor_fields WHERE NAME = "twitter"');
	$yahoo = $wpdb->get_results('SELECT NAME FROM ' . $wpdb->prefix . 'profile_editor_fields WHERE NAME = "yahoo"');
	$aim = $wpdb->get_results('SELECT NAME FROM ' . $wpdb->prefix . 'profile_editor_fields WHERE NAME = "aim"');
	$profile_background = $wpdb->get_results('SELECT NAME FROM ' . $wpdb->prefix . 'profile_editor_fields WHERE NAME = "profile_background"');

	if ( empty($skype) ) {
		$wpdb->query('INSERT INTO ' . $wpdb->prefix . 'profile_editor_fields (NAME,TYPE,LABEL,PLACEHOLDER,RULES,DESCRIPTION) VALUES ("skype","text","Skype","Skype name","{\"field_empty\":\"off\",\"field_syntax\":\"off\",\"field_min\":\"\",\"field_max\":\"\",\"field_registration\":\"on\"}","Your skype name")');
		$wpdb->query('UPDATE ' . $wpdb->prefix . 'usermeta SET meta_key="pe_skype" WHERE meta_key="skype"');
	}

	if ( empty($twitter) ) {
		$wpdb->query('INSERT INTO ' . $wpdb->prefix . 'profile_editor_fields (NAME,TYPE,LABEL,PLACEHOLDER,RULES,DESCRIPTION) VALUES ("twitter","text","Twitter","Twitter name","{\"field_empty\":\"off\",\"field_syntax\":\"off\",\"field_min\":\"\",\"field_max\":\"\",\"field_registration\":\"on\"}","Your twitter name")');
		$wpdb->query('UPDATE ' . $wpdb->prefix . 'usermeta SET meta_key="pe_twitter" WHERE meta_key="twitter"');
	}

	if ( empty($yahoo) ) {
		$wpdb->query('INSERT INTO ' . $wpdb->prefix . 'profile_editor_fields (NAME,TYPE,LABEL,PLACEHOLDER,RULES,DESCRIPTION) VALUES ("yahoo","text","Yahoo","Yahoo name","{\"field_empty\":\"off\",\"field_syntax\":\"off\",\"field_min\":\"\",\"field_max\":\"\",\"field_registration\":\"on\"}","Your yahoo name")');
		$wpdb->query('UPDATE ' . $wpdb->prefix . 'usermeta SET meta_key="pe_yahoo" WHERE meta_key="yahoo"');
	}

	if ( empty($aim) ) {
		$wpdb->query('INSERT INTO ' . $wpdb->prefix . 'profile_editor_fields (NAME,TYPE,LABEL,PLACEHOLDER,RULES,DESCRIPTION) VALUES ("aim","text","Aim","Aim name","{\"field_empty\":\"off\",\"field_syntax\":\"off\",\"field_min\":\"\",\"field_max\":\"\",\"field_registration\":\"on\"}","Your aim name")');
		$wpdb->query('UPDATE ' . $wpdb->prefix . 'usermeta SET meta_key="pe_aim" WHERE meta_key="aim"');
	}

	if ( empty($profile_background) ) {
		$wpdb->query('INSERT INTO ' . $wpdb->prefix . 'profile_editor_fields (NAME,TYPE,LABEL,RULES,DESCRIPTION) VALUES ("profile_background","picture","Profile background","{\"field_empty\":\"off\",\"field_max_size\":\"\",\"field_extensions\":\"all\",\"field_registration\":\"on\"}","Background for author page")');
		
		// Get old values
		$user_backgrounds = $wpdb->get_results('SELECT cmfdata.USER_ID, cmfdata.VALUE FROM ' . $wpdb->prefix . 'cimy_uef_data as cmfdata, ' . $wpdb->prefix . 'cimy_uef_fields as cmffields WHERE cmffields.NAME="PROFILE_BACKGROUND" AND cmffields.ID=cmfdata.FIELD_ID');
		require_once(ABSPATH . 'wp-admin/includes/media.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		foreach ($user_backgrounds as $user_key => $user_value) {
			$image = media_sideload_image($user_value->VALUE, '1', '', 'src');
			$user_backgrounds[$user_key]->VALUE = $image;
		}

		// Set for prodile-editor
		foreach ($user_backgrounds as $pe_key => $pe_value) {
			update_user_meta($pe_value->USER_ID, 'pe_profile_background', blogpost_get_image_id($pe_value->VALUE));
		}
	}
}
register_activation_hook( WP_PLUGIN_DIR.'/profile-editor/profile-editor.php', 'blogpost_profile_editor_activation' );

function blogpost_get_image_id($image_url) {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
	return $attachment[0]; 
}
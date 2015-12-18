<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
	<head>
		<meta content="True" name="HandheldFriendly">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta name="viewport" content="width=device-width">
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>">
		<title><?php wp_title('&laquo;', true, 'right'); ?></title>
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		<?php
			global $vh_class,$wpdb,$map_canvas_arr;
			$logo_size_html = '';

			// Get theme logo
			$logo = get_theme_mod('blogpost_logo', get_template_directory_uri() . '/images/logo.png');

			$website_logo_retina_ready = get_theme_mod('blogpost_logo_retina_ready', false);
			if ( $website_logo_retina_ready != false) {
				$logo_size = getimagesize($logo);
				$logo_size_html = ' style="height: ' . esc_attr( ($logo_size[1] / 2) ) . 'px;" height="' . esc_attr( ($logo_size[1] / 2) ) . '"';
			}
		?>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class($vh_class); ?>>
		<div class="vh_wrapper" id="vh_wrappers">
		<div class="main-body-color"></div>
		<div class="overlay-hide"></div>
		<div class="pushy pushy-left">
			<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary-menu',
						'menu_class'     => 'responsive-menu',
						'depth'          => 2,
						'link_before'    => '',
						'link_after'     => ''
					)
				);
			?>
		</div>
		<div id="vh_loading_effect" class="full"></div>
		<input type="hidden" id="body-classes" <?php body_class($vh_class); ?>>
		<div class="wrapper st-effect-3 w_display_none" id="container">
			<button id="mobile-menu-icon" class="wpb_button  wpb_btn-success wpb_regularsize wpb_menu rounded">
				<span>
					<span class="circle animate" style="height: 70px; width: 70px; top: 8px; left: 14px;"></span>
					<i class="icon"> </i>
				</span>
			</button>
			<div id="mobile-menu-overlay"></div>
			<div class="main nano">
				<?php
				// Scroll to top option
				$scroll_to_top = get_theme_mod('blogpost_scrolltotop', false);
				if ( $scroll_to_top != false ) { ?>
					<a href="javascript:void(0)" class="scroll-to-top icon-up"></a>
				<?php } ?>
				<header class="header <?php vh_get_cookie_classess(); ?> vc_row-fluid vc_col-sm-12">
					<div class="top-header">
						<a href="javascript:void(0)" class="header-menu-button icon-menu-2"></a>
						<div class="header-button-container">
							<div class="header-search">
								<?php get_search_form(); ?>
								<a href="javascript:void(0)" class="header-search-button icon-search"></a>
								<div class="clearfix"></div>
							</div>
							<?php echo vh_display_menu_social_icons('main') ?>
							<a href="javascript:void(0)" class="header-reading-button icon-book"></a>
						</div>
						<div class="clearfix"></div>
					</div>
				</header><!--end of header-->
				<?php
					if ( get_theme_mod('blogpost_side_menu_img', '') == '' ) {
						$menu_background = '#333';
					} else {
						$menu_background = 'url('.esc_url( get_theme_mod('blogpost_side_menu_img', '') ).') no-repeat';
					}
				?>
				<div class="side-menu-container <?php vh_get_cookie_classess(); ?>" style="background: <?php echo $menu_background; ?>">
					<a href="javascript:void(0)" class="header-menu-button active icon-cancel"></a>
					<div class="logo vc_col-sm-8">
						<a href="<?php echo home_url(); ?>"><img src="<?php echo esc_url( $logo ); ?>"<?php echo $logo_size_html ; ?> class="web-logo" alt="<?php bloginfo('name'); ?>" /></a>
					</div>
					<div class="clearfix"></div>
					<div class="side-menu-inner-container">
						<?php
							wp_nav_menu(
								array(
									'theme_location'  => 'primary-menu',
									'menu_class'      => 'header-menu',
									'container'       => 'div',
									'container_class' => 'menu-style',
									'depth'           => 2,
									'link_before'     => '',
									'link_after'      => ''
								)
							);
						?>
					</div>
					<div class="menu-search">
						<?php get_search_form(); ?>
						<a href="javascript:void(0)" class="header-search-button icon-search"></a>
						<div class="clearfix"></div>
					</div>
					<div class="header-button-container menu">
						<?php echo vh_display_menu_social_icons('menu') ?>
						<a href="javascript:void(0)" class="header-reading-button icon-book"></a>
					</div>
					<div class="made-with">
						Theme by <a href="https://cohhe.com">Cohhe</a>
					</div>
				</div>
				<div class="clearfix"></div>
				<?php
					wp_reset_postdata();
					$layout_type = get_post_meta(get_the_id(), 'layouts', true);

					if ( is_archive() || is_search() || is_404() || ( get_post_type() == 'tribe_events' && !is_single() ) ) {
						$layout_type = 'full';
					} else if ( is_home() ) {

						// Get the ID of your posts page
						$id = get_option('page_for_posts');

						$layout_type = get_post_meta($id, 'layouts', true) ? get_post_meta($id, 'layouts', true) : 'full';
					} elseif (empty($layout_type)) {
						$layout_type = get_theme_mod('blogpost_layout', 'full');
					}

					switch ($layout_type) {
						case 'right':
							define('LAYOUT', 'sidebar-right');
							break;
						case 'full':
							define('LAYOUT', 'sidebar-no');
							break;
						case 'left':
							define('LAYOUT', 'sidebar-left');
							break;
					}
<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme.
*/
get_header();
if ( have_posts() ) {
	global $blogpost_blog_image_layout, $blogpost_from_home_page;

	$blogpost_blog_image_layout = $blogpost_from_home_page = TRUE;
	?>
	
	<div class="page-<?php echo BLOGPOST_LAYOUT; ?> page-wrapper nano-content <?php blogpost_get_cookie_classess(); ?>">
		<div id="page-inner-container">
			<div class="clearfix"></div>
			<div class="content vc_row wpb_row vc_row-fluid">
				<div class="<?php echo BLOGPOST_LAYOUT; ?>-pull">
					<div class="main-content <?php echo (BLOGPOST_LAYOUT != 'sidebar-no') ? 'vc_col-sm-9' : 'vc_col-sm-12'; ?>">
						<div class="main-inner">
							<?php 
								get_template_part( 'loop', get_post_format() );
							 ?>								
						</div>
					</div>
				</div>
				<?php
				if (BLOGPOST_LAYOUT == 'sidebar-right') {
				?>
				<div class="vc_col-sm-3 pull-right <?php echo BLOGPOST_LAYOUT; ?>">
					<div class="sidebar-inner">
					<?php
						global $blogpost_is_in_sidebar;
						$blogpost_is_in_sidebar = true;
						if ( function_exists('generated_dynamic_sidebar') ) { generated_dynamic_sidebar(); }
					?>
					<div class="clearfix"></div>
					</div>
				</div><!--end of span3-->
				<?php } ?>
				<?php $blogpost_is_in_sidebar = false; ?>
				<div class="clearfix"></div>
			</div><!--end of content-->
			<div class="clearfix"></div>
		</div>
	</div><!--end of shadow1-->
	<?php
} else {
	?>
	
	<div class="page-<?php echo BLOGPOST_LAYOUT; ?> page-wrapper nano-content <?php blogpost_get_cookie_classess(); ?>">
		<div id="page-inner-container">
			<div class="clearfix"></div>
			<div class="page-title">
				<h1><?php _e( 'Nothing Found!', 'blogpost' ); ?></h1>
			</div>
			<div class="content vc_row-fluid">
				<?php wp_reset_postdata(); ?>
				<div class="<?php echo BLOGPOST_LAYOUT; ?>-pull vc_col-sm-12">
					<div class="main-content vc_col-sm-12">
						<div class="main-inner">
							<p><?php _e( 'Sorry, nothing found!', 'blogpost' ); ?></p>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div><!--end of content-->
			<div class="clearfix"></div>
		</div>
	</div><!--end of shadow1-->
	<?php
}
?>
<?php get_footer();
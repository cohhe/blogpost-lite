<?php
/*
* Template Name: Homepage
*/
get_header();

$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large-image' );

?>
<div class="page-<?php echo BLOGPOST_LAYOUT; ?> page-wrapper nano-content <?php blogpost_get_cookie_classess(); ?> <?php if ( !is_front_page() ) { echo 'not_front_page';}?>">
	<?php echo do_shortcode('[rev_slider main]'); ?>
	<div id="page-inner-container">
		<div class="clearfix"></div>
		<?php
		if ( !is_front_page() && !is_home() ) { ?>
			<div class="page_info">
				<div class="page-title">
					<?php echo  the_title( '<h1>', '</h1>' ); ?>
				</div>
				<div class="clearfix"></div>
			</div>
		<?php } ?>
		<div class="content vc_row wpb_row vc_row-fluid">
			<?php
			wp_reset_postdata(); ?>
			<div class="<?php echo BLOGPOST_LAYOUT; ?>-pull <?php echo (BLOGPOST_LAYOUT != 'sidebar-no') ? 'vc_col-sm-9' : 'vc_col-sm-12'; ?>">
				<div class="main-content">
					<?php
					if ( isset($img[0]) ) { ?>
						<div class="entry-image">
							<img src="<?php echo esc_url( $img[0] ); ?>" class="open_entry_image" alt="" />
						</div>
					<?php } ?>
					<div class="main-inner">
						<?php
						if (have_posts ()) {
							while (have_posts()) {
								the_post();
								the_content();
							}
						} else {
							echo '
								<h2>Nothing Found</h2>
								<p>Sorry, it appears there is no content in this section.</p>';
						}
						?>
					</div>
				</div>
			</div>
			<?php
			if (BLOGPOST_LAYOUT == 'sidebar-right') {
			?>
			<div class="vc_col-sm-3 pull-right <?php echo BLOGPOST_LAYOUT; ?>">
				<a href="javascript:void(0)" class="header-sidebar-button sidebar icon-right"></a>
				<div class="clearfix"></div>
				<div class="sidebar-inner">
				<?php
					global $blogpost_is_in_sidebar;
					$blogpost_is_in_sidebar = true;
					generated_dynamic_sidebar();
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
</div><!--end of page-wrapper-->
<?php get_footer();
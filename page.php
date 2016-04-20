<?php
/**
 * Page template file.
 */
get_header();

$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large-image' );

?>
<div class="page-<?php echo BLOGPOST_LAYOUT; ?> page-wrapper nano-content <?php blogpost_get_cookie_classess(); ?> <?php if ( !is_front_page() ) { echo 'not_front_page';}?>">
	<div id="page-inner-container">
		<div class="clearfix"></div>
		<?php if ( !is_front_page() && !is_home() ) { ?>
			<div class="page_info">
				<div class="page-title">
					<?php echo  the_title( '<h1>', '</h1>' ); ?>
				</div>
				<div class="clearfix"></div>
			</div>
		<?php } ?>
		<?php if ( BLOGPOST_LAYOUT == 'sidebar-right' ) { ?>
			<a href="javascript:void(0)" class="header-sidebar-button icon-left" style="display: block;"></a>
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
						if ( is_front_page() ) {
							$featured_post = get_theme_mod('blogpost_featured_post_id', '');
							$post = get_post( $featured_post );
							setup_postdata( $post );
							$img = wp_get_attachment_image_src(get_post_thumbnail_id(), '');
							if ( !empty($img) ) {
								$featured_img = 'style="background: url(' . $img['0'] . ');"';
							} else {
								$featured_img = 'style=""';
							}
							?>
							<div class="featured-post" <?php echo $featured_img; ?>>
								<div class="featured-post-wrapper">
									<div class="featured-title tp-caption white-title"><a href=""><?php the_title(); ?></a></div>
									<div class="featured-excerpt tp-caption white-excerpt"><?php the_excerpt(); ?></div>
									<?php if ( get_the_category_list(', ', '', $featured_post) != '' ) { ?>
										<div class="featured-category blog-categories <?php echo blogpost_get_random_circle(); ?> tp-caption Category-text">
											<?php echo get_the_category_list(', ', '', $featured_post); ?>
										</div>
									<?php } ?>
								</div>
							</div>

						<?php
						wp_reset_postdata();
						} ?>
						<?php
						if (have_posts ()) {
							while (have_posts()) {
								the_post();
								the_content();
							}
							$comment_page = get_page_by_title('Sidebar comments');
							if ( ( get_theme_mod('blogpost_sidebar_comments', false) != true || DEMO_COMMENTS ) && ( !is_null($comment_page) && get_the_ID() != $comment_page->ID ) ) {
								comments_template( '', true );
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
</div><!--end of page-wrapper-->
<?php get_footer();
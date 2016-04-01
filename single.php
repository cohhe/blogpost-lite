<?php
/**
 * Single template file.
 */
get_header();

$layout_type = get_post_meta(get_the_id(), 'layouts', true);

if(empty($layout_type)) {
	$layout_type = get_theme_mod('blogpost_layout', 'full');
}

$img       = wp_get_attachment_image_src( get_post_thumbnail_id(), 'offer-image-large' );
$span_size = 'vc_col-sm-10';
?>
<div class="page-<?php echo BLOGPOST_LAYOUT; ?> page-wrapper nano-content <?php vh_get_cookie_classess(); ?>">
	<div id="page-inner-container">
		<div class="content vc_row wpb_row vc_row-fluid">
			<?php
			wp_reset_postdata(); ?>
			<div class="<?php echo BLOGPOST_LAYOUT; ?>-pull">
				<div class="main-content">
					<div class="main-inner">
						<div class="vc_row wpb_row vc_row-fluid">
							<?php
							if ( have_posts() ) {
								while ( have_posts() ) {
									the_post();
									get_template_part( 'content', 'single' );
									if ( get_post_type( $post ) == 'post' ) { ?>
										<div class="clearfix"></div>
										<nav class="nav-single blog vc_col-sm-12">
											<?php
											$prev_post = get_previous_post();
											$next_post = get_next_post();
											if (!empty( $prev_post )) { ?>
												<div class="nav_button left">
													<a href="<?php the_permalink( $prev_post->ID ); ?>" class="prev-post-text icon-left"><?php _e('Previous', 'vh'); ?></a>
													<div class="clearfix"></div>
													<div class="prev-post-img">
														<?php echo '<a href="' . get_permalink( $prev_post->ID ) . '">'.get_the_post_thumbnail( $prev_post->ID, 'post-gallery-medium' ).'</a>'; ?>
													</div>
													<div class="prev-post-link">
														<a href="<?php echo get_permalink( $prev_post->ID ); ?>" class="prev_blog_post"><?php echo get_the_title( $prev_post->ID ); ?></a>
													</div>
													<div class="prev-post-content">
														<p>
														<?php
														if ( strlen( $prev_post->post_content ) > 80 ) {
															$post_content = substr($prev_post->post_content, 0, 80) . '..';
														} else {
															$post_content = $prev_post->post_content;
														}
														echo strip_tags($post_content);
														?>
														</p>
													</div>
													<div class="prev-post-info">
														<?php if ( get_the_category_list(', ', '', $prev_post->ID) != '' ) { ?>
															<div class="blog-categories <?php echo vh_get_random_circle(); ?>">
																<?php echo get_the_category_list(', ', '', $prev_post->ID); ?>
															</div>
														<?php } ?>
														<div class="blog-comments icon-comment-1">
															<?php
															$tc = wp_count_comments( $prev_post->ID );
															echo $tc->approved;
															?>
														</div>
														<div class="clearfix"></div>
													</div>
												</div>
											<?php }
											if (!empty( $next_post )) { ?>
												<div class="nav_button right">
													<a href="<?php the_permalink( $next_post->ID ); ?>" class="next-post-text icon-right"><?php _e('Next', 'vh'); ?></a>
													<div class="clearfix"></div>
													<div class="next-post-img">
														<?php echo '<a href="' . get_permalink( $next_post->ID ) . '">'.get_the_post_thumbnail( $next_post->ID, 'post-gallery-medium' ).'</a>'; ?>
													</div>
													<div class="next-post-link">
														<a href="<?php echo get_permalink( $next_post->ID ); ?>" class="next_blog_post"><?php echo get_the_title( $next_post->ID ); ?></a>
													</div>
													<div class="next-post-content">
														<p>
														<?php
														if ( strlen( $next_post->post_content ) > 80 ) {
															$post_content = substr($next_post->post_content, 0, 80) . '..';
														} else {
															$post_content = $next_post->post_content;
														}
														echo strip_tags($post_content);
														?>
														</p>
													</div>
													<div class="next-post-info">
														<?php if ( get_the_category_list(', ', '', $next_post->ID) != '' ) { ?>
															<div class="blog-categories <?php echo vh_get_random_circle(); ?>">
																<?php echo get_the_category_list(', ', '', $next_post->ID); ?>
															</div>
														<?php } ?>
														<div class="blog-comments icon-comment-1">
															<?php
															$tc = wp_count_comments( $next_post->ID );
															echo $tc->approved;
															?>
														</div>
														<div class="clearfix"></div>
													</div>
												</div>
											<?php } ?>
											<div class="clearfix"></div>
										</nav><!-- .nav-single -->
										<?php if ( get_theme_mod('blogpost_sidebar_comments', false) != true || DEMO_COMMENTS ) { ?>
											<div class="comments_container vc_col-sm-12">
												<div class="clearfix"></div>
												<?php
												comments_template( '', true ); ?>
											</div>
										<?php } ?>
										<div style="display: none;"><?php the_posts_pagination(); ?></div>
										<?php
									}
								}
							} else {
								echo '<h2>' . __('Nothing Found', 'vh') . '</h2>
									<p>' . __('Sorry, it appears there is no content in this section', 'vh') . '.</p>';
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
					global $vh_is_in_sidebar;
					$vh_is_in_sidebar = true;
					generated_dynamic_sidebar();
				?>
				<div class="clearfix"></div>
				</div>
			</div><!--end of span3-->
			<?php } ?>
			<?php $vh_is_in_sidebar = false; ?>
			<div class="clearfix"></div>
			</div>
		</div><!--end of content-->
	</div>
	<div class="clearfix"></div>
</div><!--end of page-wrapper-->
<?php get_footer();
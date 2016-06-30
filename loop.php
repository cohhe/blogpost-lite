<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage BlogPost
 */

$m = 0;
$n = 1;

global $blogpost_from_search, $blogpost_from_archive, $blogpost_blog_image_layout, $more; ?>
<div class="vc_row wpb_row vc_row-fluid">
	<div class="vc_col-sm-12 wpb_column column_container">
		<div class="wpb_wrapper">
			<div class="wpb_teaser_grid vc_row wpb_row wpb_content_element wpb_grid columns_count_1 grid_layout-title_thumbnail_text title_thumbnail_text_<?php echo (BLOGPOST_LAYOUT != 'sidebar-no') ? 'vc_col-sm-9' : 'vc_col-sm-12'; ?> columns_count_1_title_thumbnail_text wpb_teaser_grid_post">
				<div class="wpb_wrapper">
					<?php echo blogpost_get_blog_url_info(); ?>
					<div class="teaser_grid_container">
						<ul id="main-teaser-grid-container" class="wpb_thumbnails-posts clearfix" style="position: relative; overflow: hidden;">
							<?php

							/* Start the Loop */
							while ( have_posts() ) { the_post();
								$more        = 0;
								$show_sep    = false;
								$img_style   = '';
								$clear       = '';
								$excerpt     = get_the_excerpt();
								$small_image = false;
								$post_date_d = get_the_date( 'd. M' );
								$post_date_m = get_the_date( 'Y' );
								if ( !isset($show_date) ) {
									$show_date = 'true';
								}
								if ( !isset($show_comments) ) {
									$show_comments = false;
								}

								// Determine image size
								if ( $blogpost_blog_image_layout == 'with_full_image' || $blogpost_from_search || $blogpost_from_archive ) {
									$clear = ' style="float: none;"';
									$img_style = ' style="margin-left: 0;"';
									if ( BLOGPOST_LAYOUT != 'sidebar-no' ) {
										$image_span_size = ' vc_col-sm-10';
									} else {
										$image_span_size = ' vc_col-sm-12';
									}
								} else {
									$small_image     = true;
									$image_span_size = 'vc_col-sm-4';
								}
								$img           = wp_get_attachment_image_src( get_post_thumbnail_id(), 'offer-image-large' );
								$entry_utility = '';

								$entry_utility .= '
									<div class="entry-top-utility">';

									if ( 'post' == get_post_type() ) {
										/* translators: used between list items, there is a space after the comma */
										$categories_list = get_the_category_list( __( ', ', 'blogpost-lite' ) );
										if ( $categories_list ) {
											$entry_utility .= '
											<div class="category-link">
											<i class="icon-folder"></i>
											' . sprintf( __( '<span class="%1$s"></span> %2$s', 'blogpost-lite' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
											$show_sep = true;
											$entry_utility .= '
											</div>';
										}

										/* translators: used between list items, there is a space after the comma */
										$tags_list = get_the_tag_list( '', __( ', ', 'blogpost-lite' ) );
										if ( $tags_list ) {
											$style = '';
											if ( $show_sep ) {
												$style = ' style="margin-left: 30px;"';
											}
											$entry_utility .= '
											<div class="tag-link"' . $style . '>
											<i class="icon-tags"></i>
											' . sprintf( __( '<span class="%1$s"></span> %2$s', 'blogpost-lite' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
											$show_sep = true;
											$entry_utility .= '
											</div>';
										}
									}
									if ( $show_sep ) {
										$entry_utility .= '
										<div class="sep">&nbsp;</div>';
									}
									$entry_utility .= '
									<div class="clearfix"></div>
									</div>';
								?>
								<?php
									require("loop-standard.php");
								?>
							<?php $m++; } ?>
						</ul>
					</div>
					<button id="load-more-posts" class="wpb_button wpb_btn-danger wpb_regularsize square"><span><?php _e('Load more', 'blogpost-lite'); ?></span></button>
					<div class="loading-effect"></div>
					<div class="no-more-posts"><?php _e('No more posts', 'blogpost-lite'); ?></div>
				</div>
			</div>
		</div>
	</div><!--end of wpb_column-->
</div><!--end of vc_row wpb_row-->
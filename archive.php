<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage BlogPost
 */
get_header();

global $blogpost_from_archive;
$blogpost_from_archive = true;
$author_id = get_query_var( 'author' );
?>
<div class="page-<?php echo BLOGPOST_LAYOUT; ?> page-wrapper nano-content <?php blogpost_get_cookie_classess(); ?>">
	<div id="page-inner-container">
		<div class="clearfix"></div>
		<?php if ( is_author() ) { ?>
			<div class="author-page-top">
				<?php
					$author_info = get_userdata( $author_id );
					if ( get_the_author_meta('pe_profile_background', $author_id) ) {
						$user_img = wp_get_attachment_image_src( get_the_author_meta('pe_profile_background', $author_id), 'full' );
						echo '
						<div class="profile-background">
							<img src="' . esc_url($user_img['0'] ) . '" class="profile-background-picture" alt="Profile background picture" />
						</div>';
					}
				?>
				<div class="author-information">
					<span class="author-name"><?php echo esc_html( $author_info->display_name ); ?></span>
					<span class="author-description">
						<p>
							<?php
							$author_description = get_the_author_meta( 'description', $author_id );
							if ( strlen($author_description) > 200 ) {
								$author_desc = substr($author_description, 0, 200) . '..';
							} else {
								$author_desc = $author_description;
							}
							echo esc_html( $author_desc );
							?>
						</p>
					</span>
					<div class="author-page-social-icons">
						<?php
						$author_id = get_the_author_meta('ID');
						$skype_link = get_the_author_meta( 'pe_skype', $author_id );
						$twitter_link = get_the_author_meta( 'pe_twitter', $author_id );
						$yahoo_link = get_the_author_meta( 'pe_yahoo', $author_id );
						$aim_link = get_the_author_meta( 'pe_aim', $author_id );

						if ( $skype_link != '' ) {
							echo '<a href="skype:'.esc_url( $skype_link ).'?call" class="author-link author-skype icon-skype"></a>';
						}

						if ( $twitter_link != '' ) {
							echo '<a href="'.esc_url( $twitter_link ).'" class="author-link author-twitter icon-twitter"></a>';
						}

						if ( $yahoo_link != '' ) {
							echo '<a href="'.esc_url( $yahoo_link ).'" class="author-link author-yahoo icon-yahoo"></a>';
						}

						if ( $aim_link != '' ) {
							echo '<a href="'.esc_url( $aim_link ).'" class="author-link author-aim icon-aim"></a>';
						}
						?>
					</div>
				</div>
			</div>
		<?php } elseif ( is_category() ) { ?>
			<?php
			$category_id = get_query_var( 'cat' );
			if ( function_exists('get_tax_meta') ) {
				$category_image = get_tax_meta( $category_id, 'image_field_id' );
			}
			$category_info = get_category( $category_id );
			if ( !empty($category_image) ) { ?>
				<div class="category-page-top">
					<?php
						if ( function_exists('get_tax_meta') ) {
							echo '
							<div class="category-background">
								<img src="' . esc_url( $category_image['url'] ) . '" class="category-background-picture" alt="'.__('Category background picture', 'blogpost-lite').'" />
							</div>';
						}
					?>
					<div class="category-information">
						<span class="category-name"><?php echo esc_html( $category_info->name ); ?></span>
						<span class="category-description">
							<p>
								<?php
								$category_description = $category_info->description;
								if ( strlen($category_description) > 200 ) {
									$category_desc = substr($category_description, 0, 200) . '..';
								} else {
									$category_desc = $category_description;
								}
								echo esc_html( $category_desc );
								?>
							</p>
						</span>
					</div>
				</div>
			<?php } ?>
		<?php } else { ?>
			<div class="page_info">
				<div class="page-title">
					<h1>
					<?php if (is_day()) : ?>
						<?php printf(__('Daily Archives: %s', 'blogpost-lite'), '<span>' . get_the_date() . '</span>'); ?>
					<?php elseif (is_month()) : ?>
						<?php printf(__('Monthly Archives: %s', 'blogpost-lite'), '<span>' . get_the_date('F Y') . '</span>'); ?>
					<?php elseif (is_year()) : ?>
						<?php printf(__('Yearly Archives: %s', 'blogpost-lite'), '<span>' . get_the_date('Y') . '</span>'); ?>
					<?php else : ?>
						<?php _e('Blog Archives', 'blogpost-lite'); ?>
					<?php endif; ?>
					</h1>
				</div>
				<div class="clearfix"></div>
			</div>
		<?php } ?>
		<div class="content vc_row wpb_row vc_row-fluid">
			<?php wp_reset_postdata(); ?>
			<div class="<?php echo BLOGPOST_LAYOUT; ?>-pull">
				<div class="main-content <?php echo (BLOGPOST_LAYOUT != 'sidebar-no') ? 'vc_col-sm-9' : 'vc_col-sm-12'; ?>">
					<div class="main-inner">
						<?php
						if (have_posts()) {
							// Include the Post-Format-specific template for the content.
							get_template_part('loop', get_post_format());
						} else { ?>
							<p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'blogpost-lite'); ?></p>
							<?php get_search_form(); ?>
						<?php } ?>
						<div class="clearer"></div>
					</div>
				</div>
			</div>
			<?php $blogpost_is_in_sidebar = false; ?>
			<div class="clearfix"></div>
		</div><!--end of content-->
		<div class="clearfix"></div>
	</div>
</div><!--end of page-wrapper-->
<?php get_footer();
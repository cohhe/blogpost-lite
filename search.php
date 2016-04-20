<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage BlogPost
 */

get_header();
global $blogpost_from_search, $blogpost_blog_image_layout;
$blogpost_blog_image_layout = 'with_full_image';
?>

<div class="page-<?php echo BLOGPOST_LAYOUT; ?> page-wrapper nano-content <?php blogpost_get_cookie_classess(); ?>">
	<div id="page-inner-container">
		<div class="search-page-top">
			<?php
				$search_background = get_theme_mod('blogpost_search_background', '');
				if ( $search_background != '' ) {
					echo '
					<div class="search-background">
						<img src="' . esc_url( $search_background ) . '" class="search-background-picture" alt="Search background picture" />
					</div>';
				}
			?>
			<div class="search-information">
				<span class="search-name">
					<?php
					_e( 'Search Results for:', 'vh' );
					echo '<br/>';
					echo get_search_query();
					?>
				</span>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="content vc_row wpb_row vc_row-fluid">
			<div class="<?php echo BLOGPOST_LAYOUT; ?>-pull <?php echo (BLOGPOST_LAYOUT != 'sidebar-no') ? 'vc_col-sm-9' : 'vc_col-sm-12'; ?>">
				<div class="main-content <?php echo (BLOGPOST_LAYOUT != 'sidebar-no') ? 'vc_col-sm-9' : 'vc_col-sm-12'; ?>">
					<?php
					if ( isset($img[0]) ) { ?>
						<div class="entry-image">
							<img src="<?php echo esc_url( $img[0] ); ?>" class="open_entry_image" alt="" />
						</div>
					<?php } ?>
					<div class="main-inner">
						<?php
						if ( have_posts() ) {
							$blogpost_from_search = true;

							// Include the Post-Format-specific template for the content.
							get_template_part( 'loop', get_post_format() );

						} else { ?>
							<div class="vc_row wpb_row vc_row-fluid">
								<p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'vh'); ?></p>
								<?php
								$blogpost_is_in_sidebar = 'content';
								get_search_form();
								?>
							</div><!--end of entry-content-->
						<?php } ?>
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
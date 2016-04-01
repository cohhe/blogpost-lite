<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage BlogPost
 */
get_header();

	$title_404 = get_theme_mod( 'blogpost_404_title', "This is somewhat embarrassing, isn't it?");
	$title_msg = get_theme_mod( 'blogpost_404_message', "It seems we can't find what you're looking for. Perhaps searching, or one of the links below, can help.");
?>
<div class="page-<?php echo BLOGPOST_LAYOUT; ?> page-wrapper nano-content <?php vh_get_cookie_classess(); ?> search-no-results">
	<div id="page-inner-container">
		<div class="clearfix"></div>
		<div class="page_info">
			<div class="page-title">
				<h1><?php echo esc_html($title_404); ?></h1>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="content vc_row wpb_row vc_row-fluid">
			<?php wp_reset_postdata(); ?>
			<div class="<?php echo BLOGPOST_LAYOUT; ?>-pull">
				<div class="main-content <?php echo (BLOGPOST_LAYOUT != 'sidebar-no') ? 'vc_col-sm-9' : 'vc_col-sm-12'; ?>">
					<div class="main-inner">
						<div class="vc_row wpb_row vc_row-fluid">
							<div class="error404-text vc_col-sm-12">
								<p><?php echo esc_html($title_msg); ?></p>
								<?php require("searchform.php"); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php $vh_is_in_sidebar = false; ?>
			<div class="clearfix"></div>
		</div><!--end of content-->
		<div class="clearfix"></div>
	</div>
</div><!--end of page-wrapper-->
<?php get_footer();
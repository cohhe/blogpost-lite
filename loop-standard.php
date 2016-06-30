<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage BlogPost
 */

global $blogpost_from_home_page, $post;

$tc = 0;
$excerpt = get_the_excerpt();

$img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'blogpost-post-gallery-medium');

?>

<?php if ( get_post_format() != '' ) {
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
<?php } ?>
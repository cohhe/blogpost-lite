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

if ( empty($img[0]) ) {
	$img[0] = get_template_directory_uri() . '/images/default-image.jpg';
}
?>

<li class="isotope-item blog-inner-container <?php echo get_post_format(); ?>-format">
	<div  <?php post_class(); ?>>
		<div class="post-image">
			<?php
			$i = 1;
			$posts_slideshow   = ( get_option('blogpost_posts_slideshow_number') ) ? get_option('blogpost_posts_slideshow_number') : 5;
			$attachments_count = 0;
			$images_arr = array();

			if ( function_exists('kd_mfi_get_featured_image_id') ) {
				while( $i <= $posts_slideshow ) {
					$attachment_id = kd_mfi_get_featured_image_id( 'gallery-image-' . $i, 'post' );
					if( $attachment_id ) {
						$attachments_count++;
						$images_arr[] = 'gallery-image-'.$i;
					}
					$i++;
				}
			}

			if ( $attachments_count > 1 ) { ?>
				<div class="post-image-carousel">
					<div class="post-carousel-container">
						<?php
						foreach ($images_arr as $image_value) {
							$attachment_id = kd_mfi_get_featured_image_id( $image_value, 'post' );
							$attachment_image = wp_get_attachment_image_src( $attachment_id, 'blogpost-post-gallery-medium-cropped' );
							?> <img src="<?php echo esc_url( $attachment_image[0] ); ?>" class="post-carousel-image" alt="Carousel image" /> <?php
						}
						?>
					</div>
				</div>
				<a href="<?php the_permalink(); ?>" class="post-image-link gallery-link"></a>
				<?php blogpost_get_favorite_icon(get_the_ID()); ?>
			<?php } ?>
			<img src="<?php echo esc_url( $img[0] ); ?>" class="post-inner-picture" alt="post-img" />
			<?php echo blogpost_get_carousel_bullets( $attachments_count ); ?>
			<?php if ( get_the_category_list(', ') != '' ) { ?>
				<div class="blog-category <?php echo blogpost_get_random_circle(); ?>">
					<?php echo get_the_category_list(', '); ?>
				</div>
			<?php } ?>

		</div>
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
				<?php if ( $attachments_count < 1 ) {
					blogpost_get_favorite_icon(get_the_ID());
				} ?>
				<a href="<?php echo get_permalink( $post->ID ); ?>" class="blog-read-more ripple-slow wpb_button wpb_btn-danger wpb_regularsize square"><?php _e('Read', 'blogpost-lite'); ?></a>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</li>
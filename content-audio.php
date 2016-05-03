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

$img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'post-gallery-medium');

if ( empty($img[0]) ) {
	$img[0] = get_template_directory_uri() . '/images/default-image.jpg';
}
?>

<li class="isotope-item blog-inner-container <?php echo get_post_format(); ?>-format">
	<div  <?php post_class(); ?>>
		<div class="post-image">
		<?php if ( function_exists('get_embed_code') ) { get_embed_code( $post->ID ); } ?>
		</div>
		<div class="post-inner entry-content <?php echo get_post_type(); ?>">
			<div class="blog-title">
				<a href="<?php echo get_permalink( $post->ID ); ?>"><?php echo get_the_title(); ?></a>
			</div>
			<div class="blog-excerpt">
			<?php
				$post_content = '';
				if( empty($excerpt) ) {
					_e( 'No excerpt for this posting.', 'blogpost' );
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
				<a href="<?php echo get_permalink( $post->ID ); ?>" class="blog-read-more ripple-slow wpb_button wpb_btn-danger wpb_regularsize square"><?php _e('Read', 'blogpost'); ?></a>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</li>
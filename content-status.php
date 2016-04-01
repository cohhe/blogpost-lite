<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage BlogPost
 */

global $vh_from_home_page, $post;

$tc = 0;
$excerpt = get_the_excerpt();

$img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'post-gallery-medium');

if ( empty($img[0]) ) {
	$img[0] = get_template_directory_uri() . '/images/default-image.jpg';
}
?>

<li class="isotope-item blog-inner-container <?php echo get_post_format(); ?>-format">
	<div  <?php post_class(); ?>>
		<div class="post-twitter-username icon-twitter-1">
		<?php if ( function_exists('get_twitter_link') ) { get_twitter_link( $post->ID ); } ?>
		</div>
		<div class="post-inner entry-content <?php echo get_post_type(); ?>">
			<div class="blog-excerpt">
			<?php
				$post_content = '';
				if( empty($excerpt) ) {
					_e( 'No excerpt for this posting.', 'vh' );
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
			<?php if ( function_exists('get_tweet_buttons') ) { get_tweet_buttons($post->ID); } ?>
		</div>
	</div>
</li>
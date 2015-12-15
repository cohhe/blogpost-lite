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
if ( get_post_meta( $post->ID, 'post_ad_button_text', true ) != '' ) {
	$ad_button = get_post_meta( $post->ID, 'post_ad_button_text', true );
} else {
	$ad_button = __('Open ad', 'vh');
}

if ( get_post_meta( $post->ID, 'post_ad_button_url', true ) != '' ) {
	$ad_button_url = get_post_meta( $post->ID, 'post_ad_button_url', true );
} else {
	$ad_button_url = get_permalink( $post->ID );
}

if ( get_post_meta( $post->ID, 'post_ad_background', true ) != '' ) {
	$ad_background_id = get_post_meta( $post->ID, 'post_ad_background', true );
	$ad_background = wp_get_attachment_image_src( $ad_background_id, 'post-gallery-medium' );
} else {
	$ad_background_id = '';
}
?>

<li class="isotope-item blog-inner-container <?php echo get_post_format(); ?>-format">
	<div  <?php post_class(); ?> style="background: url('<?php echo esc_url( $ad_background['0'] ); ?>') no-repeat;">
		<div class="post-inner entry-content <?php echo get_post_type(); ?>">
			<div class="ad-title"><?php the_title(); ?></div>
			<div class="blog-excerpt">
			<?php
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
			<div class="entry-ad-button">
				<a href="<?php echo esc_url( $ad_button_url ); ?>" class="post-ad-button ripple-slow wpb_button wpb_btn-danger wpb_regularsize square"><?php echo esc_html( $ad_button ); ?></a>
			</div>
			<a href="javascript:void(0)" class="ad-close icon-cancel"></a>
		</div>
	</div>
</li>
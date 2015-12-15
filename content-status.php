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
		<?php
		if ( get_post_meta( $post->ID, 'post_twitter_username', true ) != '' ) {
			$twitter_user = esc_html( get_post_meta( $post->ID, 'post_twitter_username', true ) );
			$user_link = 'https://twitter.com/'.str_replace('@', '', $twitter_user);
			echo '<a href="' . esc_url( $user_link ) . '" class="twitter-link">' . $twitter_user . '</a>';
		}
		?>
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
			<?php if ( get_post_meta( $post->ID, 'post_twitter_link', true ) != '' ) {
				$twitter_link = esc_url( get_post_meta( $post->ID, 'post_twitter_link', true ) );
				$twitter_link_arr = explode('/', $twitter_link); ?>
				<div class="twitter-follow-button">
					<a href="https://twitter.com/intent/user?screen_name=<?php echo esc_attr( $twitter_link_arr['3'] ); ?>" target="_blank" class="twitter-follow"><?php _e('Follow', 'vh'); ?></a>
				</div>
				<div class="twitter-buttons">
					<a href="https://twitter.com/intent/tweet?in_reply_to=<?php echo esc_attr( $twitter_link_arr['5'] ); ?>" target="_blank" class="twitter-button icon-reply"><?php _e('reply', 'vh'); ?></a>
					<a href="https://twitter.com/intent/retweet?tweet_id=<?php echo esc_attr( $twitter_link_arr['5'] ); ?>" target="_blank" class="twitter-button icon-retweet"><?php _e('retweet', 'vh'); ?></a>
					<a href="https://twitter.com/intent/favorite?tweet_id=<?php echo esc_attr( $twitter_link_arr['5'] ); ?>" target="_blank" class="twitter-button icon-star"><?php _e('favorite', 'vh'); ?></a>
				</div>
			<?php } ?>
		</div>
	</div>
</li>
<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package WordPress
 * @subpackage BlogPost
 */

global $vh_blog_image_layout;

$show_sep       = FALSE;
$style          = '';
$clear          = '';
$excerpt        = get_the_excerpt();
$top_left       = "";
$small_image    = FALSE;
$post_date_d    = get_the_date( 'd. M' );
$post_date_m    = get_the_date( 'Y' );
$is_author_desc = '';
$post_id = $post->ID;
$tc = wp_count_comments( $post_id );

$show_date = isset( $show_date ) ? $show_date : NULL;

if ( get_the_author_meta( 'description' ) ) { 
	$is_author_desc = ' is_author_desc';
}

// Determine blog image size
if ( BLOGPOST_LAYOUT == 'sidebar-no' ) {
	$clear     = ' style="float: none;"';
	$img_style = ' style="margin-left: 0;"';
} else {
	$small_image = TRUE;
	$img_style   = ' style="margin-left: 0;"';
}
$img           = wp_get_attachment_image_src( get_post_thumbnail_id(), 'post-wide' );
$entry_utility = '';

$entry_utility .= '<div class="page_title">' . get_the_title() . '</div>';
$entry_utility_bottom = '<div class="entry-bottom-utility">';
if ( 'post' == get_post_type() ) {

	/* translators: used between list items, there is a space after the comma */
	$categories_list = get_the_category_list( __( ', ', 'vh' ) );
	if ( $categories_list ) {
		$entry_utility_bottom .= '
		<div class="category-link">
		' . sprintf( __( '<span class="%1$s"></span> %2$s', 'vh' ), 'entry-utility-prep entry-utility-prep-cat-links icon-folder-open', $categories_list );
		$show_sep = TRUE;
		$entry_utility_bottom .= '
		</div>';
	}
}
$entry_utility_bottom .= '</div>';
?>
<div class="entry no_left_margin first-entry <?php echo esc_attr( $is_author_desc ); ?> <?php if ( !isset($img[0]) ) { echo ' no-image'; } ?><?php echo (BLOGPOST_LAYOUT != 'sidebar-no') ? ' vc_col-sm-12' : ' vc_col-sm-12'; ?>">
	<div class="entry-image vh_animate_when_almost_visible with_full_image <?php echo esc_attr( $vh_blog_image_layout ) . esc_attr( $is_author_desc ); ?>"<?php echo $clear; ?>>
		<?php
		$i                 = 2;
		$posts_slideshow   = ( get_option('vh_posts_slideshow_number') ) ? get_option('vh_posts_slideshow_number') : 5;
		$attachments_count = 1;
		?>
		<div class="main_image_wrapper <?php echo get_post_format(); ?>">
			<?php
			$i = 1;
			$posts_slideshow   = ( get_option('vh_posts_slideshow_number') ) ? get_option('vh_posts_slideshow_number') : 5;
			$attachments_count = 0;
			$images_arr = array();

			while( $i <= $posts_slideshow ) {
				$attachment_id = kd_mfi_get_featured_image_id( 'gallery-image-' . $i, 'post' );

				if( $attachment_id ) {
					$attachments_count++;
					$images_arr[] = 'gallery-image-'.$i;
				}
				$i++;
			}

			if ( get_post_format() == 'video' || get_post_format() == 'audio' ) {
				get_embed_code( $post->ID );
			} elseif ( get_post_format() == 'status' ) { ?>
				<div class="post-twitter-username icon-twitter-1">
				<?php if ( function_exists('get_twitter_link') ) { get_twitter_link( $post_id ); } ?>
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
					<?php if ( function_exists('get_tweet_buttons') ) { get_tweet_buttons( $post_id, false ); } ?>
				</div>
				<?php
			} elseif ( $attachments_count > 1 ) { ?>
				<div class="open-post-image-carousel">
					<div class="open-post-carousel-container">
						<?php
						if ( isset($img[0]) ) { ?>
							<img src="<?php echo esc_url( $img[0] ); ?>" class="open-post-carousel-image" alt="Carousel image" /> <?php
						}
						foreach ($images_arr as $image_value) {
							$attachment_id = kd_mfi_get_featured_image_id( $image_value, 'post' );
							$attachment_image = wp_get_attachment_image_src( $attachment_id, 'post-wide' );
							?> <img src="<?php echo esc_url( $attachment_image[0] ); ?>" class="open-post-carousel-image" alt="Carousel image" /> <?php
						}
						?>
					</div>
				</div>
				<?php 
				if ( isset($img[0]) ) {
					$attachments_count++;
				}
				echo vh_get_carousel_bullets( $attachments_count );
				?>
				<div class="open-post-carousel-text">
					<span class="carousel-text-title"><?php the_title(); ?></span>
					<div class="category-text-excerpt">
						<p>
							<?php
							echo wp_kses( 
								$excerpt, 
								array(
									'a' => array(
										'href' => array(),
										'class' => array()
									)
								)
							);
							?>
						</p>
					</div>
					<div class="open-post-meta">
						<span class="carousel-text-category <?php echo vh_get_random_circle(); ?>">
							<?php
							echo wp_kses( 
								$categories_list, 
								array(
									'a' => array(
										'href' => array(),
										'class' => array(),
										'rel' => array()
									)
								)
							);
							?>
						</span>
						<span class="category-text-comments icon-comment-1"><?php echo $tc->total_comments; ?></span>
						<div class="clearfix"></div>
					</div>
				</div>
			<?php } elseif ( isset($img[0]) ) { ?>
				<div class="image_wrapper">
					<img src="<?php echo esc_url( $img[0] ); ?> "<?php echo $img_style; ?> class="open_entry_image" alt="" />
				</div>
				<div class="open-post-carousel-text">
					<span class="carousel-text-title"><?php the_title(); ?></span>
					<div class="category-text-excerpt">
						<p>
							<?php
							echo wp_kses( 
								$excerpt, 
								array(
									'a' => array(
										'href' => array(),
										'class' => array()
									)
								)
							);
							?>
						</p>
					</div>
					<div class="open-post-meta">
						<span class="carousel-text-category <?php echo vh_get_random_circle(); ?>">
							<?php
							echo wp_kses( 
								$categories_list, 
								array(
									'a' => array(
										'href' => array(),
										'class' => array(),
										'rel' => array()
									)
								)
							);
							?>
						</span>
						<span class="category-text-comments icon-comment-1"><?php echo $tc->total_comments; ?></span>
						<div class="clearfix"></div>
					</div>
				</div>
			<?php } else { ?>
				<span class="single-post-open-title"><?php echo get_the_title(); ?></span>
			<?php } ?>
			<?php if (BLOGPOST_LAYOUT == 'sidebar-right') { ?>
				<a href="javascript:void(0)" class="header-sidebar-button icon-left"></a>
			<?php } ?>
			<a href="javascript:void(0)" class="article-back icon-angle-double-left"></a>
		</div>
		<div class="entry-content">
			<?php
			if ( is_search() ) {
				the_excerpt();
				if( empty($excerpt) )
					echo 'No excerpt for this posting.';

			} elseif ( get_post_format() != 'status' ) {
				echo '<div class="blog-open-content">';
					the_content(__('Read more', 'vh'));
					wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'vh' ) . '</span>', 'after' => '</div>', 'link_before' => '<span class="page-link-wrap">', 'link_after' => '</span>', ) );
				echo '<div class="clearfix"></div></div>';
			}
			?>
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
	<?php
	// If a user has filled out their description, show a bio on their entries
	if ( get_post_type( $post ) == 'post' && get_the_author_meta( 'description' ) ) { ?>
	<div id="author-info">
		<span class="author-text"><?php _e('Author', 'vh'); ?></span>
		<div class="author-infobox">
			<div id="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'vh_author_bio_avatar_size', 300 ) ); ?>
			</div>
			<div id="author-description">
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" class="author-link"><?php echo get_the_author(); ?></a>
				<p><?php the_author_meta( 'description' ); ?></p>
				<div class="author-social-icons">
				<?php
					$author_id = get_the_author_meta('ID');
					$skype_link = get_the_author_meta( 'skype', $author_id );
					$twitter_link = get_the_author_meta( 'twitter', $author_id );
					$yahoo_link = get_the_author_meta( 'yahoo', $author_id );
					$aim_link = get_the_author_meta( 'aim', $author_id );

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
			</div><!-- end of author-description -->
		</div>
		<div class="clearfix"></div>
	</div><!-- end of entry-author-info -->
	<?php } ?>
</div>
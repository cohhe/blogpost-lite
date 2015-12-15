<?php
/*
Title: Hotel name
Address: Calle Alemanes 27, 41004, New York
Phone: +34 954 560 000
Email: email@email.com
Text: Here you can add some kind of info, but don't add to muxh text here
*/

global $post;

$vh_map_title   = (get_post_meta( $post->ID, 'vh_map_title', true )) ? get_post_meta( $post->ID, 'vh_map_title', true ) : '';
$vh_map_address = (get_post_meta( $post->ID, 'vh_map_address', true )) ? get_post_meta( $post->ID, 'vh_map_address', true ) : '';
$vh_map_phone   = (get_post_meta( $post->ID, 'vh_map_phone', true )) ? get_post_meta( $post->ID, 'vh_map_phone', true ) : '';
$vh_map_email   = (get_post_meta( $post->ID, 'vh_map_email', true )) ? get_post_meta( $post->ID, 'vh_map_email', true ) : '';
$vh_map_text    = (get_post_meta( $post->ID, 'vh_map_text', true )) ? get_post_meta( $post->ID, 'vh_map_text', true ) : '';
$vh_map_lat     = (get_post_meta( $post->ID, 'vh_map_lat', true )) ? get_post_meta( $post->ID, 'vh_map_lat', true ) : '';
$vh_map_long    = (get_post_meta( $post->ID, 'vh_map_long', true )) ? get_post_meta( $post->ID, 'vh_map_long', true ) : '';

?>

<div class="row-container">
	<div class="content google_map_admin">
		<label><?php _e('Address:', 'vh'); ?></label>
		<input type="text" name="vh_map_address" value="<?php echo esc_attr($vh_map_address); ?>" size="55" /><br />

		<label><?php _e('Phone:', 'vh'); ?></label>
		<input type="text" name="vh_map_phone" value="<?php echo esc_attr($vh_map_phone); ?>" size="55" /><br />

		<label><?php _e('Email:', 'vh'); ?></label>
		<input type="text" name="vh_map_email" value="<?php echo esc_attr($vh_map_email); ?>" size="55" /><br />

		<label><?php _e('Latitude:', 'vh'); ?></label>
		<input type="text" name="vh_map_lat" value="<?php echo esc_attr($vh_map_lat); ?>" size="55" /><br />

		<label><?php _e('Longitude:', 'vh'); ?></label>
		<input type="text" name="vh_map_long" value="<?php echo esc_attr($vh_map_long); ?>" size="55" /><br />
	</div>
</div>
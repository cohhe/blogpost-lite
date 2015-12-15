<?php
$layouts = array(
	'full',
	'right'
);

$selected = get_option($id, $default);
?>

<div class="row-container">
	<h4><?php echo esc_html( $name ); ?></h4>
	<div class="content layouts">
		<?php foreach($layouts as $layout) { ?>
			<input type="radio" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id) . '-' . $layout; ?>" style="display: none;" value="<?php echo esc_attr($layout); ?>" <?php checked($selected, $layout); ?>/>
			<label for="<?php echo esc_attr($id) . '-' . $layout; ?>"><img src="<?php echo VH_ADMIN_IMAGES; ?>/layout/layout-<?php echo esc_attr($layout); ?>.png" alt="" class="<?php if($selected == $layout) echo 'selected'; ?>" /></label>
		<?php } ?>
	</div>
</div>
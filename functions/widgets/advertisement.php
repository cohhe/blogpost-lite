<?php

/**
 * advertisement widget
 */
class vh_advertisement extends WP_Widget {

	private $max_ads = 6;

	public function vh_advertisement() {
		$widget_opts = array(
			'classname'   => 'vh_advertisement',
			'description' => __('Displays advertisement', 'vh')
		);
		parent::__construct('vh_advertisement', __('BlogPost - Advertisement', 'vh'), $widget_opts);
	}

	public function widget($args, $instance) {

		// Vars
		global $vh_is_in_sidebar;

		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

		$count = (int) $instance['count'];

		echo $before_widget;
		if ($title) {
			echo $before_title . $title . $after_title;
		}

		for ($i = 1; $i <= $count; $i++) {
			$image = isset($instance['ad_image'][$i]) ? $instance['ad_image'][$i] : '';
			$link = isset($instance['ad_link'][$i]) ? $instance['ad_link'][$i] : '';
			?>
			<div class="advertisement-container">
				<a href="<?php echo esc_url( $link ); ?>" rel="nofollow" target="_top" class="no_thickbox" title="<?php _e('Advertisement', 'vh') ?>">
					<img src="<?php echo esc_url( $image ); ?>" alt="<?php _e('Advertisement', 'vh') ?>" />
				</a>
			</div>
			<?php
		}

		echo $after_widget;
	}

	public function update($new_instance, $old_instance) {
		$instance          = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = (int) $new_instance['count'];
		for ($i = 1; $i <= $instance['count']; $i++) {
			$instance['ad_image'][$i] = strip_tags($new_instance['ad_image_' . $i]);
			$instance['ad_link'][$i] = strip_tags($new_instance['ad_link_' . $i]);
		}
		return $instance;
	}

	function form($instance) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$count = isset($instance['count']) ? absint($instance['count']) : 1;
		for ($i = 1; $i <= $this->max_ads; $i++) {
			$selected_ad_image[$i] = isset($instance['ad_image'][$i]) ? $instance['ad_image'][$i] : '';
			$selected_ad_link[$i]  = isset($instance['ad_link'][$i]) ? $instance['ad_link'][$i] : '';
		}
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php _e('Title:', 'vh'); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('count') ); ?>"><?php _e('How many ads to display?', 'vh'); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id('count') ); ?>" class="how_many" name="<?php echo esc_attr( $this->get_field_name('count') ); ?>">
				<?php for ($i = 1; $i <= $this->max_ads; $i++): ?>
					<option <?php if ($i == $count) echo 'selected="selected"' ?>><?php echo $i; ?></option>
				<?php endfor ?>
			</select>
		</p>
		<div class="advertisement_container">
			<?php
			for ($i = 1; $i <= $this->max_ads; $i++) {
				$ad_image = "ad_image_$i";
				$ad_link  = "ad_link_$i";
				?>
				<div class="hidden_container" <?php if ($i > $count) { ?>style="display:none"<?php } ?>>
					<span class="adv_counter"><?php echo $i; ?>.</span>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id($ad_image) ); ?>"><?php _e('Image URL:', 'vh'); ?></label>
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id($ad_image) ); ?>" name="<?php echo esc_attr( $this->get_field_name($ad_image) ); ?>" type="text" value="<?php echo esc_attr( $selected_ad_image[$i] ); ?>" />
					</p>
					<p>
						<label for="<?php echo esc_attr( $this->get_field_id($ad_link) ); ?>"><?php _e('Link:', 'vh'); ?></label>
						<input class="widefat" id="<?php echo esc_attr( $this->get_field_id($ad_link) ); ?>" name="<?php echo esc_attr( $this->get_field_name($ad_link) ); ?>" type="text" value="<?php echo esc_attr( $selected_ad_link[$i] ); ?>" />
					</p>
					<p>
						<em><?php _e("Example: <code>http://www.example.com</code>", 'vh'); ?></em>
					</p>
				</div>

			<?php } ?>
		</div><?php
	}

}

register_widget('vh_advertisement');
<?php

/**
* About Us widget
*/

class vh_aboutus extends WP_Widget {
	public function vh_aboutus() {
		$widget_options = array(
			'classname'   => 'vh_aboutus',
			'description' => __('Display an information about your site.', 'vh')
		);
		parent::__construct('vh_aboutus', __('BlogPost - About Us', 'vh') , $widget_options);
	}

	public function widget($args, $instance) {
		extract($args);
		$title     = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$content   = $instance['content'];
		$facebook   = $instance['facebook'];
		$twitter   = $instance['twitter'];
		$instagram   = $instance['instagram'];
		$googleplus   = $instance['googleplus'];
		$pinterest   = $instance['pinterest'];
		$vkontakte   = $instance['vkontakte'];
		
		echo $before_widget;

		if ($title) echo $before_title . $title . $after_title; ?>

		<div class="blogpost-about-us">
		<div class="blogpost-about-us-inner">
			<p>
			<?php
				echo wp_kses( 
					$content, 
					array(
						'a' => array(
							'href' => array(),
							'class' => array()
						)
					)
				);
			?>
			</p>
			<div class="blogpost-about-us-social">
				<?php if ( $facebook != '' ) { ?>
					<a href="<?php echo esc_url( $facebook ); ?>" class="widget-social-icon icon-facebook"></a>
				<?php } ?>
				<?php if ( $twitter != '' ) { ?>
					<a href="<?php echo esc_url( $twitter ); ?>" class="widget-social-icon icon-twitter-1"></a>
				<?php } ?>
				<?php if ( $instagram != '' ) { ?>
					<a href="<?php echo esc_url( $instagram ); ?>" class="widget-social-icon icon-instagram"></a>
				<?php } ?>
				<?php if ( $googleplus != '' ) { ?>
					<a href="<?php echo esc_url( $googleplus ); ?>" class="widget-social-icon icon-gplus"></a>
				<?php } ?>
				<?php if ( $pinterest != '' ) { ?>
					<a href="<?php echo esc_url( $pinterest ); ?>" class="widget-social-icon icon-pinterest"></a>
				<?php } ?>
				<?php if ( $vkontakte != '' ) { ?>
					<a href="<?php echo esc_url( $vkontakte ); ?>" class="widget-social-icon icon-vkontakte"></a>
				<?php } ?>
			</div>
		</div>
		</div>
		<?php

		echo $after_widget;
	}

	public function update($new_instance, $old_instance) {
		$instance              = $old_instance;
		$instance['title']     = strip_tags($new_instance['title']);
		$instance['content']   = strip_tags($new_instance['content']);
		$instance['facebook']   = strip_tags($new_instance['facebook']);
		$instance['twitter']   = strip_tags($new_instance['twitter']);
		$instance['instagram']   = strip_tags($new_instance['instagram']);
		$instance['googleplus']   = strip_tags($new_instance['googleplus']);
		$instance['pinterest']   = strip_tags($new_instance['pinterest']);
		$instance['vkontakte']   = strip_tags($new_instance['vkontakte']);

		return $instance;
	}

	public function form($instance) {
		$title     = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$content   = isset($instance['content']) ? esc_attr($instance['content']) : '';
		$facebook   = isset($instance['facebook']) ? esc_attr($instance['facebook']) : '';
		$twitter   = isset($instance['twitter']) ? esc_attr($instance['twitter']) : '';
		$instagram   = isset($instance['instagram']) ? esc_attr($instance['instagram']) : '';
		$googleplus   = isset($instance['googleplus']) ? esc_attr($instance['googleplus']) : '';
		$pinterest   = isset($instance['pinterest']) ? esc_attr($instance['pinterest']) : '';
		$vkontakte   = isset($instance['vkontakte']) ? esc_attr($instance['vkontakte']) : '';
		
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'vh'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><?php _e('Content:', 'vh'); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>" ><?php echo esc_attr($content); ?></textarea>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('facebook')); ?>"><?php _e('Facebook URL:', 'vh'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('facebook')); ?>" name="<?php echo esc_attr($this->get_field_name('facebook')); ?>" type="text" value="<?php echo esc_attr($facebook); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('twitter')); ?>"><?php _e('Twitter URL:', 'vh'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('twitter')); ?>" name="<?php echo esc_attr($this->get_field_name('twitter')); ?>" type="text" value="<?php echo esc_attr($twitter); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('instagram')); ?>"><?php _e('Instagram URL:', 'vh'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('instagram')); ?>" name="<?php echo esc_attr($this->get_field_name('instagram')); ?>" type="text" value="<?php echo esc_attr($instagram); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('googleplus')); ?>"><?php _e('Google+ URL:', 'vh'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('googleplus')); ?>" name="<?php echo esc_attr($this->get_field_name('googleplus')); ?>" type="text" value="<?php echo esc_attr($googleplus); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('pinterest')); ?>"><?php _e('Pinterest URL:', 'vh'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('pinterest')); ?>" name="<?php echo esc_attr($this->get_field_name('pinterest')); ?>" type="text" value="<?php echo esc_attr($pinterest); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('vkontakte')); ?>"><?php _e('VKontakte URL:', 'vh'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('vkontakte')); ?>" name="<?php echo esc_attr($this->get_field_name('vkontakte')); ?>" type="text" value="<?php echo esc_attr($vkontakte); ?>" />
		</p>
	<?php
	}
}

register_widget('vh_aboutus');
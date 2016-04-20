<?php

	// Vars
	global $blogpost_search_form_button, $blogpost_is_in_sidebar;

	if ($blogpost_search_form_button == 'searchButton') {
		$search_string = '';
	} elseif (get_search_query() == '') {
		$search_string = '';
	} else {
		$search_string = get_search_query();
	}

	if (empty($blogpost_search_form_button)) {
		$blogpost_search_form_button = 'submitButton';
	}

	$class      = 'footer_search_input';
	$form_class = ' gray-form';
	if ( $blogpost_is_in_sidebar === true ) {
		$class      = 'sb-search-input';
		$search_string = '';
		$form_class = '';
	} elseif ( is_search() && $blogpost_is_in_sidebar === 'content' ) {
		$class      = 'span5';
		$form_class = '';
	}
	$random = rand();
?>
<div class="search sb-search" id="sb-search-<?php echo esc_attr( $random ); ?>">
	<form action="<?php echo home_url(); ?>" method="get" class="<?php echo esc_attr( $form_class ); ?>">
		<input type="text" name="s" class="<?php echo esc_attr( $class ); ?>" value="<?php echo esc_attr( $search_string ); ?>" placeholder="<?php _e('Type here for search...', 'vh'); ?>"/>
		<button class="wpb_button  wpb_btn-danger wpb_regularsize square"><span><?php _e('Search', 'vh'); ?></span></button>
		<!-- <input type="submit" name="search" class="btn btn-primary sb-search-submit" value="<?php _e('Search', 'vh'); ?>" /> -->
	</form>
</div><!--end of search-->
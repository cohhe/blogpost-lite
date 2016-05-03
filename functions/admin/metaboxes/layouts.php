<?php
/*
 * Layout options
 */

$config = array(
	'id'       => 'blogpost_layouts',
	'title'    => __('Layouts', 'blogpost'),
	'pages'    => array('page', 'post'),
	'context'  => 'normal',
	'priority' => 'high',
);

$options = array(array(
	'name'    => __('Layout type', 'blogpost'),
	'id'      => 'layouts',
	'type'    => 'layouts',
	'only'    => 'page,post',
	'default' => get_option('default-layout'),
));

require_once(BLOGPOST_METABOXES . '/add_metaboxes.php');
new create_meta_boxes($config, $options);
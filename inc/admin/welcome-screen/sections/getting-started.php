<?php
/**
 * Getting started template
 */

$customizer_url = admin_url() . 'customize.php' ;
$current_theme = wp_get_theme();
?>

<div id="getting_started" class="welcome-screen-tab-pane active">

	<div class="blogpost-tab-pane-center">

		<h1 class="blogpost-welcome-screen-title">Welcome to Blogpost Lite!</h1>

		<p><?php esc_html_e( 'BlogPost Lite is an amazing WordPress blog theme with resposive design.','blogpost-lite'); ?></p>
		<p><?php esc_html_e( 'To ensure you have the best experience while using Blogpost, we have created this simple dashboard. Here you will find all information necessary to start using your theme.', 'blogpost-lite' ); ?>

	</div>

	<hr />

	<div class="blogpost-tab-pane-center">

		<h1><?php esc_html_e( 'Getting started', 'blogpost-lite' ); ?></h1>

		<h4><?php esc_html_e( 'Customize everything in a single place.' ,'blogpost-lite' ); ?></h4>
		<p><?php esc_html_e( 'Using the WordPress Customizer you can easily customize every aspect of the theme.', 'blogpost-lite' ); ?></p>
		<p><a href="<?php echo esc_url( $customizer_url ); ?>" class="button button-primary"><?php esc_html_e( 'Go to Customizer', 'blogpost-lite' ); ?></a></p>

	</div>

	<div class="welcome-screen-clear"></div>

</div>

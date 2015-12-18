<?php
/**
 * Welcome Screen Class
 */
class blogpost_Welcome {

	/**
	 * Constructor for the welcome screen
	 */
	public function __construct() {

		/* create dashbord page */
		add_action( 'admin_menu', array( $this, 'blogpost_welcome_register_menu' ) );

		/* activation notice */
		add_action( 'load-themes.php', array( $this, 'blogpost_activation_admin_notice' ) );

		/* enqueue script and style for welcome screen */
		add_action( 'admin_enqueue_scripts', array( $this, 'blogpost_welcome_style_and_scripts' ) );

		/* enqueue script for customizer */
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'blogpost_welcome_scripts_for_customizer' ) );

		/* load welcome screen */
		add_action( 'blogpost_welcome', array( $this, 'blogpost_welcome_getting_started' ), 	    10 );
		add_action( 'blogpost_welcome', array( $this, 'blogpost_welcome_github' ), 		            40 );
		add_action( 'blogpost_welcome', array( $this, 'blogpost_welcome_free_pro' ), 				60 );

		/* ajax callback for dismissable required actions */
		add_action( 'wp_ajax_blogpost_dismiss_required_action', array( $this, 'blogpost_dismiss_required_action_callback') );
		add_action( 'wp_ajax_nopriv_blogpost_dismiss_required_action', array($this, 'blogpost_dismiss_required_action_callback') );

	}

	/**
	 * Creates the dashboard page
	 * @see  add_theme_page()
	 * @since 1.8.2.4
	 */
	public function blogpost_welcome_register_menu() {
		add_theme_page( 'About Blogpost Lite', 'About Blogpost Lite', 'activate_plugins', 'blogpost-welcome-screen', array( $this, 'blogpost_welcome_screen' ) );
	}

	/**
	 * Adds an admin notice upon successful activation.
	 * @since 1.8.2.4
	 */
	public function blogpost_activation_admin_notice() {
		global $pagenow;

		if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
			add_action( 'admin_notices', array( $this, 'blogpost_welcome_admin_notice' ), 99 );
		}
	}

	/**
	 * Display an admin notice linking to the welcome screen
	 * @since 1.8.2.4
	 */
	public function blogpost_welcome_admin_notice() {
		?>
			<div class="updated notice is-dismissible">
				<p><?php echo sprintf( esc_html__( 'Welcome! Thank you for choosing Blogpost Lite! To fully take advantage of the best our theme can offer please make sure you visit our %swelcome page%s.', 'blogpost' ), '<a href="' . esc_url( admin_url( 'themes.php?page=blogpost-welcome-screen' ) ) . '">', '</a>' ); ?></p>
				<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=blogpost-welcome-screen' ) ); ?>" class="button" style="text-decoration: none;"><?php _e( 'Get started with Blogpost Lite', 'blogpost' ); ?></a></p>
			</div>
		<?php
	}

	/**
	 * Load welcome screen css and javascript
	 * @since  1.8.2.4
	 */
	public function blogpost_welcome_style_and_scripts( $hook_suffix ) {

		if ( 'appearance_page_blogpost-welcome-screen' == $hook_suffix ) {
			wp_enqueue_style( 'blogpost-welcome-screen-screen-css', get_template_directory_uri() . '/inc/admin/welcome-screen/css/welcome.css' );
			wp_enqueue_script( 'blogpost-welcome-screen-screen-js', get_template_directory_uri() . '/inc/admin/welcome-screen/js/welcome.js', array('jquery') );

			global $blogpost_required_actions;

			$nr_actions_required = 0;

			/* get number of required actions */
			if( get_option('blogpost_show_required_actions') ):
				$blogpost_show_required_actions = get_option('blogpost_show_required_actions');
			else:
				$blogpost_show_required_actions = array();
			endif;

			if( !empty($blogpost_required_actions) ):
				foreach( $blogpost_required_actions as $blogpost_required_action_value ):
					if(( !isset( $blogpost_required_action_value['check'] ) || ( isset( $blogpost_required_action_value['check'] ) && ( $blogpost_required_action_value['check'] == false ) ) ) && ((isset($blogpost_show_required_actions[$blogpost_required_action_value['id']]) && ($blogpost_show_required_actions[$blogpost_required_action_value['id']] == true)) || !isset($blogpost_show_required_actions[$blogpost_required_action_value['id']]) )) :
						$nr_actions_required++;
					endif;
				endforeach;
			endif;

			wp_localize_script( 'blogpost-welcome-screen-screen-js', 'blogpostWelcomeScreenObject', array(
				'nr_actions_required' => $nr_actions_required,
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'template_directory' => get_template_directory_uri(),
				'no_required_actions_text' => __( 'Hooray! There are no required actions for you right now.','blogpost' )
			) );
		}
	}

	/**
	 * Load scripts for customizer page
	 * @since  1.8.2.4
	 */
	public function blogpost_welcome_scripts_for_customizer() {

		wp_enqueue_style( 'blogpost-welcome-screen-screen-customizer-css', get_template_directory_uri() . '/inc/admin/welcome-screen/css/welcome_customizer.css' );
		wp_enqueue_script( 'blogpost-welcome-screen-screen-customizer-js', get_template_directory_uri() . '/inc/admin/welcome-screen/js/welcome_customizer.js', array('jquery'), '20120206', true );

		global $blogpost_required_actions;

		$nr_actions_required = 0;

		/* get number of required actions */
		if( get_option('blogpost_show_required_actions') ):
			$blogpost_show_required_actions = get_option('blogpost_show_required_actions');
		else:
			$blogpost_show_required_actions = array();
		endif;

		if( !empty($blogpost_required_actions) ):
			foreach( $blogpost_required_actions as $blogpost_required_action_value ):
				if(( !isset( $blogpost_required_action_value['check'] ) || ( isset( $blogpost_required_action_value['check'] ) && ( $blogpost_required_action_value['check'] == false ) ) ) && ((isset($blogpost_show_required_actions[$blogpost_required_action_value['id']]) && ($blogpost_show_required_actions[$blogpost_required_action_value['id']] == true)) || !isset($blogpost_show_required_actions[$blogpost_required_action_value['id']]) )) :
					$nr_actions_required++;
				endif;
			endforeach;
		endif;

		wp_localize_script( 'blogpost-welcome-screen-screen-customizer-js', 'blogpostWelcomeScreenCustomizerObject', array(
			'nr_actions_required' => $nr_actions_required,
			'aboutpage' => esc_url( admin_url( 'themes.php?page=blogpost-welcome-screen#actions_required' ) ),
			'customizerpage' => esc_url( admin_url( 'customize.php#actions_required' ) ),
			'themeinfo' => __('View Theme Info','blogpost'),
		) );
	}

	/**
	 * Dismiss required actions
	 * @since 1.8.2.4
	 */
	public function blogpost_dismiss_required_action_callback() {

		global $blogpost_required_actions;

		$blogpost_dismiss_id = (isset($_GET['dismiss_id'])) ? $_GET['dismiss_id'] : 0;

		echo $blogpost_dismiss_id; /* this is needed and it's the id of the dismissable required action */

		if( !empty($blogpost_dismiss_id) ):

			/* if the option exists, update the record for the specified id */
			if( get_option('blogpost_show_required_actions') ):

				$blogpost_show_required_actions = get_option('blogpost_show_required_actions');

				$blogpost_show_required_actions[$blogpost_dismiss_id] = false;

				update_option( 'blogpost_show_required_actions',$blogpost_show_required_actions );

			/* create the new option,with false for the specified id */
			else:

				$blogpost_show_required_actions_new = array();

				if( !empty($blogpost_required_actions) ):

					foreach( $blogpost_required_actions as $blogpost_required_action ):

						if( $blogpost_required_action['id'] == $blogpost_dismiss_id ):
							$blogpost_show_required_actions_new[$blogpost_required_action['id']] = false;
						else:
							$blogpost_show_required_actions_new[$blogpost_required_action['id']] = true;
						endif;

					endforeach;

				update_option( 'blogpost_show_required_actions', $blogpost_show_required_actions_new );

				endif;

			endif;

		endif;

		die(); // this is required to return a proper result
	}


	/**
	 * Welcome screen content
	 * @since 1.8.2.4
	 */
	public function blogpost_welcome_screen() {

		require_once( ABSPATH . 'wp-load.php' );
		require_once( ABSPATH . 'wp-admin/admin.php' );
		require_once( ABSPATH . 'wp-admin/admin-header.php' );
		?>

		<ul class="welcome-screen-nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#getting_started" aria-controls="getting_started" role="tab" data-toggle="tab"><?php esc_html_e( 'Getting started','blogpost'); ?></a></li>
			<li role="presentation"><a href="#github" aria-controls="github" role="tab" data-toggle="tab"><?php esc_html_e( 'Contribute','blogpost'); ?></a></li>
			<li role="presentation"><a href="#free_pro" aria-controls="free_pro" role="tab" data-toggle="tab"><?php esc_html_e( 'Free VS PRO','blogpost'); ?></a></li>
		</ul>

		<div class="welcome-screen-tab-content">

			<?php
			/**
			 * @hooked blogpost_welcome_getting_started - 10
			 * @hooked blogpost_welcome_actions_required - 20
			 * @hooked blogpost_welcome_child_themes - 30
			 * @hooked blogpost_welcome_github - 40
			 * @hooked blogpost_welcome_changelog - 50
			 * @hooked blogpost_welcome_free_pro - 60
			 */
			do_action( 'blogpost_welcome' ); ?>

		</div>
		<?php
	}

	/**
	 * Getting started
	 * @since 1.8.2.4
	 */
	public function blogpost_welcome_getting_started() {
		require_once( get_template_directory() . '/inc/admin/welcome-screen/sections/getting-started.php' );
	}

	/**
	 * Contribute
	 * @since 1.8.2.4
	 */
	public function blogpost_welcome_github() {
		require_once( get_template_directory() . '/inc/admin/welcome-screen/sections/github.php' );
	}

	/**
	 * Free vs PRO
	 * @since 1.8.2.4
	 */
	public function blogpost_welcome_free_pro() {
		require_once( get_template_directory() . '/inc/admin/welcome-screen/sections/free_pro.php' );
	}
}

$GLOBALS['blogpost_Welcome'] = new blogpost_Welcome();
<?php
/**
 * The template for displaying the footer.
 */

global $vh_is_footer;
$vh_is_footer = true;

$retina_logo_class = '';
$logo_size_html = '';
$map_class = '';

?>
			</div><!--end of main-->
		</div><!--end of wrapper-->
		<?php
			$javascript_code = get_theme_mod('blogpost_custom_js', '');
			if ( !empty( $javascript_code ) ) { ?>
				<!-- Tracking Code -->
				<?php
				echo wp_kses( 
					$javascript_code, 
					array(
						'script' => array(
							'src' => array()
						)
					)
				);
			}
		?>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>
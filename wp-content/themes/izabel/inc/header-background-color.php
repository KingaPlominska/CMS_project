<?php
/**
 * Customizer functionality
 *
 * @package Izabel
 */

/**
 * Sets up the WordPress core custom header and custom background features.
 *
 * @since Izabel 1.0
 *
 * @see izabel_header_style()
 */
function izabel_custom_header_and_background() {
	$default_text_color       = '#000000';

	/**
	 * Filter the arguments used when adding 'custom-header' support in Persona.
	 *
	 * @since Izabel 1.0
	 *
	 * @param array $args {
	 *     An array of custom-header support arguments.
	 *
	 *     @type string $default-text-color Default color of the header text.
	 *     @type int      $width            Width in pixels of the custom header image. Default 1200.
	 *     @type int      $height           Height in pixels of the custom header image. Default 280.
	 *     @type bool     $flex-height      Whether to allow flexible-height header images. Default true.
	 *     @type callable $wp-head-callback Callback function used to style the header image and text
	 *                                      displayed on the blog.
	 * }
	 */
	add_theme_support( 'custom-header', apply_filters( 'izabel_custom_header_args', array(
		'default-image'      	 => get_parent_theme_file_uri( '/assets/images/header-image.jpg' ),
		'default-text-color'     => $default_text_color,
		'width'                  => 1920,
		'height'                 => 835,
		'flex-height'            => true,
		'flex-height'            => true,
		'wp-head-callback'       => 'izabel_header_style',
		'video'                  => true,
	) ) );

	register_default_headers( array(
		'default-image' => array(
			'url'           => '%s/assets/images/header-image.jpg',
			'thumbnail_url' => '%s/assets/images/header-image-275x155.jpg',
			'description'   => esc_html__( 'Default Header Image', 'izabel' ),
		),
	) );
}
add_action( 'after_setup_theme', 'izabel_custom_header_and_background' );

if ( ! function_exists( 'izabel_header_style' ) ) :
	/**
	 * Styles the header text displayed on the site.
	 *
	 * Create your own izabel_header_style() function to override in a child theme.
	 *
	 * @since Izabel 1.0
	 *
	 * @see izabel_custom_header_and_background().
	 */
	function izabel_header_style() {

	$header_image = izabel_featured_overall_image();

	    if ( 'disable' !== $header_image ) : ?>
	        <style type="text/css" rel="header-image">
	            .custom-header:before {
	                background-image: url( <?php echo esc_url( $header_image ); ?>);
					background-position: center top;
					background-repeat: no-repeat;
					background-size: cover;
					content: "";
					display: block;
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 100%;
					z-index: -1;
	            }
	        </style>
	    <?php
	    endif;
	// If the header text has been hidden.
	?>
	<?php
	// If the header text option is untouched, let's bail.
	if ( display_header_text() ) {
		$header_text_color = get_header_textcolor();
		$default_color     = '#000000';

		if ( $default_color !== $header_text_color ) :
		?>
		<style type="text/css" id="izabel-header-css">
		.site-title a,
		.site-description {
			color: #<?php echo esc_attr( $header_text_color ); ?>;
		}
		</style>
	<?php
		endif;
	} else {
		?>
		<style type="text/css" id="izabel-header-css">
		.site-branding {
			margin: 0 auto 0 0;
		}

		.site-identity {
			clip: rect(1px, 1px, 1px, 1px);
			position: absolute;
		}
		</style>
	<?php
	}
}
endif; // izabel_header_style

/**
 * Customize video play/pause button in the custom header.
 *
 * @param array $settings header video settings.
 */
function izabel_video_controls( $settings ) {
	$settings['l10n']['play'] = '<span class="screen-reader-text">' . esc_html__( 'Play background video', 'izabel' ) . '</span>' . izabel_get_svg( array(
		'icon' => 'play',
	) );
	$settings['l10n']['pause'] = '<span class="screen-reader-text">' . esc_html__( 'Pause background video', 'izabel' ) . '</span>' . izabel_get_svg( array(
		'icon' => 'pause',
	) );

	$enable = get_theme_mod( 'izabel_header_enable_media_on_mobile' );

    // Enable header video on mobile devices
    if ( $enable ) {
		$settings['minWidth']  = 100;
		$settings['minHeight'] = 100;
    }

	return $settings;
}
add_filter( 'header_video_settings', 'izabel_video_controls' );

/**
 * Binds the JS listener to make Customizer color_scheme control.
 *
 * Passes color scheme data as colorScheme global.
 *
 * @since Izabel 1.0
 */
function izabel_customize_control_js() {
	wp_enqueue_style( 'izabel-custom-controls-css', trailingslashit( esc_url( get_template_directory_uri() ) ) . 'assets/css/customizer.css' );
}
add_action( 'customize_controls_enqueue_scripts', 'izabel_customize_control_js' );
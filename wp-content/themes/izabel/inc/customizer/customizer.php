<?php
/**
 * Theme Customizer
 *
 * @package Izabel
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function izabel_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	$wp_customize->get_setting( 'header_image' )->transport 	= 'refresh';


	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.site-title a',
			'container_inclusive' => false,
			'render_callback' => 'izabel_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector' => '.site-description',
			'container_inclusive' => false,
			'render_callback' => 'izabel_customize_partial_blogdescription',
		) );
	}

	// Important Links.
	$wp_customize->add_section( 'izabel_important_links', array(
		'priority'      => 999,
		'title'         => esc_html__( 'Important Links', 'izabel' ),
	) );

	// Has dummy Sanitizaition function as it contains no value to be sanitized.
	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_important_links',
			'sanitize_callback' => 'sanitize_text_field',
			'custom_control'    => 'izabel_Important_Links_Control',
			'label'             => esc_html__( 'Important Links', 'izabel' ),
			'section'           => 'izabel_important_links',
			'type'              => 'izabel_important_links',
		)
	);
	// Important Links End.
}
add_action( 'customize_register', 'izabel_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function izabel_customize_preview_js() {
	wp_enqueue_script( 'izabel-customize-preview', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/customize-preview.min.js', array( 'customize-preview' ), '20170816', true );
}
add_action( 'customize_preview_init', 'izabel_customize_preview_js' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @since Izabel 1.0
 * @see izabel_customize_register()
 *
 * @return void
 */
function izabel_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @since Izabel 1.0
 * @see izabel_customize_register()
 *
 * @return void
 */
function izabel_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Include Custom Controls
 */
require get_parent_theme_file_path( 'inc/customizer/custom-controls.php' );

/**
 * Include Header Media Options
 */
require get_parent_theme_file_path( 'inc/customizer/header-media.php' );

/**
 * Include Theme Options
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options.php' );

/**
 * Include Hero Content
 */
require get_parent_theme_file_path( 'inc/customizer/hero-content.php' );

/**
 * Include Slider
 */
require get_parent_theme_file_path( 'inc/customizer/featured-slider.php' );

/**
 * Include Featured Content
 */
require get_parent_theme_file_path( 'inc/customizer/featured-content.php' );

/**
 * Include Testimonial
 */
require get_parent_theme_file_path( 'inc/customizer/testimonial.php' );

/**
 * Include Portfolio
 */
require get_parent_theme_file_path( 'inc/customizer/portfolio.php' );

/**
 * Include WooCommerce Support
 */
require get_parent_theme_file_path( 'inc/customizer/woocommerce.php' );

/**
 * Include Customizer Helper Functions
 */
require get_parent_theme_file_path( 'inc/customizer/helpers.php' );

/**
 * Include Sanitization functions
 */
require get_parent_theme_file_path( 'inc/customizer/sanitize-functions.php' );

/**
 * Include service
 */
require get_parent_theme_file_path( 'inc/customizer/service.php' );

/**
 * Include Popular Products
 */
require get_parent_theme_file_path( 'inc/customizer/woo-products-top-rated.php' );

/**
 * Include Collection
 */
require get_parent_theme_file_path( 'inc/customizer/collection.php' );
/**
 * Include Reset Buttons
 */
require get_parent_theme_file_path( 'inc/customizer/reset.php' );

/**
 * Include Upgrade Button
 */
require get_parent_theme_file_path( 'inc/customizer/upgrade-button/class-customize.php' );

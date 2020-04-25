<?php
/**
 * Featured Slider Options
 *
 * @package Izabel
 */

/**
 * Add hero content options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function izabel_slider_options( $wp_customize ) {
	$wp_customize->add_section( 'izabel_featured_slider', array(
			'panel' => 'izabel_theme_options',
			'title' => esc_html__( 'Featured Slider', 'izabel' ),
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_slider_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'izabel_sanitize_select',
			'choices'           => izabel_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'izabel' ),
			'section'           => 'izabel_featured_slider',
			'type'              => 'select',
		)
	);

	/*Overlay Option for Header Media*/
	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_slider_opacity',
			'default'           => '0',
			'sanitize_callback' => 'izabel_sanitize_number_range',
			'active_callback'   => 'izabel_is_slider_active',
			'label'             => esc_html__( 'Image Overlay', 'izabel' ),
			'section'           => 'izabel_featured_slider',
			'type'              => 'number',
			'input_attrs'       => array(
				'style' => 'width: 60px;',
				'min'   => 0,
				'max'   => 100,
			),
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_slider_number',
			'default'           => '4',
			'sanitize_callback' => 'izabel_sanitize_number_range',
			'active_callback'   => 'izabel_is_slider_active',
			'description'       => esc_html__( 'Save and refresh the page if No. of Slides is changed (Max no of slides is 20)', 'izabel' ),
			'input_attrs'       => array(
				'style' => 'width: 100px;',
				'min'   => 0,
				'max'   => 20,
				'step'  => 1,
			),
			'label'             => esc_html__( 'No of Slides', 'izabel' ),
			'section'           => 'izabel_featured_slider',
			'type'              => 'number',
		)
	);

	$slider_number = get_theme_mod( 'izabel_slider_number', 4 );

	for ( $i = 1; $i <= $slider_number ; $i++ ) {
		// Page Sliders
		izabel_register_option( $wp_customize, array(
				'name'              =>'izabel_slider_page_' . $i,
				'sanitize_callback' => 'izabel_sanitize_post',
				'active_callback'   => 'izabel_is_slider_active',
				'label'             => esc_html__( 'Page', 'izabel' ) . ' # ' . $i,
				'section'           => 'izabel_featured_slider',
				'type'              => 'dropdown-pages',
			)
		);
	} // End for().
}
add_action( 'customize_register', 'izabel_slider_options' );

/** Active Callback Functions */

if ( ! function_exists( 'izabel_is_slider_active' ) ) :
	/**
	* Return true if slider is active
	*
	* @since Izabel 1.0
	*/
	function izabel_is_slider_active( $control ) {
		$enable = $control->manager->get_setting( 'izabel_slider_option' )->value();

		//return true only if previwed page on customizer matches the type option selected
		return izabel_check_section( $enable );
	}
endif;



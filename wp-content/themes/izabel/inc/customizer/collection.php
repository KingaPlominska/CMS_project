<?php
/**
 * Collection options
 *
 * @package Izabel
 */

/**
 * Add collection options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function izabel_collection_options( $wp_customize ) {
	$wp_customize->add_section( 'izabel_collection', array(
			'title' => esc_html__( 'Collection', 'izabel' ),
			'panel' => 'izabel_theme_options',
		)
	);

	// Add color scheme setting and control.
	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_collection_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'izabel_sanitize_select',
			'choices'           => izabel_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'izabel' ),
			'section'           => 'izabel_collection',
			'type'              => 'select',
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_collection_archive_title',
			'default'           => esc_html__( 'Collection', 'izabel' ),
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'izabel_is_collection_active',
			'label'             => esc_html__( 'Title', 'izabel' ),
			'section'           => 'izabel_collection',
			'type'              => 'text',
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_collection_number',
			'default'           => 6,
			'sanitize_callback' => 'izabel_sanitize_number_range',
			'active_callback'   => 'izabel_is_collection_active',
			'description'       => esc_html__( 'Save and refresh the page if No. of items is changed (Max no of items: 20)', 'izabel' ),
			'input_attrs'       => array(
				'style' => 'width: 100px;',
				'min'   => 0,
			),
			'label'             => esc_html__( 'No of Items', 'izabel' ),
			'section'           => 'izabel_collection',
			'type'              => 'number',
		)
	);

	$number = get_theme_mod( 'izabel_collection_number', 6 );

	for ( $i = 1; $i <= $number ; $i++ ) {
		
		if ( izabel_is_woocommerce_activated() ) {
			izabel_register_option( $wp_customize, array(
					'name'              => 'izabel_collection_product_' . $i,
					'sanitize_callback' => 'izabel_sanitize_post',
					'active_callback'   => 'izabel_is_collection_active',
					'label'             => esc_html__( 'Product', 'izabel' ) . ' ' . $i ,
					'section'           => 'izabel_collection',
					'type'              => 'select',
					'choices'           => izabel_generate_products_array(),
				)
			);
		}
	} // End for().
}
add_action( 'customize_register', 'izabel_collection_options', 10 );

/** Active Callback Functions */

if ( ! function_exists( 'izabel_is_collection_active' ) ) :
	/**
	* Return true if slider is active
	*
	* @since Izabel 1.0
	*/
	function izabel_is_collection_active( $control ) {
		$enable = $control->manager->get_setting( 'izabel_collection_option' )->value();

		//return true only if previwed page on customizer matches the type option selected
		return izabel_check_section( $enable );
	}
endif;

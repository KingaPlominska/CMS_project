<?php
/**
 * Adding support for WooCommerce Products Showcase Option
 */

if ( ! class_exists( 'WooCommerce' ) ) {
    // Bail if WooCommerce is not installed
    return;
}

/**
 * Add WooCommerce Product Showcase Options to customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function izabel_trp_showcase( $wp_customize ) {
   $wp_customize->add_section( 'izabel_trp_showcase', array(
        'title' => esc_html__( 'Popular Products', 'izabel' ),
        'panel' => 'izabel_theme_options',
    ) );

    izabel_register_option( $wp_customize, array(
            'name'              => 'izabel_trp_showcase_option',
            'default'           => 'disabled',
            'sanitize_callback' => 'izabel_sanitize_select',
            'choices'           => izabel_section_visibility_options(),
            'label'             => esc_html__( 'Enable on', 'izabel' ),
            'section'           => 'izabel_trp_showcase',
            'type'              => 'select',
        )
    );

    izabel_register_option( $wp_customize, array(
            'name'              => 'izabel_trp_showcase_headline',
            'default'           => esc_html__( 'Popular Products', 'izabel' ),
            'sanitize_callback' => 'wp_kses_post',
            'label'             => esc_html__( 'Headline', 'izabel' ),
            'active_callback'   => 'izabel_is_trp_showcase_active',
            'section'           => 'izabel_trp_showcase',
            'type'              => 'text',
        )
    );

    izabel_register_option( $wp_customize, array(
            'name'              => 'izabel_trp_showcase_subheadline',
            'default'           => esc_html__( 'This season\'s top sold products', 'izabel' ),
            'sanitize_callback' => 'wp_kses_post',
            'label'             => esc_html__( 'Sub headline', 'izabel' ),
            'active_callback'   => 'izabel_is_trp_showcase_active',
            'section'           => 'izabel_trp_showcase',
            'type'              => 'text',
        )
    );

    izabel_register_option( $wp_customize, array(
            'name'              => 'izabel_trp_showcase_number',
            'default'           => 4,
            'sanitize_callback' => 'izabel_sanitize_number_range',
            'active_callback'   => 'izabel_is_trp_showcase_active',
            'description'       => esc_html__( 'Save and refresh the page if No. of Products is changed. Set -1 to display all', 'izabel' ),
            'input_attrs'       => array(
                'style' => 'width: 50px;',
                'min'   => -1,
            ),
            'label'             => esc_html__( 'No of Products', 'izabel' ),
            'section'           => 'izabel_trp_showcase',
            'type'              => 'number',
        )
    );
}
add_action( 'customize_register', 'izabel_trp_showcase', 10 );

/** Active Callback Functions **/
if( ! function_exists( 'izabel_is_trp_showcase_active' ) ) :
    /**
    * Return true if featured content is active
    *
    * @since Izabel Pro 1.0
    */
    function izabel_is_trp_showcase_active( $control ) {
        $enable = $control->manager->get_setting( 'izabel_trp_showcase_option' )->value();

        return ( izabel_check_section( $enable ) );
    }
endif;

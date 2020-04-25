<?php
/**
 * Add Testimonial Settings in Customizer
 *
 * @package Izabel
*/

/**
 * Add testimonial options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function izabel_testimonial_options( $wp_customize ) {
    // Add note to Jetpack Testimonial Section
    izabel_register_option( $wp_customize, array(
            'name'              => 'izabel_jetpack_testimonial_cpt_note',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Izabel_Note_Control',
            'label'             => sprintf( esc_html__( 'For Testimonial Options for Izabel Theme, go %1$shere%2$s', 'izabel' ),
                '<a href="javascript:wp.customize.section( \'izabel_testimonials\' ).focus();">',
                 '</a>'
            ),
           'section'            => 'jetpack_testimonials',
            'type'              => 'description',
            'priority'          => 1,
        )
    );

    $wp_customize->add_section( 'izabel_testimonials', array(
            'panel'    => 'izabel_theme_options',
            'title'    => esc_html__( 'Testimonials', 'izabel' ),
        )
    );

    $action = 'install-plugin';
    $slug   = 'essential-content-types';

    $install_url = wp_nonce_url(
        add_query_arg(
            array(
                'action' => $action,
                'plugin' => $slug
            ),
            admin_url( 'update.php' )
        ),
        $action . '_' . $slug
    );

    izabel_register_option( $wp_customize, array(
            'name'              => 'izabel_testimonial_jetpack_note',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Izabel_Note_Control',
            'active_callback'   => 'izabel_is_ect_testimonial_inactive',
            /* translators: 1: <a>/link tag start, 2: </a>/link tag close. */
            'label'             => sprintf( esc_html__( 'For Testimonial, install %1$sEssential Content Types%2$s Plugin with testimonial Type Enabled', 'izabel' ),
                '<a target="_blank" href="' . esc_url( $install_url ) . '">',
                '</a>'

            ),
           'section'            => 'izabel_testimonials',
            'type'              => 'description',
            'priority'          => 1,
        )
    );

    izabel_register_option( $wp_customize, array(
            'name'              => 'izabel_testimonial_option',
            'default'           => 'disabled',
            'sanitize_callback' => 'izabel_sanitize_select',
            'active_callback'   => 'izabel_is_ect_testimonial_active',
            'choices'           => izabel_section_visibility_options(),
            'label'             => esc_html__( 'Enable on', 'izabel' ),
            'section'           => 'izabel_testimonials',
            'type'              => 'select',
            'priority'          => 1,
        )
    );

    izabel_register_option( $wp_customize, array(
            'name'              => 'izabel_testimonial_cpt_note',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Izabel_Note_Control',
            'active_callback'   => 'izabel_is_testimonial_active',
            /* translators: 1: <a>/link tag start, 2: </a>/link tag close. */
			'label'             => sprintf( esc_html__( 'For CPT heading and sub-heading, go %1$shere%2$s', 'izabel' ),
                '<a href="javascript:wp.customize.section( \'jetpack_testimonials\' ).focus();">',
                '</a>'
            ),
            'section'           => 'izabel_testimonials',
            'type'              => 'description',
        )
    );

    izabel_register_option( $wp_customize, array(
            'name'              => 'izabel_testimonial_number',
            'default'           => '4',
            'sanitize_callback' => 'izabel_sanitize_number_range',
            'active_callback'   => 'izabel_is_testimonial_active',
            'label'             => esc_html__( 'Number of items to show', 'izabel' ),
            'section'           => 'izabel_testimonials',
            'type'              => 'number',
            'input_attrs'       => array(
                'style'             => 'width: 100px;',
                'min'               => 0,
            ),
        )
    );

    $number = get_theme_mod( 'izabel_testimonial_number', 4 );

    for ( $i = 1; $i <= $number ; $i++ ) {
        //for CPT
        izabel_register_option( $wp_customize, array(
            'name'              => 'izabel_testimonial_cpt_' . $i,
            'sanitize_callback' => 'izabel_sanitize_post',
            'active_callback'   => 'izabel_is_testimonial_active',
            'label'             => esc_html__( 'Testimonial', 'izabel' ) . ' ' . $i ,
            'section'           => 'izabel_testimonials',
            'type'              => 'select',
            'choices'           => izabel_generate_post_array( 'jetpack-testimonial' ),
            )
        );
    } // End for().
}
add_action( 'customize_register', 'izabel_testimonial_options' );

/**
 * Active Callback Functions
 */
if ( ! function_exists( 'izabel_is_testimonial_active' ) ) :
    /**
    * Return true if testimonial is active
    *
    * @since Izabel 1.0
    */
    function izabel_is_testimonial_active( $control ) {
        $enable = $control->manager->get_setting( 'izabel_testimonial_option' )->value();

        //return true only if previwed page on customizer matches the type of content option selected
        return ( izabel_is_ect_testimonial_active( $control ) &&  izabel_check_section( $enable ) );
    }
endif;

if ( ! function_exists( 'izabel_is_ect_testimonial_inactive' ) ) :
    /**
    *
    * @since Izabel 1.0
    */
    function izabel_is_ect_testimonial_inactive( $control ) {
        return ! ( class_exists( 'Essential_Content_Jetpack_testimonial' ) || class_exists( 'Essential_Content_Pro_Jetpack_testimonial' ) );
    }
endif;

if ( ! function_exists( 'izabel_is_ect_testimonial_active' ) ) :
    /**
    *
    * @since Izabel 1.0
    */
    function izabel_is_ect_testimonial_active( $control ) {
        return ( class_exists( 'Essential_Content_Jetpack_testimonial' ) || class_exists( 'Essential_Content_Pro_Jetpack_testimonial' ) );
    }
endif;


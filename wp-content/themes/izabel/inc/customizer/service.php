<?php
/**
* The template for adding Service Settings in Customizer
*
 * @package Izabel
*/

function izabel_service_options( $wp_customize ) {
	// Add note to Jetpack Portfolio Section
    izabel_register_option( $wp_customize, array(
            'name'              => 'izabel_jetpack_portfolio_cpt_note',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Izabel_Note_Control',
            'label'             => sprintf( esc_html__( 'For Service Options for Izabel Theme, go %1$shere%2$s', 'izabel' ),
                 '<a href="javascript:wp.customize.section( \'izabel_service\' ).focus();">',
                 '</a>'
            ),
            'section'           => 'ect_service',
            'type'              => 'description',
            'priority'          => 1,
        )
    );

	$wp_customize->add_section( 'izabel_service', array(
			'panel'    => 'izabel_theme_options',
			'title'    => esc_html__( 'Service', 'izabel' ),
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
            'name'              => 'izabel_service_jetpack_note',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Izabel_Note_Control',
            'active_callback'   => 'izabel_is_ect_services_inactive',
            /* translators: 1: <a>/link tag start, 2: </a>/link tag close. */
            'label'             => sprintf( esc_html__( 'For Services, install %1$sEssential Content Types%2$s Plugin with Service Type Enabled', 'izabel' ),
                '<a target="_blank" href="' . esc_url( $install_url ) . '">',
                '</a>'

            ),
           'section'            => 'izabel_service',
            'type'              => 'description',
            'priority'          => 1,
        )
    );

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_service_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'izabel_sanitize_select',
			'active_callback'   => 'izabel_is_ect_services_active',
			'choices'           => izabel_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'izabel' ),
			'section'           => 'izabel_service',
			'type'              => 'select',
		)
	);

    izabel_register_option( $wp_customize, array(
            'name'              => 'izabel_service_cpt_note',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Izabel_Note_Control',
            'active_callback'   => 'izabel_is_service_active',
            /* translators: 1: <a>/link tag start, 2: </a>/link tag close. */
			'label'             => sprintf( esc_html__( 'For CPT heading and sub-heading, go %1$shere%2$s', 'izabel' ),
                 '<a href="javascript:wp.customize.control( \'ect_service_title\' ).focus();">',
                 '</a>'
            ),
            'section'           => 'izabel_service',
            'type'              => 'description',
        )
    );

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_service_number',
			'default'           => 4,
			'sanitize_callback' => 'izabel_sanitize_number_range',
			'active_callback'   => 'izabel_is_service_active',
			'description'       => esc_html__( 'Save and refresh the page if No. of Service is changed', 'izabel' ),
			'input_attrs'       => array(
				'style' => 'width: 100px;',
				'min'   => 0,
			),
			'label'             => esc_html__( 'No of Service', 'izabel' ),
			'section'           => 'izabel_service',
			'type'              => 'number',
		)
	);

	$number = get_theme_mod( 'izabel_service_number', 4 );

	for ( $i = 1; $i <= $number ; $i++ ) {

		//for CPT
		izabel_register_option( $wp_customize, array(
				'name'              => 'izabel_service_cpt_' . $i,
				'sanitize_callback' => 'izabel_sanitize_post',
				'default'           => 0,
				'active_callback'   => 'izabel_is_service_active',
				'label'             => esc_html__( 'Service ', 'izabel' ) . ' ' . $i ,
				'section'           => 'izabel_service',
				'type'              => 'select',
				'choices'           => izabel_generate_post_array( 'ect-service' ),
			)
		);

	} // End for().
}
add_action( 'customize_register', 'izabel_service_options' );

if ( ! function_exists( 'izabel_is_service_active' ) ) :
	/**
	* Return true if service is active
	*
	* @since Izabel 1.0
	*/
	function izabel_is_service_active( $control ) {
		$enable = $control->manager->get_setting( 'izabel_service_option' )->value();

		//return true only if previwed page on customizer matches the type of content option selected
		return ( izabel_is_ect_services_active( $control ) &&  izabel_check_section( $enable ) );
	}
endif;

if ( ! function_exists( 'izabel_is_ect_services_inactive' ) ) :
    /**
    * Return true if service is active
    *
    * @since Izabel 1.0
    */
    function izabel_is_ect_services_inactive( $control ) {
        return ! ( class_exists( 'Essential_Content_Service' ) || class_exists( 'Essential_Content_Pro_Service' ) );
    }
endif;

if ( ! function_exists( 'izabel_is_ect_services_active' ) ) :
    /**
    * Return true if service is active
    *
    * @since Izabel 1.0
    */
    function izabel_is_ect_services_active( $control ) {
        return ( class_exists( 'Essential_Content_Service' ) || class_exists( 'Essential_Content_Pro_Service' ) );
    }
endif;
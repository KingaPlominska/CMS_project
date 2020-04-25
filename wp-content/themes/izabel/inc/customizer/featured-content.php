<?php
/**
 * Featured Content options
 *
 * @package Izabel
 */

/**
 * Add featured content options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function izabel_feat_cont_options( $wp_customize ) {
	// Add note to ECT Featured Content Section
    izabel_register_option( $wp_customize, array(
            'name'              => 'izabel_feat_cont_jetpack_note',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Izabel_Note_Control',
            'label'             => sprintf( esc_html__( 'For all Featured Content Options for Izabel Theme, go %1$shere%2$s', 'izabel' ),
                '<a href="javascript:wp.customize.section( \'izabel_feat_cont\' ).focus();">',
                 '</a>'
            ),
           'section'            => 'feat_cont',
            'type'              => 'description',
            'priority'          => 1,
        )
    );

    $wp_customize->add_section( 'izabel_feat_cont', array(
			'title' => esc_html__( 'Featured Content', 'izabel' ),
			'panel' => 'izabel_theme_options',
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

	// Add note to ECT Featured Content Section
    izabel_register_option( $wp_customize, array(
            'name'              => 'izabel_featured_content_etc_note',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Izabel_Note_Control',
            'active_callback'   => 'izabel_is_ect_featured_content_inactive',
            /* translators: 1: <a>/link tag start, 2: </a>/link tag close. */
            'label'             => sprintf( esc_html__( 'For Featured Content, install %1$sEssential Content Types%2$s Plugin with Featured Content Type Enabled', 'izabel' ),
                '<a target="_blank" href="' . esc_url( $install_url ) . '">',
                '</a>'

            ),
           'section'            => 'izabel_feat_cont',
            'type'              => 'description',
            'priority'          => 1,
        )
    );

	// Add color scheme setting and control.
	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_feat_cont_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'izabel_sanitize_select',
			'active_callback'	=> 'izabel_is_ect_featured_content_active',
			'choices'           => izabel_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'izabel' ),
			'section'           => 'izabel_feat_cont',
			'type'              => 'select',
		)
	);

    izabel_register_option( $wp_customize, array(
            'name'              => 'izabel_feat_cont_cpt_note',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Izabel_Note_Control',
            'active_callback'   => 'izabel_is_featured_content_active',
            /* translators: 1: <a>/link tag start, 2: </a>/link tag close. */
			'label'             => sprintf( esc_html__( 'For CPT heading and sub-heading, go %1$shere%2$s', 'izabel' ),
                 '<a href="javascript:wp.customize.control( \'featured_content_title\' ).focus();">',
                 '</a>'
            ),
            'section'           => 'izabel_feat_cont',
            'type'              => 'description',
        )
    );

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_feat_cont_number',
			'default'           => 3,
			'sanitize_callback' => 'izabel_sanitize_number_range',
			'active_callback'   => 'izabel_is_featured_content_active',
			'description'       => esc_html__( 'Save and refresh the page if No. of Featured Content is changed (Max no of items: 20)', 'izabel' ),
			'input_attrs'       => array(
				'style' => 'width: 100px;',
				'min'   => 0,
			),
			'label'             => esc_html__( 'No of Items', 'izabel' ),
			'section'           => 'izabel_feat_cont',
			'type'              => 'number',
		)
	);

	$number = get_theme_mod( 'izabel_feat_cont_number', 3 );

	//loop for featured post content
	for ( $i = 1; $i <= $number ; $i++ ) {
		izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_feat_cont_cpt_' . $i,
			'sanitize_callback' => 'izabel_sanitize_post',
			'active_callback'   => 'izabel_is_featured_content_active',
			'label'             => esc_html__( 'Featured Content', 'izabel' ) . ' ' . $i ,
			'section'           => 'izabel_feat_cont',
			'type'              => 'select',
            'choices'           => izabel_generate_post_array( 'featured-content' ),
			)
		);

	} // End for().
}
add_action( 'customize_register', 'izabel_feat_cont_options', 10 );

/** Active Callback Functions **/
if( ! function_exists( 'izabel_is_featured_content_active' ) ) :
	/**
	* Return true if featured content is active
	*
	* @since Izabel 1.0
	*/
	function izabel_is_featured_content_active( $control ) {
		$enable = $control->manager->get_setting( 'izabel_feat_cont_option' )->value();

		//return true only if previwed page on customizer matches the type option selected
		return ( izabel_is_ect_featured_content_active( $control ) &&  izabel_check_section( $enable ) );
	}
endif;

if ( ! function_exists( 'izabel_is_ect_featured_content_active' ) ) :
    /**
    * Return true if featured_content is active
    *
    * @since Izabel 1.0
    */
    function izabel_is_ect_featured_content_active( $control ) {
        return ( class_exists( 'Essential_Content_Featured_Content' ) || class_exists( 'Essential_Content_Pro_Featured_Content' ) );
    }
endif;

if ( ! function_exists( 'izabel_is_ect_featured_content_inactive' ) ) :
    /**
    * Return true if featured_content is active
    *
    * @since Izabel 1.0
    */
    function izabel_is_ect_featured_content_inactive( $control ) {
        return ! ( class_exists( 'Essential_Content_Featured_Content' ) || class_exists( 'Essential_Content_Pro_Featured_Content' ) );
    }
endif;


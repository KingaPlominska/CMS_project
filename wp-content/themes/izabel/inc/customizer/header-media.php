<?php
/**
 * Header Media Options
 *
 * @package Izabel
 */

function izabel_header_media_options( $wp_customize ) {
	$wp_customize->get_section( 'header_image' )->description = esc_html__( 'If you add video, it will only show up on Homepage/FrontPage. Other Pages will use Header/Post/Page Image depending on your selection of option. Header Image will be used as a fallback while the video loads ', 'izabel' );

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_header_media_option',
			'default'           => 'entire-site-page-post',
			'sanitize_callback' => 'izabel_sanitize_select',
			'choices'           => array(
				'homepage'               => esc_html__( 'Homepage / Frontpage', 'izabel' ),
				'exclude-home'           => esc_html__( 'Excluding Homepage', 'izabel' ),
				'exclude-home-page-post' => esc_html__( 'Excluding Homepage, Page/Post Featured Image', 'izabel' ),
				'entire-site'            => esc_html__( 'Entire Site', 'izabel' ),
				'entire-site-page-post'  => esc_html__( 'Entire Site, Page/Post Featured Image', 'izabel' ),
				'pages-posts'            => esc_html__( 'Pages and Posts', 'izabel' ),
				'disable'                => esc_html__( 'Disabled', 'izabel' ),
			),
			'label'             => esc_html__( 'Enable on ', 'izabel' ),
			'section'           => 'header_image',
			'type'              => 'select',
			'priority'          => 1,
		)
	);

	/*Overlay Option for Header Media*/
	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_header_media_image_opacity',
			'default'           => '0',
			'sanitize_callback' => 'izabel_sanitize_number_range',
			'label'             => esc_html__( 'Header Media Overlay', 'izabel' ),
			'section'           => 'header_image',
			'type'              => 'number',
			'input_attrs'       => array(
				'style' => 'width: 60px;',
				'min'   => 0,
				'max'   => 100,
			),
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              		=> 'izabel_header_media_content_position',
			'default'           		=> 'content-center-top',
			'sanitize_callback' 		=> 'izabel_sanitize_select',
			'choices'           		=> array(
				'content-center' 		=> esc_html__( 'Center', 'izabel' ),
				'content-right'  		=> esc_html__( 'Right', 'izabel' ),
				'content-left'   		=> esc_html__( 'Left', 'izabel' ),
				'content-center-top'   	=> esc_html__( 'Center Top', 'izabel' ),
				'content-center-bottom' => esc_html__( 'Center Bottom', 'izabel' ),
			),
			'label'             => esc_html__( 'Content Position', 'izabel' ),
			'section'           => 'header_image',
			'type'              => 'select',
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_header_media_text_alignment',
			'default'           => 'text-aligned-center',
			'sanitize_callback' => 'izabel_sanitize_select',
			'choices'           => array(
				'text-aligned-right'  => esc_html__( 'Right', 'izabel' ),
				'text-aligned-center' => esc_html__( 'Center', 'izabel' ),
				'text-aligned-left'   => esc_html__( 'Left', 'izabel' ),
			),
			'label'             => esc_html__( 'Text Alignment', 'izabel' ),
			'section'           => 'header_image',
			'type'              => 'select',
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_header_media_sub_title',
			'default'           => esc_html__( 'New Arrival', 'izabel' ),
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Header Media Sub Title', 'izabel' ),
			'section'           => 'header_image',
			'type'              => 'text',
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_header_media_title',
			'default'           => esc_html__( 'Furniture Store', 'izabel' ),
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Header Media Title', 'izabel' ),
			'section'           => 'header_image',
			'type'              => 'text',
		)
	);

    izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_header_media_text',
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Header Media Text', 'izabel' ),
			'section'           => 'header_image',
			'type'              => 'textarea',
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_header_media_url',
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
			'label'             => esc_html__( 'Header Media Url', 'izabel' ),
			'section'           => 'header_image',
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_header_media_url_text',
			'default'           => esc_html__( 'View Details', 'izabel' ),
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Header Media Url Text', 'izabel' ),
			'section'           => 'header_image',
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_header_url_target',
			'sanitize_callback' => 'izabel_sanitize_checkbox',
			'label'             => esc_html__( 'Open Link in New Window/Tab', 'izabel' ),
			'section'           => 'header_image',
			'custom_control'    => 'Izabel_Toggle_Control',
		)
	);
}
add_action( 'customize_register', 'izabel_header_media_options' );


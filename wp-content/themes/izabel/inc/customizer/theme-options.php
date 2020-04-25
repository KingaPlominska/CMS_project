<?php
/**
 * Theme Options
 *
 * @package Izabel
 */

/**
 * Add theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function izabel_theme_options( $wp_customize ) {
	$wp_customize->add_panel( 'izabel_theme_options', array(
		'title'    => esc_html__( 'Theme Options', 'izabel' ),
		'priority' => 130,
	) );

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_latest_posts_title',
			'default'           => esc_html__( 'News', 'izabel' ),
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Latest Posts Title', 'izabel' ),
			'section'           => 'izabel_theme_options',
		)
	);

	// Layout Options
	$wp_customize->add_section( 'izabel_layout_options', array(
		'title' => esc_html__( 'Layout Options', 'izabel' ),
		'panel' => 'izabel_theme_options',
		)
	);

	/* Default Layout */
	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_default_layout',
			'default'           => 'right-sidebar',
			'sanitize_callback' => 'izabel_sanitize_select',
			'label'             => esc_html__( 'Default Layout', 'izabel' ),
			'section'           => 'izabel_layout_options',
			'type'              => 'select',
			'choices'           => array(
				'right-sidebar'         => esc_html__( 'Right Sidebar ( Content, Primary Sidebar )', 'izabel' ),
				'no-sidebar-full-width' => esc_html__( 'No Sidebar: Full Width', 'izabel' ),
			),
		)
	);

	/* Homepage Layout */
	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_homepage_layout',
			'default'           => 'no-sidebar-full-width',
			'sanitize_callback' => 'izabel_sanitize_select',
			'label'             => esc_html__( 'Homepage Layout', 'izabel' ),
			'section'           => 'izabel_layout_options',
			'type'              => 'select',
			'choices'           => array(
				'right-sidebar'         => esc_html__( 'Right Sidebar ( Content, Primary Sidebar )', 'izabel' ),
				'no-sidebar-full-width' => esc_html__( 'No Sidebar: Full Width', 'izabel' ),
			),
		)
	);
	
	/* Single Page/Post Image Layout */
	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_single_layout',
			'default'           => 'disabled',
			'sanitize_callback' => 'izabel_sanitize_select',
			'label'             => esc_html__( 'Single Page/Post Image Layout', 'izabel' ),
			'section'           => 'izabel_layout_options',
			'type'              => 'select',
			'choices'           => array(
				'disabled'           => esc_html__( 'Disabled', 'izabel' ),
				'post-thumbnail'     => esc_html__( 'Post Thumbnail', 'izabel' ),
			),
		)
	);

	// Excerpt Options.
	$wp_customize->add_section( 'izabel_excerpt_options', array(
		'panel'     => 'izabel_theme_options',
		'title'     => esc_html__( 'Excerpt Options', 'izabel' ),
	) );

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_excerpt_length',
			'default'           => '20',
			'sanitize_callback' => 'absint',
			'description' => esc_html__( 'Excerpt length. Default is 20 words', 'izabel' ),
			'input_attrs' => array(
				'min'   => 10,
				'max'   => 200,
				'step'  => 5,
				'style' => 'width: 60px;',
			),
			'label'    => esc_html__( 'Excerpt Length (words)', 'izabel' ),
			'section'  => 'izabel_excerpt_options',
			'type'     => 'number',
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_excerpt_more_text',
			'default'           => esc_html__( 'Continue Reading', 'izabel' ),
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Read More Text', 'izabel' ),
			'section'           => 'izabel_excerpt_options',
			'type'              => 'text',
		)
	);

	// Excerpt Options.
	$wp_customize->add_section( 'izabel_search_options', array(
		'panel'     => 'izabel_theme_options',
		'title'     => esc_html__( 'Search Options', 'izabel' ),
	) );

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_search_text',
			'default'           => esc_html__( 'Search', 'izabel' ),
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Search Text', 'izabel' ),
			'section'           => 'izabel_search_options',
			'type'              => 'text',
		)
	);

	// Homepage / Frontpage Options.
	$wp_customize->add_section( 'izabel_homepage_options', array(
		'description' => esc_html__( 'Only posts that belong to the categories selected here will be displayed on the front page', 'izabel' ),
		'panel'       => 'izabel_theme_options',
		'title'       => esc_html__( 'Homepage / Frontpage Options', 'izabel' ),
	) );

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_front_page_category',
			'sanitize_callback' => 'izabel_sanitize_category_list',
			'custom_control'    => 'Izabel_Multi_Categories_Control',
			'label'             => esc_html__( 'Categories', 'izabel' ),
			'section'           => 'izabel_homepage_options',
			'type'              => 'dropdown-categories',
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_recent_posts_heading',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => esc_html__( 'From Our Blog', 'izabel' ),
			'label'             => esc_html__( 'Recent Posts Heading', 'izabel' ),
			'section'           => 'izabel_homepage_options',
		)
	);

	// Pagination Options.
	$pagination_type = get_theme_mod( 'izabel_pagination_type', 'default' );

	$nav_desc = '';

	/**
	* Check if navigation type is Jetpack Infinite Scroll and if it is enabled
	*/
	$nav_desc = sprintf(
		wp_kses(
			__( 'For infinite scrolling, use %1$sCatch Infinite Scroll Plugin%2$s with Infinite Scroll module Enabled.', 'izabel' ),
			array(
				'a' => array(
					'href' => array(),
					'target' => array(),
				),
				'br'=> array()
			)
		),
		'<a target="_blank" href="https://wordpress.org/plugins/catch-infinite-scroll/">',
		'</a>'
	);

	$wp_customize->add_section( 'izabel_pagination_options', array(
		'description' => $nav_desc,
		'panel'       => 'izabel_theme_options',
		'title'       => esc_html__( 'Pagination Options', 'izabel' ),
	) );

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_pagination_type',
			'default'           => 'default',
			'sanitize_callback' => 'izabel_sanitize_select',
			'choices'           => izabel_get_pagination_types(),
			'label'             => esc_html__( 'Pagination type', 'izabel' ),
			'section'           => 'izabel_pagination_options',
			'type'              => 'select',
		)
	);

	// For WooCommerce layout: izabel_woocommerce_layout, check woocommerce-options.php.
	/* Scrollup Options */
	$wp_customize->add_section( 'izabel_scrollup', array(
		'panel'    => 'izabel_theme_options',
		'title'    => esc_html__( 'Scrollup Options', 'izabel' ),
	) );

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_display_scrollup',
			'default'			=> 1,
			'sanitize_callback' => 'izabel_sanitize_checkbox',
			'label'             => esc_html__( 'Scroll Up', 'izabel' ),
			'section'           => 'izabel_scrollup',
			'custom_control'    => 'Izabel_Toggle_Control',
		)
	);
}
add_action( 'customize_register', 'izabel_theme_options' );

/** Active Callback Functions */
if( ! function_exists( 'izabel_is_header_right_cart_enable' ) ) :
	/**
	* Return true if primary menu's cart icon enabled
	*
	* @since Izabel 1.0
	*/
	function izabel_is_header_right_cart_enable( $control ) {
		return $control->manager->get_setting( 'izabel_header_right_cart_enable' )->value();
	}
endif;

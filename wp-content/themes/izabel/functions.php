<?php
/**
 * Izabel functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package Izabel
 */

/**
 * Izabel only works in WordPress 4.4 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	require trailingslashit( get_template_directory() ) . 'inc/back-compat.php';
}

if ( ! function_exists( 'izabel_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Create your own izabel_setup() function to override in a child theme.
 *
 * @since Izabel 1.0
 */
function izabel_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/izabel
	 * If you're building a theme based on Izabel, use a find and replace
	 * to change 'izabel' to the name of your theme in all the template files
	 */
	load_theme_textdomain('izabel', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for custom logo.
	 *
	 *  @since Izabel 1.0
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 100,
		'flex-height' => true,
		'flex-width' => true,
	) );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// Used in excerpt image Top 3:2 Ratio
	set_post_thumbnail_size( 640, 427, true );

	// Used ins Slider, Promotion Headline and Header Media
	add_image_size( 'izabel-slider', 1920, 1080, true );

	// Used in Hero Content, 4:3 ratio
	add_image_size( 'izabel-hero-content', 730, 547, true );

	// Used in Portfolio Section 4:5 ratio
	add_image_size( 'izabel-featured', 640, 800, true );

	// Used in Collection Content Large for layout-three 16:9 ratio
	add_image_size( 'izabel-collection-large-layout-three', 880, 495, true );

	// Used in Collection Content Large for layout-two 16:9 ratio
	add_image_size( 'izabel-collection-large-layout-two', 920, 518, true );

	// Used in Portfolio 1:1 ratio & Collection Content Small
	add_image_size( 'izabel-portfolio', 640, 640, true );

	// Used in team, 1:1 ratio
	add_image_size( 'izabel-team', 240, 240, true );

	// Used in testimonial, 1:1 ratio
	add_image_size( 'izabel-testimonial', 110, 110, true );

	// Used in services, 1:1 ratio
	add_image_size( 'izabel-service', 65, 65, true );

	// Used in Logo Slider, 21:9 ratio
	add_image_size( 'izabel-logo', 180, 77, true );

	// Used in blog, 3:2 ratio
	add_image_size( 'izabel-blog', 960, 640, true );


	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'menu-1'                => esc_html__( 'Primary Menu', 'izabel' ),
		'social-header-left'    => esc_html__( 'Social Menu at Left Side Above Primary Menu', 'izabel' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat',
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'assets/css/editor-style.css', izabel_fonts_url() ) );

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Add custom editor font sizes.
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name'      => esc_html__( 'Small', 'izabel' ),
				'shortName' => esc_html__( 'S', 'izabel' ),
				'size'      => 14,
				'slug'      => 'small',
			),
			array(
				'name'      => esc_html__( 'Normal', 'izabel' ),
				'shortName' => esc_html__( 'M', 'izabel' ),
				'size'      => 18,
				'slug'      => 'normal',
			),
			array(
				'name'      => esc_html__( 'Large', 'izabel' ),
				'shortName' => esc_html__( 'L', 'izabel' ),
				'size'      => 42,
				'slug'      => 'large',
			),
			array(
				'name'      => esc_html__( 'Huge', 'izabel' ),
				'shortName' => esc_html__( 'XL', 'izabel' ),
				'size'      => 62,
				'slug'      => 'huge',
			),
		)
	);

	// Add support for custom color scheme.
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => esc_html__( 'White', 'izabel' ),
			'slug'  => 'white',
			'color' => '#ffffff',
		),
		array(
			'name'  => esc_html__( 'Black', 'izabel' ),
			'slug'  => 'black',
			'color' => '#111111',
		),
		array(
			'name'  => esc_html__( 'Gray', 'izabel' ),
			'slug'  => 'gray',
			'color' => '#999999',
		),
		array(
			'name'  => esc_html__( 'yellow', 'izabel' ),
			'slug'  => 'yellow',
			'color' => '#f1c970',
		),
	) );

	/**
	 * Adds support for Catch Breadcrumb.
	 */
	add_theme_support( 'catch-breadcrumb', array(
		'content_selector' => '.header-media .entry-title',
	) );
}
endif; // izabel_setup
add_action( 'after_setup_theme', 'izabel_setup' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since Izabel 1.0
 */
function izabel_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'izabel_content_width', 990 );
}
add_action( 'after_setup_theme', 'izabel_content_width', 0 );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet for different value other than the default one
 *
 * @global int $content_width
 */
function izabel_template_redirect() {
    $layout = izabel_get_theme_layout();

    if ( 'no-sidebar-full-width' == $layout ) {
		$GLOBALS['content_width'] = 1510;
	}
}
add_action( 'template_redirect', 'izabel_template_redirect' );

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Izabel 1.0
 */
function izabel_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'izabel' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'izabel' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 1', 'izabel' ),
		'id'            => 'sidebar-2',
		'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'izabel' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 2', 'izabel' ),
		'id'            => 'sidebar-3',
		'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'izabel' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 3', 'izabel' ),
		'id'            => 'sidebar-4',
		'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'izabel' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	//Instagram Widget
	if ( class_exists( 'Catch_Instagram_Feed_Gallery_Widget' ) ||  class_exists( 'Catch_Instagram_Feed_Gallery_Widget_Pro' ) ) {
		register_sidebar( array(
			'name'          => esc_html__( 'Instagram', 'izabel' ),
			'id'            => 'sidebar-instagram',
			'description'   => esc_html__( 'Appears above footer. This sidebar is only for Widget from plugin Catch Instagram Feed Gallery Widget and Catch Instagram Feed Gallery Widget Pro', 'izabel' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}
}
add_action( 'widgets_init', 'izabel_widgets_init' );

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 *
 * @since Izabel 1.0
 */
function izabel_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-2' ) ) {
		$count++;
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		$count++;
	}

	if ( is_active_sidebar( 'sidebar-4' ) ) {
		$count++;
	}

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="widget-area footer-widget-area ' . $class . '"'; // WPCS: XSS OK.
}

if ( ! function_exists( 'izabel_fonts_url' ) ) :
/**
 * Register Google fonts for Izabel
 *
 * Create your own izabel_fonts_url() function to override in a child theme.
 *
 * @since Izabel 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function izabel_fonts_url() {
	$fonts_url = '';

		/* Translators: If there are characters in your language that are not
		* supported by PT Sans, translate this to 'off'. Do not translate
		* into your own language.
		*/
		$pt_sans = _x( 'on', 'PT Sans: on or off', 'izabel' );

		/* Translators: If there are characters in your language that are not
		* supported by PT Serif, translate this to 'off'. Do not translate
		* into your own language.
		*/
		$josefin_sans = _x( 'on', 'Josefin Sans: on or off', 'izabel' );

		if ( 'off' !== $pt_sans || 'off' !== $josefin_sans ) {
			$font_families = array();

			if ( 'off' !== $pt_sans ) {
			$font_families[] = 'PT Sans:300,400,600,700,900';
			}

			if ( 'off' !== $josefin_sans ) {
			$font_families[] = 'Josefin Sans:300,400,600,700,900';
			}

			$query_args = array(
				'family' => urlencode( implode( '|', $font_families ) ),
				'subset' => urlencode( 'latin,latin-ext' ),
			);

			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
		}

		return esc_url_raw( $fonts_url );
}
endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Izabel 1.0
 */
function izabel_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'izabel_javascript_detection', 0 );

/**
 * Enqueues scripts and styles.
 *
 * @since Izabel 1.0
 */
function izabel_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'izabel-fonts', izabel_fonts_url(), array(), null );

	// Theme stylesheet.
	wp_enqueue_style( 'izabel-style', get_stylesheet_uri() );

	// Theme block stylesheet.
	wp_enqueue_style( 'izabel-block-style', get_theme_file_uri( '/assets/css/blocks.css' ), array( 'izabel-style' ), '1.0' );

	// Load the html5 shiv.
	wp_enqueue_script( 'izabel-html5', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/html5.min.js', array(), '3.7.3' );
	wp_script_add_data( 'izabel-html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'izabel-skip-link-focus-fix', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/skip-link-focus-fix.min.js', array(), '20160816', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'izabel-keyboard-image-navigation', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/keyboard-image-navigation.min.js', array( 'jquery' ), '20160816' );
	}

 	wp_register_script( 'jquery-match-height', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/jquery.matchHeight.min.js', array( 'jquery' ), '20151215', true );

 	$deps[] = 'jquery';

 	$deps[] = 'jquery-match-height';

 	$enable_portfolio = izabel_check_section( get_theme_mod( 'izabel_portfolio_option', 'disabled' ) );

	wp_enqueue_script( 'izabel-script', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/functions.min.js', $deps, '20160816', true );

	wp_localize_script( 'izabel-script', 'izabelScreenReaderText', array(
		'expand'   => esc_html__( 'expand child menu', 'izabel' ),
		'collapse' => esc_html__( 'collapse child menu', 'izabel' ),
		'icon'     => izabel_get_svg( array(
			'icon'     => 'angle-down',
			'fallback' => true,
		) ),
	) );

	//Slider Scripts
	$enable_slider = get_theme_mod( 'izabel_slider_option', 'disabled' );
	$enable_testimonial = get_theme_mod( 'izabel_testimonial_option', 'disabled' );

	if ( izabel_check_section( $enable_slider ) || izabel_check_section( $enable_testimonial ) ) {
		wp_enqueue_script( 'jquery-cycle2', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/jquery.cycle/jquery.cycle2.min.js', array( 'jquery' ), '2.1.5', true );

		$transition_effects = array(
			get_theme_mod( 'izabel_slider_transition_effect', 'fade' ),
		);
	}

	// Enqueue fitvid if JetPack is not installed.
	if ( ! class_exists( 'Jetpack' ) ) {
		wp_enqueue_script( 'jquery-fitvids', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/fitvids.min.js', array( 'jquery' ), '1.1', true );
	}
}
add_action( 'wp_enqueue_scripts', 'izabel_scripts' );

/**
 * Enqueue editor styles for Gutenberg
 *
 * @since Izabel 1.0
 */
function izabel_block_editor_styles() {
	// Block styles.
	wp_enqueue_style( 'izabel-block-editor-style', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/css/editor-blocks.css' );

	// Add custom fonts.
	wp_enqueue_style( 'izabel-fonts', izabel_fonts_url(), array(), null );
}
add_action( 'enqueue_block_editor_assets', 'izabel_block_editor_styles' );

/**
 * Custom template tags for this theme.
 */
require get_parent_theme_file_path( 'inc/template-tags.php' );

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Include Header Background Color Options
 */
require get_parent_theme_file_path( 'inc/header-background-color.php' );

/**
 * SVG icons functions and filters.
 */
require get_parent_theme_file_path( 'inc/icon-functions.php' );

/**
 * Custom functions that act independently of the theme templates.
 */
require get_parent_theme_file_path( 'inc/extras.php' );

/**
 * Add functions for service.
 */
require get_parent_theme_file_path( 'inc/service.php' );

/**
 * Add functions for header media.
 */
require get_parent_theme_file_path( 'inc/custom-header.php' );

/**
 * Load Jetpack compatibility file.
 */
require get_parent_theme_file_path( 'inc/jetpack.php' );

/**
 * Include Slider
 */
require get_parent_theme_file_path( '/inc/slider.php' );

/**
 * Add Metaboxes
 */
require get_parent_theme_file_path( 'inc/metabox/metabox.php' );

/**
 * Include Widgets
 */
require get_parent_theme_file_path( '/inc/widgets/social-icons.php' );

/**
 * Load WooCommerce compatibility file.
 */
require get_parent_theme_file_path( 'inc/woocommerce.php' );

/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @since Izabel 1.0
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array A new modified arguments.
 */
function izabel_widget_tag_cloud_args( $args ) {
	$args['largest'] = 1;
	$args['smallest'] = 1;
	$args['unit'] = 'em';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'izabel_widget_tag_cloud_args' );

/**
 * Load Theme About page
 */
require get_parent_theme_file_path( '/inc/about.php' );

/**
 * Load TGMPA
 */
require get_template_directory() . '/inc/class-tgm-plugin-activation.php';



/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function izabel_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		// Catch Web Tools.
		array(
			'name' => 'Catch Web Tools', // Plugin Name, translation not required.
			'slug' => 'catch-web-tools',
		),
		// Catch IDs
		array(
			'name' => 'Catch IDs', // Plugin Name, translation not required.
			'slug' => 'catch-ids',
		),
		// To Top.
		array(
			'name' => 'To top', // Plugin Name, translation not required.
			'slug' => 'to-top',
		),
		// Catch Gallery.
		array(
			'name' => 'Catch Gallery', // Plugin Name, translation not required.
			'slug' => 'catch-gallery',
		),
	);

	if ( ! class_exists( 'Catch_Infinite_Scroll_Pro' ) ) {
		$plugins[] = array(
			'name' => 'Catch Infinite Scroll', // Plugin Name, translation not required.
			'slug' => 'catch-infinite-scroll',
		);
	}

	if ( ! class_exists( 'Essential_Content_Types_Pro' ) ) {
		$plugins[] = array(
			'name' => 'Essential Content Types', // Plugin Name, translation not required.
			'slug' => 'essential-content-types',
		);
	}

	if ( ! class_exists( 'Essential_Widgets_Pro' ) ) {
		$plugins[] = array(
			'name' => 'Essential Widgets', // Plugin Name, translation not required.
			'slug' => 'essential-widgets',
		);
	}

	if ( ! class_exists( 'Catch_Instagram_Feed_Gallery_Widget_Pro' ) ) {
		$plugins[] = array(
			'name' => 'Catch Instagram Feed Gallery & Widget', // Plugin Name, translation not required.
			'slug' => 'catch-instagram-feed-gallery-widget',
		);
	}

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'izabel',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'izabel_register_required_plugins' );

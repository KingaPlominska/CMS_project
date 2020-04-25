<?php
/**
 * Adding support for WooCommerce Plugin
 */

if ( ! class_exists( 'WooCommerce' ) ) {
	// Bail if WooCommerce is not installed
	return;
}


if ( ! function_exists( 'izabel_woocommerce_setup' ) ) :
	/**
	 * Sets up support for various WooCommerce features.
	 */
	function izabel_woocommerce_setup() {
		add_theme_support( 'woocommerce', array(
			'thumbnail_image_width'  => 640,
			'thumbnail_image_height' => 800,
		) );
	}

	if ( get_theme_mod( 'izabel_product_gallery_zoom', 1 ) ) {
		add_theme_support('wc-product-gallery-zoom');
	}

	if ( get_theme_mod( 'izabel_product_gallery_lightbox', 1 ) ) {
		add_theme_support('wc-product-gallery-lightbox');
	}

	if (get_theme_mod( 'izabel_product_gallery_slider', 1 ) ) {
		add_theme_support('wc-product-gallery-slider');
	}

endif; //catch-izabel_woocommerce_setup
add_action( 'after_setup_theme', 'izabel_woocommerce_setup' );


/**
 * Add WooCommerce Options to customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function izabel_woocommerce_options( $wp_customize ) {

	// WooCommerce Options
	$wp_customize->add_section( 'izabel_woocommerce_options', array(
		'title'       => esc_html__( 'WooCommerce Options', 'izabel' ),
		'panel'       => 'izabel_theme_options',
		'description' => esc_html__( 'Since these options are added via theme support, you will need to save and refresh the customizer to view the full effect.', 'izabel' ),
	) );

	izabel_register_option( $wp_customize, array(
	        'name'              => 'izabel_shop_subtitle',
	        'sanitize_callback' => 'wp_kses_post',
	        'label'             => esc_html__( 'Shop Page Subtitle', 'izabel' ),
	        'default'           => esc_html__( 'This is where you can add new products to your store.', 'izabel' ),
	        'section'           => 'izabel_woocommerce_options',
	        'type'              => 'textarea',
	    )
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_product_gallery_zoom',
			'default'           => 1,
			'sanitize_callback' => 'izabel_sanitize_checkbox',
			'label'             => esc_html__( 'Product Gallery Zoom', 'izabel' ),
			'section'           => 'izabel_woocommerce_options',
			'custom_control'    => 'Izabel_Toggle_Control',
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_product_gallery_lightbox',
			'default'           => 1,
			'sanitize_callback' => 'izabel_sanitize_checkbox',
			'label'             => esc_html__( 'Product Gallery Lightbox', 'izabel' ),
			'section'           => 'izabel_woocommerce_options',
			'custom_control'    => 'Izabel_Toggle_Control',
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_product_gallery_slider',
			'default'           => 1,
			'sanitize_callback' => 'izabel_sanitize_checkbox',
			'label'             => esc_html__( 'Product Gallery Slider', 'izabel' ),
			'section'           => 'izabel_woocommerce_options',
			'custom_control'    => 'Izabel_Toggle_Control',
		)
	);
	
		// WooCommerce Excerpt Options.
	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_woo_excerpt_length',
			'default'           => '10',
			'sanitize_callback' => 'absint',
			'input_attrs' => array(
				'min'   => 5,
				'max'   => 200,
				'step'  => 5,
				'style' => 'width: 60px;',
			),
			'label'    => esc_html__( 'WooCommerce Product Excerpt Length (words)', 'izabel' ),
			'section'  => 'izabel_excerpt_options',
			'type'     => 'number',
		)
	);

	izabel_register_option( $wp_customize, array(
			'name'              => 'izabel_woo_excerpt_more_text',
			'default'           => esc_html__( 'Buy Now', 'izabel' ),
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'WooCommerce Product Read More Text', 'izabel' ),
			'section'           => 'izabel_excerpt_options',
			'type'              => 'text',
		)
	);
}
add_action( 'customize_register', 'izabel_woocommerce_options' );

/**
 * Make Shop Page Sub-title dynamic
 */
function izabel_woocommerce_shop_title( $args ) {
    if ( is_shop() ) {
        return wp_kses_post( get_theme_mod( 'izabel_shop_title', esc_html__( 'Archive: Products', 'izabel' ) ) );
    }
    
    return $args;
}
add_filter( 'get_the_archive_title', 'izabel_woocommerce_shop_title', 20 );

/**
 * Make Shop Page Title dynamic
 */
function izabel_woocommerce_shop_subtitle( $args ) {
    if ( is_shop() ) {
        return wp_kses_post( get_theme_mod( 'izabel_shop_subtitle', esc_html__( 'This is where you can add new products to your store.', 'izabel' ) ) );
    }

    return $args;
}
add_filter( 'get_the_archive_description', 'izabel_woocommerce_shop_subtitle', 20 );

function izabel_woocommerce_hide_page_title() { 
    if ( is_shop() && izabel_has_header_media_text() ) {
        return false;
    }

    return true;  
}
add_filter( 'woocommerce_show_page_title', 'izabel_woocommerce_hide_page_title' ); 


/**
 * uses remove_action to remove the WooCommerce Wrapper and add_action to add Main Wrapper
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );


if ( ! function_exists( 'izabel_woocommerce_start' ) ) :
	function izabel_woocommerce_start() {
		echo '<div id="primary" class="content-area"><main role="main" class="site-main woocommerce" id="main"><div class="woocommerce-posts-wrapper">';
	}
endif; //catch-izabel_woocommerce_start
add_action( 'woocommerce_before_main_content', 'izabel_woocommerce_start', 15 );


if ( ! function_exists( 'izabel_woocommerce_end' ) ) :
	function izabel_woocommerce_end() {
		echo '</div><!-- .woocommerce-posts-wrapper --></main><!-- #main --></div><!-- #primary -->';
	}
endif; //catch-izabel_woocommerce_end
add_action( 'woocommerce_after_main_content', 'izabel_woocommerce_end', 15 );


function izabel_woocommerce_shorting_start() {
	echo '<div class="woocommerce-shorting-wrapper">';
}
add_action( 'woocommerce_before_shop_loop', 'izabel_woocommerce_shorting_start', 10 );


function izabel_woocommerce_shorting_end() {
	echo '</div><!-- .woocommerce-shorting-wrapper -->';
}
add_action( 'woocommerce_before_shop_loop', 'izabel_woocommerce_shorting_end', 40 );


function izabel_woocommerce_product_container_start() {
	echo '<div class="product-container">';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'izabel_woocommerce_product_container_start', 20 );


function izabel_woocommerce_product_container_end() {
	echo '</div><!-- .product-container -->';
}
add_action( 'woocommerce_after_shop_loop_item', 'izabel_woocommerce_product_container_end', 20 );

if ( ! function_exists( 'izabel_my_account_icon_link' ) ) {
	/**
	 * The account callback function
	 */
	function izabel_my_account_icon_link( $label ) {

		$label_html = '';

		$label_title = esc_html__( 'My Account', 'izabel' );

		if ( $label ) {
			$label_html = '<span class="my-account-label">' . esc_html( $label ) . '</span>';
			$label_title = $label;
		}
		echo '<a class="my-account" href="' . esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) . '" title="' . esc_attr( $label_title ) . '">' . izabel_get_svg( array( 'icon' => 'user' ) ) . $label_html . '</a>';
	}
}

if ( ! function_exists( 'izabel_cart_link' ) ) {
	/**
	 * Cart Link
	 * Displayed a link to the cart including the number of items present and the cart total
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function izabel_cart_link( $items = true, $amount = true ) {
		?>
			<a class="site-cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'izabel' ); ?>">
				<?php echo izabel_get_svg( array( 'icon' => 'shopping-bag', 'title' => esc_html__( 'View your shopping cart', 'izabel' ) ) ); ?>
				<?php if ( $items ) : ?>
				<?php /* translators: number of items in the mini cart. */ ?>
				<span class="count"><?php echo wp_kses_data( sprintf( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'izabel' ), WC()->cart->get_cart_contents_count() ) );?></span>
				<?php endif; ?>

				<?php if ( $items && $amount ) : ?>
				<span class="sep"> / </span>
				<?php endif; ?>

				<?php if ( $amount ) : ?>
				<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
				<?php endif; ?>
			</a>
		<?php
	}
}

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function izabel_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'izabel_woocommerce_active_body_class' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function izabel_woocommerce_scripts() {
	$font_path   = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style( 'izabel-style', $inline_font );
}
add_action( 'wp_enqueue_scripts', 'izabel_woocommerce_scripts' );


if ( ! function_exists( 'izabel_woocommerce_product_columns_wrapper' ) ) {
	/**
	 * Product columns wrapper.
	 *
	 * @return  void
	 */
	function izabel_woocommerce_product_columns_wrapper() {
		// Get option from Customizer=> WooCommerce=> Product Catlog=> Products per row.
		echo '<div class="columns-' . absint( get_option( 'woocommerce_catalog_columns', 3 ) ) . '">';
	}
}
add_action( 'woocommerce_before_shop_loop', 'izabel_woocommerce_product_columns_wrapper', 40 );

if ( ! function_exists( 'izabel_woocommerce_product_columns_wrapper_close' ) ) {
	/**
	 * Product columns wrapper close.
	 *
	 * @return  void
	 */
	function izabel_woocommerce_product_columns_wrapper_close() {
		echo '</div>';
	}
}
add_action( 'woocommerce_after_shop_loop', 'izabel_woocommerce_product_columns_wrapper_close', 40 );

if ( ! function_exists( 'izabel_remove_default_woo_store_notice' ) ) {
	/**
	 * Remove default Store Notice from footer, added in header.php
	 *
	 * @return  void
	 */
	function izabel_remove_default_woo_store_notice() {
		remove_action( 'wp_footer', 'woocommerce_demo_store' );
	}
}
add_action( 'after_setup_theme', 'izabel_remove_default_woo_store_notice', 40 );

if ( ! function_exists( 'izabel_header_mini_cart_refresh_number' ) ) {
	/**
	 * Update Header Cart items number on add to cart
	 */
	function izabel_header_mini_cart_refresh_number( $fragments ){
	    ob_start();
	    ?>
	    <span class="count"><?php echo wp_kses_data( sprintf( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'izabel' ), WC()->cart->get_cart_contents_count() ) );?></span>
	    <?php
	        $fragments['.site-cart-contents .count'] = ob_get_clean();
	    return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'izabel_header_mini_cart_refresh_number' );

if ( ! function_exists( 'izabel_header_mini_cart_refresh_amount' ) ) {
	/**
	 * Update Header Cart amount on add to cart
	 */
	function izabel_header_mini_cart_refresh_amount( $fragments ){
	    ob_start();
	    ?>
	    <span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
	    <?php
	        $fragments['.site-cart-contents .amount'] = ob_get_clean();
	    return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'izabel_header_mini_cart_refresh_amount' );

<?php
/**
 * WooCommerce Compatibility File
 * See: https://wordpress.org/plugins/woocommerce/
 *
 * @package Izabel 1.0
 */

/**
 * Query WooCommerce activation
 */
if ( ! function_exists( 'izabel_is_woocommerce_activated' ) ) {
	function izabel_is_woocommerce_activated() {
		return class_exists( 'WooCommerce' ) ? true : false;
	}
}

if ( ! function_exists( 'izabel_generate_products_array' ) ) {
	/**
	 * Returns list of products to be used in customizer
	 */
	function izabel_generate_products_array( $post_type = 'product' ) {
		$output = array();
		$posts = get_posts( array(
			'post_type'        => 'product',
			'post_status'      => 'publish',
			'suppress_filters' => false,
			'posts_per_page'   => -1,
			)
		);

		$output['0']= esc_html__( '-- Select --', 'izabel' );

		foreach ( $posts as $post ) {
			/* translators: 1: post id. */
		$output[ $post->ID ] = ! empty( $post->post_title ) ? $post->post_title : sprintf( __( '#%d (no title)', 'izabel' ), $post->ID );
		}

		return $output;

	}
}

if ( ! function_exists( 'izabel_myaccount_icon_link' ) ) {
    /**
     * The account callback function
     */
    function izabel_myaccount_icon_link() {
    	if ( ! izabel_is_woocommerce_activated() ) {
    		// Bail if WooCommerce is not activated.
    		return;
    	}

        echo '<a class="my-account" href="' . esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) . '" title="' . esc_attr__( 'Go to My Account', 'izabel' ) . '"><span class="screen-reader-text">' . esc_attr__( 'My Account', 'izabel' ) . '</span>' . izabel_get_svg( array( 'icon' => 'user' ) ) . '</a>';
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
    function izabel_cart_link() {
    	if ( ! izabel_is_woocommerce_activated() ) {
    		// Bail if WooCommerce is not activated.
    		return;
    	}

        ?>
            <a class="site-cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'izabel' ); ?>">
                <?php echo izabel_get_svg( array( 'icon' => 'shopping-bag', 'title' => esc_html__( 'View your shopping cart', 'izabel' ) ) ); ?>
                <?php /* translators: number of items in the mini cart. */ ?>
                <span class="count"><?php echo wp_kses_data( sprintf( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'izabel' ), WC()->cart->get_cart_contents_count() ) );?></span><span class="sep"> / </span><span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
            </a>
        <?php
    }
}

if ( ! function_exists( 'izabel_header_right_cart_account' ) ) {
    /**
     * Displays header right cart and my accounnt link
     *
     * @return void
     * @since  1.0.0
     */
    function izabel_header_right_cart_account() {
        if ( ! izabel_is_woocommerce_activated() ) {
            // Bail if WooCommerce is not activated.
            return;
        }

        $cartclass = 'menu-inline site-cart current-menu-item';
        //account class
        $accountclass = 'secondary-account-wrapper menu-inline';
        ?>
        <div id="site-header-secondary-cart-wrapper" class="site-header-cart-wrapper">
            <ul class="site-header-cart menu">
                <li class="<?php echo esc_attr( $cartclass ); ?>">
                    <?php izabel_cart_link( false, false ); ?>
                        <ul id="site-cart-contents-items" class="site-cart-contents-items">
                            <?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
                        </ul>
                    </li>
            </ul>
        </div><!-- .site-header-cart-wrapper -->
        <?php
    }
}

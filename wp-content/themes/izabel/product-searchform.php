<?php
/**
 * Template for displaying woocommerce search form
 *
* @package Izabel
 */
?>

<form role="search" method="get" class="woocommerce-product-search search-form" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
	<label class="screen-reader-text" for="s"><?php esc_html_e( 'Search for:', 'izabel' ); ?></label>
	<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search Products&hellip;', 'placeholder', 'izabel' ); ?>" value="<?php the_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'izabel' ); ?>" />
	<button type="submit" class="search-submit">
		<span class="screen-reader-text"><?php echo _x( 'Search Products', 'submit button', 'izabel' ); ?></span>
		<?php echo izabel_get_svg( array( 'icon' => 'search' ) ); ?>
	</button>
	<input type="hidden" name="post_type" value="product" />
</form>

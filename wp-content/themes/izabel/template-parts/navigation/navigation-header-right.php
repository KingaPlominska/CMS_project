<?php
/**
 * Displays Header Right Navigation
 *
 * @package Izabel
 */
?>
	<div id="site-header-right-menu" class="site-secondary-menu">
			<div class="secondary-search-wrapper">
				<div id="search-container-main">
					<button id="search-toggle-main" class="menu-search-main-toggle">
						<?php
						echo izabel_get_svg( array( 'icon' => 'search' ) );
						echo izabel_get_svg( array( 'icon' => 'close' ) );
						echo '<span class="menu-label-prefix">'. esc_attr__( 'Search ', 'izabel' ) . '</span>';
						?>
					</button>

		        	<div class="search-container">
		            	<?php get_search_form(); ?>
		            </div><!-- .search-container -->
				</div><!-- #search-social-container -->
			</div><!-- .secondary-search-wrapper -->
	</div><!-- #site-header-right-menu -->
	
<?php
if ( function_exists( 'izabel_header_right_cart_account' ) ) {
	izabel_header_right_cart_account();
}

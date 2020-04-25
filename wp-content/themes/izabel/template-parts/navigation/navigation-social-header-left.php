<?php
/**
 * Displays Header Top Navigation
 *
 * @package Catch_Store
 */
?>

<?php if ( has_nav_menu( 'social-header-left' ) ) : ?>
	<nav id="social-navigation-left" class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Header Left Social Links Menu', 'izabel' ); ?>">
		<?php
			wp_nav_menu( array(
				'theme_location' => 'social-header-left',
				'menu_class'     => 'social-links-menu',
				'depth'          => 1,
				'link_before'    => '<span class="screen-reader-text">',
				'link_after'     => '</span>' . izabel_get_svg( array( 'icon' => 'chain' ) ),
			) );
		?>
	</nav><!-- #social-secondary-navigation -->
<?php endif; ?>

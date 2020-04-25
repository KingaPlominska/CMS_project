<?php
/**
 * Izabel back compat functionality
 *
 * Prevents Izabel from running on WordPress versions prior to 4.4,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.4.
 *
 * @package Izabel
 */

/**
 * Prevent switching to Izabel on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since Izabel 1.0
 */
function izabel_switch_theme() {
	switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );

	unset( $_GET['activated'] );

	add_action( 'admin_notices', 'izabel_upgrade_notice' );
}
add_action( 'after_switch_theme', 'izabel_switch_theme' );

/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Izabel on WordPress versions prior to 4.4.
 *
 * @since Izabel 1.0
 *
 * @global string $wp_version WordPress version.
 */
function izabel_upgrade_notice() {
	/* translators: %s: current WordPress version. */
	$message = sprintf( __( 'Izabel requires at least WordPress version 4.4. You are running version %s. Please upgrade and try again.', 'izabel' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );// WPCS: XSS ok.
}

/**
 * Prevents the Customizer from being loaded on WordPress versions prior to 4.4.
 *
 * @since Izabel 1.0
 *
 * @global string $wp_version WordPress version.
 */
function izabel_customize() {
	/* translators: %s: current WordPress version. */
	$message = sprintf( __( 'Izabel requires at least WordPress version 4.4. You are running version %s. Please upgrade and try again.', 'izabel' ), $GLOBALS['wp_version'] ); // WPCS: XSS ok.

	wp_die( $message, '', array(
		'back_link' => true,
	) );
}
add_action( 'load-customize.php', 'izabel_customize' );

/**
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 4.4.
 *
 * @since Izabel 1.0
 *
 * @global string $wp_version WordPress version.
 */
function izabel_preview() {
	if ( isset( $_GET['preview'] ) ) {
		/* translators: %s: current WordPress version. */
		wp_die( sprintf( __( 'Izabel requires at least WordPress version 4.4. You are running version %s. Please upgrade and try again.', 'izabel' ), $GLOBALS['wp_version'] ) );// WPCS: XSS ok.
	}
}
add_action( 'template_redirect', 'izabel_preview' );

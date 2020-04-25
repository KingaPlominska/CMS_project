<?php
/**
 * The template used for displaying hero content
 *
 * @package Izabel
 */
?>

<?php
$enable_section = get_theme_mod( 'izabel_hero_cont_visibility', 'disabled' );

if ( ! izabel_check_section( $enable_section ) ) {
	// Bail if hero content is not enabled
	return;
}

get_template_part( 'template-parts/hero-content/post-type', 'hero' );
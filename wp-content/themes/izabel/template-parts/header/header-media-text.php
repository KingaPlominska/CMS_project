<?php
/**
 * Display Header Media Text
 *
 * @package Izabel
 */
?>
<?php

$header_media_sub_title = ( is_singular() && ! is_front_page() ) ? '' : get_theme_mod( 'izabel_header_media_sub_title', esc_html__( 'New Arrival', 'izabel' ) );
$header_media_title = get_theme_mod( 'izabel_header_media_title', esc_html__( 'Furniture Store', 'izabel' ) );
$header_media_text = get_theme_mod( 'izabel_header_media_text' );

if ( '' !== $header_media_sub_title || '' !== $header_media_title || '' !== $header_media_text ) : ?>
<div class="custom-header-content sections header-media-section">

	<?php if ( '' !== $header_media_sub_title ) : ?>
		<div class="sub-title"><span><?php echo wp_kses_post( $header_media_sub_title ); ?></span></div>
	<?php endif; ?>

	<?php if ( '' !== $header_media_title ) : ?>
	<h2 class="entry-title"><?php echo wp_kses_post( $header_media_title ); ?></h2>
	<?php endif; ?>

	<p class="site-header-text"><?php echo wp_kses_post( $header_media_text ); ?>
	<a class="more-link"  href="<?php echo esc_url( get_theme_mod( 'izabel_header_media_url', '#' ) ); ?>" target="<?php echo get_theme_mod( 'izabel_header_url_target' ) ? '_blank' : '_self'; ?>"  > <span class="more-button"><?php echo esc_html( get_theme_mod( 'izabel_header_media_url_text', esc_html__( 'View Details', 'izabel' ) ) ); ?><span class="screen-reader-text"><?php echo wp_kses_post( $header_media_title ); ?></span></span></a></p>
</div>
<?php endif; ?>

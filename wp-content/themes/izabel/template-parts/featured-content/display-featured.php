<?php
/**
 * The template for displaying featured content
 *
 * @package Izabel
 */
?>

<?php
$enable_content = get_theme_mod( 'izabel_feat_cont_option', 'disabled' );
$classes = '';

if ( ! izabel_check_section( $enable_content ) ) {
	// Bail if featured content is disabled.
	return;
}

$featured_posts = izabel_get_posts( 'feat_cont' );

if ( empty( $featured_posts ) ) {
	return;
}



$title     = get_option( 'feat_cont_title', esc_html__( 'Contents', 'izabel' ) );
$sub_title = get_option( 'feat_cont_content' );

?>

<div id="featured-content-section" class="section">
	<div class="wrapper">
		<?php if ( '' !== $title || $sub_title ) : ?>
			<div class="section-heading-wrapper featured-section-headline">
				<?php if ( '' !== $title ) : ?>
					<div class="section-title-wrapper">
						<h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
					</div><!-- .page-title-wrapper -->
				<?php endif; ?>

				<?php if ( $sub_title ) : ?>
					<div class="taxonomy-description-wrapper section-subtitle">
						<?php
	                    $sub_title = apply_filters( 'the_content', $sub_title );
	                    echo wp_kses_post( str_replace( ']]>', ']]&gt;', $sub_title ) );
	                    ?>
					</div><!-- .taxonomy-description-wrapper -->
				<?php endif; ?>
			</div><!-- .section-heading-wrapper -->
		<?php endif; ?>

		<div class="section-content-wrapper featured-content-wrapper layout-three <?php echo esc_attr( $classes ); ?> ">

			<?php
			foreach ( $featured_posts as $post ) {
				setup_postdata( $post );

				// Include the featured content template.
				get_template_part( 'template-parts/featured-content/content', 'featured' );
			}

			wp_reset_postdata();
			?>
		</div><!-- .featured-content-wrapper -->
	</div><!-- .wrapper -->
</div><!-- #featured-content-section -->

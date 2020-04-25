<?php
/**
 * The template for displaying featured content
 *
 * @package Izabel
 */
?>

<?php
$enable_content = get_theme_mod( 'izabel_collection_option', 'disabled' );
$classes[] = '';

if ( ! izabel_check_section( $enable_content ) ) {
	// Bail if featured content is disabled.
	return;
}

$collection_posts = izabel_get_posts( 'collection' );

if ( empty( $collection_posts ) ) {
	return;
}

$title     = get_theme_mod( 'izabel_collection_archive_title', esc_html__( 'Collection', 'izabel' ) );


$classes[] = 'woocommerce';

$classes[] = 'section';

if ( ! $title ) {
	$classes[] = 'no-section-heading';
}

?>

<div id="collection-section" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<div class="wrapper">
		<?php if ( '' !== $title ) : ?>
			<div class="section-heading-wrapper featured-section-headline">
				<?php if ( '' !== $title ) : ?>
					<div class="section-title-wrapper">
						<h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
					</div><!-- .page-title-wrapper -->
				<?php endif; ?>

			</div><!-- .section-heading-wrapper -->
		<?php endif; ?>

		<div class="section-content-wrapper collection-wrapper layout-three">

			<?php
			$i =1;
			foreach ( $collection_posts as $post ) {
				setup_postdata( $post );

				$post_class = 'hentry';

				$thumbnail = 'izabel-portfolio';

				if ( 1 === $i % 4 ) {
					$post_class = 'featured';
					$thumbnail  = 'izabel-collection-large-layout-three';
				}
			

				if ( has_post_thumbnail() ) {
					$thumb_url = get_the_post_thumbnail_url( get_the_ID(), $thumbnail );
				} else {
					$thumb_url = izabel_get_no_thumb_image( $thumbnail, 'src' );
				}
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
					<div class="hentry-inner">
						<div class="post-thumbnail" style="background-image: url( '<?php echo esc_url( $thumb_url ); ?>' )">
							<a class="cover-link" href="<?php the_permalink(); ?>">
							</a>
						</div>

						<?php 
						if ( izabel_is_woocommerce_activated() ) {
							$title = 'woocommerce-loop-product__title';
						}
						 ?>
						<div class="entry-container product-container">
							<header class="entry-header">

								<?php the_title( '<h2 class="' . esc_attr( $title ) . '"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">','</a></h2>' ); ?>
							</header>

							<?php if ( izabel_is_woocommerce_activated() ) { ?>
								<div class="product-meta">
									<?php woocommerce_template_single_rating(); ?>
									<?php woocommerce_template_single_price(); ?>
								</div>
							<?php } ?>

							<?php if ( izabel_is_woocommerce_activated() ) { ?>
								<div class="product-footer-meta">
									<?php woocommerce_template_loop_add_to_cart(); ?>
								</div>
							<?php } ?>
						</div><!-- .entry-container -->
					</div><!-- .hentry-inner -->
				</article>
				<?php
				
				if ( $i > 8 ) {
					$i = 0;
				}
				
				$i++;
			}

			wp_reset_postdata();
			?>
		</div><!-- .collection-wrapper -->
	</div><!-- .wrapper -->
</div><!-- #collection-section -->

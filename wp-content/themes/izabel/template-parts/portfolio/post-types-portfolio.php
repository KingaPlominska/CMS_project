<?php
/**
 * The template for displaying portfolio items
 *
 * @package Izabel
 */
?>

<?php
$number = get_theme_mod( 'izabel_portfolio_number', 5 );

if ( ! $number ) {
	// If number is 0, then this section is disabled
	return;
}

$args = array(
	'orderby'             => 'post__in',
	'ignore_sticky_posts' => 1 // ignore sticky posts
);

$post_list  = array();// list of valid post/page ids

$type = 'jetpack-portfolio';

$args['post_type'] = $type;

for ( $i = 1; $i <= $number; $i++ ) {
	$post_id = '';

	$post_id =  get_theme_mod( 'izabel_portfolio_cpt_' . $i );
	

	if ( $post_id && '' !== $post_id ) {
		// Polylang Support.
		if ( class_exists( 'Polylang' ) ) {
			$post_id = pll_get_post( $post_id, pll_current_language() );
		}

		$post_list = array_merge( $post_list, array( $post_id ) );

	}
}

$args['post__in'] = $post_list;


$args['posts_per_page'] = $number;
$loop     = new WP_Query( $args );

$slider_select = get_theme_mod( 'izabel_portfolio_slider', 1 );

if ( $loop -> have_posts() ) :
	while ( $loop -> have_posts() ) :
		$loop -> the_post();

		$post_class = 'hentry';

		$post_class .= ' grid-item';
		

		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
			<div class="hentry-inner">
				<?php

				$thumbnail = 'izabel-collection-large-layout-two';
				

				if ( has_post_thumbnail() ) {
					$thumb_url = get_the_post_thumbnail_url( get_the_ID(), $thumbnail );
				} else {
					$thumb_url = izabel_get_no_thumb_image( $thumbnail, 'src' );
				}

				?>
				<div class="post-thumbnail" style="background-image: url( '<?php echo esc_url( $thumb_url ); ?>' )">
					<a class="cover-link" href="<?php the_permalink(); ?>">
					</a>
				</div>


				<div class="entry-container">
					<div class="inner-wrap">
						<header class="entry-header portfolio-entry-header">
							<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

							<?php 
								echo izabel_entry_category_date();
							 ?>
						</header>
					</div><!-- .inner-wrap -->
				</div><!-- .entry-container -->
			</div><!-- .hentry-inner -->
		</article>
	<?php
	endwhile;
	wp_reset_postdata();
endif;

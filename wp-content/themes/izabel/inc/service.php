<?php
/**
 * The template for displaying Services
 *
 * @package Izabel
 */



if ( ! function_exists( 'izabel_service_display' ) ) :
	/**
	* Add Featured content.
	*
	* @uses action hook izabel_before_content.
	*
	* @since Izabel 1.0
	*/
	function izabel_service_display() {
		$output = '';

		// get data value from options
		$enable_content = get_theme_mod( 'izabel_service_option', 'disabled' );

		if ( izabel_check_section( $enable_content ) ) {
			$headline       = get_option( 'ect_service_title', esc_html__( 'Services', 'izabel' ) );
			$subheadline    = get_option( 'ect_service_content' ); 

			if ( ! $headline && ! $subheadline ) {
				$classes[] = 'no-section-heading';
			}

			$output = '
				<div id="service-content-section" class="section ect-service">
					<div class="wrapper">';

			if ( ! empty( $headline ) || ! empty( $subheadline ) ) {
				$output .= '<div class="section-heading-wrapper service-section-headline">';

				if ( ! empty( $headline ) ) {
					$output .= '<div class="section-title-wrapper"><h2 class="section-title">' . wp_kses_post( $headline ) . '</h2></div>';
				}

				if ( ! empty( $subheadline ) ) {
					$subheadline = apply_filters( 'the_content', $subheadline );
					$output .= '<div class="taxonomy-description-wrapper section-subtitle">' . str_replace( ']]>', ']]&gt;', $subheadline ) . '</div>';
				}

				$output .= '
				</div><!-- .section-heading-wrapper -->';
			}
			$output .= '
				<div class="section-content-wrapper service-content-wrapper layout-four">';

			$output .= izabel_post_page_category_service();
			 

			$output .= '
						</div><!-- .service-content-wrapper -->
				</div><!-- .wrapper -->
			</div><!-- #service-content-section -->';

		}

		echo $output;
	}
endif;
add_action( 'izabel_service', 'izabel_service_display', 10 );


if ( ! function_exists( 'izabel_post_page_category_service' ) ) :
	/**
	 * This function to display featured posts content
	 *
	 * @param $options: izabel_theme_options from customizer
	 *
	 * @since Izabel 1.0
	 */
function izabel_post_page_category_service() {
global $post;

$quantity   = get_theme_mod( 'izabel_service_number', 4 );
$post_list  = array();// list of valid post/page ids
$type       = 'ect-service';
$output     = '';

$args = array(
	'orderby'             => 'post__in',
	'ignore_sticky_posts' => 1 // ignore sticky posts
);

//Get valid number of posts

$args['post_type'] = $type;

for ( $i = 1; $i <= $quantity; $i++ ) {
	$post_id = '';

	$post_id = get_theme_mod( 'izabel_service_cpt_' . $i );
	

	if ( $post_id && '' !== $post_id ) {
		// Polylang Support.
		if ( class_exists( 'Polylang' ) ) {
			$post_id = pll_get_post( $post_id, pll_current_language() );
		}

		$post_list = array_merge( $post_list, array( $post_id ) );

	}
}

$args['post__in'] = $post_list;



$args['posts_per_page'] = $quantity;

$loop     = new WP_Query( $args );

while ( $loop->have_posts() ) {
	$loop->the_post();

	$title_attribute = the_title_attribute( 'echo=0' );

	$i = absint( $loop->current_post + 1 );

	$output .= '
		<article id="service-post-' . $i . '" class="status-publish has-post-thumbnail hentry ect-service">';

		// Default value if there is no first image
		$image = '<img class="wp-post-image" src="' . trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/images/no-thumb.jpg" >';

		if ( has_post_thumbnail() ) {
			$image = get_the_post_thumbnail( $post->ID, 'izabel-service', array( 'title' => $title_attribute, 'alt' => $title_attribute ) );
		}
		else {
			// Get the first image in page, returns false if there is no image.
			$first_image = izabel_get_first_image( $post->ID, 'izabel-service', array( 'title' => $title_attribute, 'alt' => $title_attribute ) );

			// Set value of image as first image if there is an image present in the page.
			if ( $first_image ) {
				$image = $first_image;
			}
		}

		$output .= '
			<div class="hentry-inner">
				<a class="post-thumbnail" href="' . esc_url( get_permalink() ) . '" title="' . $title_attribute . '">
					'. $image . '
				</a>
				<div class="entry-container">';

		$output .= the_title( '<header class="entry-header"><h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2></header><!-- .entry-header -->', false );
		

		//Show Excerpt
		$output .= '
			<div class="entry-summary"><p>' . get_the_excerpt() . '</p></div><!-- .entry-summary -->';
	

		$output .= '
				</div><!-- .entry-container -->
			</div><!-- .hentry-inner -->
		</article><!-- .featured-post-' . $i . ' -->';
	} //endwhile

wp_reset_postdata();

return $output;
}
endif; // izabel_post_page_category_service


if ( ! function_exists( 'izabel_image_service' ) ) :
	/**
	 * This function to display featured posts content
	 *
	 * @get the data value from theme options
	 * @displays on the index
	 *
	 * @useage Featured Image, Title and Excerpt of Post
	 *
	 * @uses set_transient
	 *
	 * @since Izabel 1.0
	 */
	function izabel_image_service() {
		$quantity = get_theme_mod( 'izabel_service_number', 4 );
		$output   = '';

		for ( $i = 1; $i <= $quantity; $i++ ) {
			$content = get_theme_mod(  'izabel_service_content_'. $i ) ? '<div class="entry-content">' . get_theme_mod(  'izabel_service_content_'. $i ) . '</div>' : '';
			$target  = get_theme_mod(  'izabel_service_target_' . $i ) ? '_blank' : '_self';
			$link    = '#';
			$title   = '';
			$image   = '';

			// Checking Link.
			if ( get_theme_mod(  'izabel_service_link_' . $i ) ) {
				// support qTranslate plugin.
				if ( function_exists( 'qtrans_convertURL' ) ) {
					$link = qtrans_convertURL( get_theme_mod(  'izabel_service_link_' . $i ) );
				} else {
					$link = get_theme_mod(  'izabel_service_link_' . $i );
				}
			}

			// Checking Title.
			if ( get_theme_mod(  'izabel_service_title_'. $i ) ) {
				$title = get_theme_mod(  'izabel_service_title_'. $i );
			}

			$img_src = get_theme_mod(  'izabel_service_image_' . $i );

			if ( $img_src ) {
				$image .= '
				<a class="post-thumbnail" title="' . esc_attr( $title ) . '" href="' . esc_url( $link ) . '" target="' . $target . '">
					<img src="' . esc_url( $img_src ) . '" class="wp-post-image" alt="' . esc_attr( $title ) . '" title="' . esc_attr( $title ) . '">
				</a>';
			}

			$output .= '
			<article id="service-post-' . esc_attr( $i ) . '" class="post hentry custom-service">
				<div class="hentry-inner">
					' . $image . '
					<div class="entry-container">
						' . $title . $content . '
					</div><!-- .entry-container -->
				</div><!-- .hentry-inner -->
			</article>';
		} // End for().

		return $output;
	}
endif; // izabel_image_service.

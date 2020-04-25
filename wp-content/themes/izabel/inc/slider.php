<?php
/**
 * The template for displaying the Slider
 *
 * @package Izabel
 */

if ( ! function_exists( 'izabel_slider' ) ) :
	/**
	 * Add slider.
	 *
	 * @uses action hook izabel_before_content.
	 *
	 * @since Izabel 1.0
	 */
	function izabel_slider() {
		$enable_slider = get_theme_mod( 'izabel_slider_option', 'disabled' );

		if ( izabel_check_section( $enable_slider ) ) {

			$output = '
				<div id="feature-slider-section" class="section content-right text-aligned-left">
					<div class="wrapper">
						<div class="cycle-slideshow"
							data-cycle-log="false"
							data-cycle-pause-on-hover="true"
							data-cycle-swipe="true"
							data-cycle-auto-height=container
							data-cycle-fx="fade"
							data-cycle-speed="1000"
							data-cycle-timeout="4000"
							data-cycle-loader="false"
							data-cycle-slides="> article"
							>

							<!-- prev/next links -->
							<button class="cycle-prev" aria-label="Previous"><span class="screen-reader-text">' . esc_html__( 'Previous Slide', 'izabel' ) . '</span>' . izabel_get_svg( array( 'icon' => 'angle-down' ) ) . '</button>
							<button class="cycle-next" aria-label="Next"><span class="screen-reader-text">' . esc_html__( 'Next Slide', 'izabel' ) . '</span>' . izabel_get_svg( array( 'icon' => 'angle-down' ) ) . '</button>


							<!-- empty element for pager links -->
							<div class="cycle-pager"></div>';
							// Select Slider

			$output .= izabel_post_page_category_product_slider();
			

			$output .= '
						</div><!-- .cycle-slideshow -->
					</div><!-- .wrapper -->
				</div><!-- #feature-slider -->';

			echo $output;
		} // End if().
	}
	endif;
add_action( 'izabel_slider', 'izabel_slider', 10 );


if ( ! function_exists( 'izabel_post_page_category_product_slider' ) ) :
	/**
	 * This function to display featured posts/page/category slider
	 *
	 * @param $options: izabel_theme_options from customizer
	 *
	 * @since Izabel 1.0
	 */
	function izabel_post_page_category_product_slider() {
		$quantity     = get_theme_mod( 'izabel_slider_number', 4 );
		$no_of_post   = 0; // for number of posts
		$post_list    = array();// list of valid post/page ids
		$type         = 'page';
		$output       = '';

		$args = array(
			'post_type'           => 'any',
			'orderby'             => 'post__in',
			'ignore_sticky_posts' => 1, // ignore sticky posts
		);

		//Get valid number of posts
		for ( $i = 1; $i <= $quantity; $i++ ) {
			$post_id = '';

			$post_id = get_theme_mod( 'izabel_slider_page_' . $i );
			

			if ( $post_id && '' !== $post_id ) {
				$post_list = array_merge( $post_list, array( $post_id ) );

				$no_of_post++;
			}
		}

		$args['post__in'] = $post_list;
		

		if ( ! $no_of_post ) {
			return;
		}

		$args['posts_per_page'] = $no_of_post;

		$loop = new WP_Query( $args );

		while ( $loop->have_posts() ) :
			$loop->the_post();

			$title_attribute = the_title_attribute( 'echo=0' );

			if ( 0 === $loop->current_post ) {
				$classes = 'post post-' . esc_attr( get_the_ID() ) . ' hentry slides displayblock';
			} else {
				$classes = 'post post-' . esc_attr( get_the_ID() ) . ' hentry slides displaynone';
			}

			// Default value if there is no featurd image or first image.
			$image_url = trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/images/no-thumb-1920x822.jpg';

			if ( has_post_thumbnail() ) {
				$image_url = get_the_post_thumbnail_url( get_the_ID(), 'izabel-slider' );
			} else {
				// Get the first image in page, returns false if there is no image.
				$first_image_url = izabel_get_first_image( get_the_ID(), 'izabel-slider', '', true );

				// Set value of image as first image if there is an image present in the page.
				if ( $first_image_url ) {
					$image_url = $first_image_url;
				}
			}

			$output .= '
			<article class="' . $classes . '">';
				$output .= '
				<div class="slider-image-wrapper">
					<img src="' . esc_url( $image_url ) . '" class="wp-post-image" alt="' . $title_attribute . '">
				</div><!-- .slider-image-wrapper -->

				<div class="slider-content-wrapper">
					<div class="entry-container">
						<header class="entry-header">';

						$output .= '<h2 class="entry-title">
								<a title="' . $title_attribute . '" href="' . esc_url( get_permalink() ) . '">' . the_title( '<span>','</span>', false ) . '</a>
							</h2>';
						$output .= '
						</header>
							';

				$excerpt = get_the_excerpt();

				$output .= '<div class="entry-summary"><p>' . $excerpt . '</p></div><!-- .entry-summary -->';
			 

						$output .= '
					</div><!-- .entry-container -->
				</div><!-- .slider-content-wrapper -->
			</article><!-- .slides -->';
		endwhile;

		wp_reset_postdata();

		return $output;
	}
endif; // izabel_post_page_category_product_slider.


if ( ! function_exists( 'izabel_image_slider' ) ) :
	/**
	 * This function to display featured posts slider
	 *
	 * @get the data value from theme options
	 * @displays on the index
	 *
	 * @usage Featured Image, Title and Excerpt of Post
	 *
	 */
	function izabel_image_slider() {
		$quantity = get_theme_mod( 'izabel_slider_number', 4 );

		$output = '';

		for ( $i = 1; $i <= $quantity; $i++ ) {
			$image = get_theme_mod( 'izabel_slider_image_' . $i );

			// Check Image Not Empty to add in the slides.
			if ( $image ) {
				$imagetitle = get_theme_mod( 'izabel_slider_title_' . $i ) ? get_theme_mod( 'izabel_slider_title_' . $i ) : '';

				$imagesubtitle = get_theme_mod( 'izabel_slider_sub_title_' . $i ) ? get_theme_mod( 'izabel_slider_sub_title_' . $i ) : '';

				$title  = '';
				$link   = get_theme_mod( 'izabel_slider_link_' . $i );
				$target = get_theme_mod( 'izabel_slider_target_' . $i ) ? '_blank' : '_self';

				$title = '<header class="entry-header"><div class="entry-meta">' . esc_html( $imagesubtitle ) . '</div><h2 class="entry-title"><span>' . esc_html( $imagetitle ) . '</span></h2></header>';

				if ( $link ) {
					$title = '<header class="entry-header"><div class="entry-meta"><a title="' . esc_attr( $imagetitle ) . '" href="' . esc_url( $link ) . '" target="' . $target . '"><span>' . esc_html( $imagesubtitle ) . '</span></div><h2 class="entry-title"><a title="' . esc_attr( $imagetitle ) . '" href="' . esc_url( $link ) . '" target="' . $target . '"><span>' . esc_html( $imagetitle ) . '</span></a></h2></header>';
				}

				$content = get_theme_mod( 'izabel_slider_content_' . $i ) ? '<div class="entry-summary"><p>' . get_theme_mod( 'izabel_slider_content_' . $i ) . '</p></div><!-- .entry-summary -->' : '';

				$contentopening = '';
				$contentclosing = '';

				// Content Opening and Closing.
				if ( ! empty( $title ) || ! empty( $content ) ) {
					$contentopening = '<div class="slider-content-wrapper"><div class="entry-container">';
					$contentclosing = '</div><!-- .entry-container --></div><!-- .slider-content-wrapper -->';
				}

				// Adding in Classes for Display block and none.
				if ( 1 === $i ) {
					$classes = 'displayblock';
				} else {
					$classes = 'displaynone';
				}

				$output .= '
				<article class="image-slides hentry slider-image images-' . esc_attr( $i ) . ' slides  ' . $classes . '">
					<div class="slider-image-wrapper">
						<img src="' . esc_url( $image ) . '" class="wp-post-image" alt="' . esc_attr( $imagetitle ) . '">
					</div>
					' . $contentopening . $title . $content . $contentclosing . '
				</article><!-- .slides --> ';
			} // End if().
		} // End for().
		return $output;
	}
endif; // izabel_image_slider.

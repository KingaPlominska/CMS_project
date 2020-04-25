<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package Izabel
 */

if ( ! function_exists( 'izabel_featured_image' ) ) :
	/**
	 * Template for Featured Header Image from theme options
	 *
	 * To override this in a child theme
	 * simply create your own izabel_featured_image(), and that function will be used instead.
	 *
	 * @since Audioman Pro 1.0
	 */
	function izabel_featured_image() {
		if ( is_header_video_active() && has_header_video() ) {
			return true;
		}
		
		$thumbnail = 'izabel-slider';

		if ( is_post_type_archive( 'jetpack-testimonial' ) ) {
			$jetpack_options = get_theme_mod( 'jetpack_testimonials' );

			if ( isset( $jetpack_options['featured-image'] ) && '' !== $jetpack_options['featured-image'] ) {
				$image = wp_get_attachment_image_src( (int) $jetpack_options['featured-image'], $thumbnail );
				return $image['0'];
			} else {
				return false;
			}
		} elseif ( is_post_type_archive( 'jetpack-portfolio' ) || is_post_type_archive( 'featured-content' ) || is_post_type_archive( 'ect-service' ) ) {
			$option = '';

			if ( is_post_type_archive( 'jetpack-portfolio' ) ) {
				$option = 'jetpack_portfolio_featured_image';
			} elseif ( is_post_type_archive( 'featured-content' ) ) {
				$option = 'feat_cont_featured_image';
			} elseif ( is_post_type_archive( 'ect-service' ) ) {
				$option = 'ect_service_featured_image';
			}

			$featured_image = get_option( $option );

			if ( '' !== $featured_image ) {
				$image = wp_get_attachment_image_src( (int) $featured_image, $thumbnail );
				return $image[0];
			} else {
				return get_header_image();
			}
		} else {
			return get_header_image();
		}
	} // izabel_featured_image
endif;

if ( ! function_exists( 'izabel_featured_page_post_image' ) ) :
	/**
	 * Template for Featured Header Image from Post and Page
	 *
	 * To override this in a child theme
	 * simply create your own izabel_featured_imaage_pagepost(), and that function will be used instead.
	 *
	 * @since Izabel 1.0
	 */
	function izabel_featured_page_post_image() {
		$thumbnail = 'izabel-slider';

		if ( class_exists( 'WooCommerce' ) && is_shop() ) {
			if ( ! has_post_thumbnail( absint( get_option( 'woocommerce_shop_page_id' ) ) ) ) {
				return izabel_featured_image();
			}
		} elseif ( is_home() && $blog_id = get_option('page_for_posts') ) {
			if ( has_post_thumbnail( $blog_id  ) ) {
		    	return get_the_post_thumbnail_url( $blog_id, $thumbnail );
			} else {
				return  izabel_featured_image();
			}
		} elseif ( ! has_post_thumbnail() ) {
			return  izabel_featured_image();
		} elseif ( is_home() && is_front_page() ) {
			return  izabel_featured_image();
		}

		$shop_header = get_theme_mod( 'izabel_shop_page_header_image' );
		if ( class_exists( 'WooCommerce' ) && is_shop() ) {
			return get_the_post_thumbnail_url( absint( get_option( 'woocommerce_shop_page_id' ) ), $thumbnail );
		}elseif ( class_exists( 'WooCommerce' ) && is_product () ) {
			if (  $shop_header ){
			return get_the_post_thumbnail_url( get_the_id(), $thumbnail );
			}
		}else {
			return get_the_post_thumbnail_url( get_the_id(), $thumbnail );
		}
	} // izabel_featured_page_post_image
endif;

if ( ! function_exists( 'izabel_featured_overall_image' ) ) :
	/**
	 * Template for Featured Header Image from theme options
	 *
	 * To override this in a child theme
	 * simply create your own izabel_featured_pagepost_image(), and that function will be used instead.
	 *
	 * @since Audioman Pro 1.0
	 */
	function izabel_featured_overall_image() {
		global $post;
		$enable = get_theme_mod( 'izabel_header_media_option', 'entire-site-page-post' );

		// Check Enable/Disable header image in Page/Post Meta box
		if ( is_singular() || ( class_exists( 'WooCommerce' ) && is_shop() ) ) { 
			$izabel_id = $post->ID; 
 
			if ( class_exists( 'WooCommerce' ) && is_shop() ) { 
				$izabel_id = absint( get_option( 'woocommerce_shop_page_id' ) ); 
			} 
			//Individual Page/Post Image Setting
			$individual_featured_image = get_post_meta( $izabel_id, 'izabel-header-image', true );

			if ( 'disable' === $individual_featured_image || ( 'default' === $individual_featured_image && 'disable' === $enable ) ) {
				return 'disable' ;
			} elseif ( 'enable' == $individual_featured_image ) {
				return izabel_featured_page_post_image();
			}
		}

		// Check Homepage
		if ( 'homepage' === $enable ) {
			if ( is_front_page() ) {
				return izabel_featured_image();
			}
		} elseif ( 'exclude-home' === $enable ) {
			// Check Excluding Homepage
			if ( ! is_front_page() ) {
				return izabel_featured_image();
			}
		} elseif ( 'exclude-home-page-post' === $enable ) {
			if ( is_front_page() ) {
				return 'disable';
			} elseif ( is_singular() || ( class_exists( 'WooCommerce' ) && is_shop() ) ) { 
				return izabel_featured_page_post_image();
			} else {
				return izabel_featured_image();
			}
		} elseif ( 'entire-site' === $enable ) {
			// Check Entire Site
			return izabel_featured_image();
		} elseif ( 'entire-site-page-post' === $enable ) {
			// Check Entire Site (Post/Page)
			if ( is_singular() || ( class_exists( 'WooCommerce' ) && is_shop() ) || ( is_home() && ! is_front_page() ) ) {
				return izabel_featured_page_post_image();
			} else {
				return izabel_featured_image();
			}
		} elseif ( 'pages-posts' === $enable ) {
			// Check Page/Post
			if ( is_singular() || ( class_exists( 'WooCommerce' ) && is_shop() ) ) { 
				return izabel_featured_page_post_image();
			}
		}

		return 'disable';
	} // izabel_featured_overall_image
endif;

if ( ! function_exists( 'izabel_header_media_text' ) ):
	/**
	 * Display Header Media Text
	 *
	 * @since Audioman Pro 1.0
	 */
	function izabel_header_media_text() {

		if ( ! izabel_has_header_media_text() ) {
			// Bail early if header media text is disabled on front page
			return false;
		}

		$content_position = get_theme_mod( 'izabel_header_media_content_position', 'content-center-top' );
		$text_align  = get_theme_mod( 'izabel_header_media_text_alignment', 'text-aligned-center' );

		$classes[] = 'custom-header-content';
		$classes[] = 'sections';
		$classes[] = 'header-media-section';
		$classes[] = $content_position;
		$classes[] = $text_align;

		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<div class="entry-container">

				<?php if ( is_front_page() ) : ?>
				<?php izabel_header_sub_title( '<div class="sub-title"><span>', '</span></div>' ); ?>
				<?php endif; ?>

				<?php izabel_header_title( '<h2 class="entry-title">', '</h2>' ); ?>

				<?php izabel_header_description(); ?>

				<?php if ( is_front_page() ) :
					$header_media_url      = get_theme_mod( 'izabel_header_media_url', '#' );
					$header_media_url_text = get_theme_mod( 'izabel_header_media_url_text', esc_html__( 'View Details', 'izabel' ) );
				?>

					<?php if ( $header_media_url_text ) : ?>
						<a class="more-link" href="<?php echo esc_url( $header_media_url ); ?>" target="<?php echo get_theme_mod( 'izabel_header_url_target' ) ? '_blank' : '_self'; ?>">

							<span class="more-button"><?php echo esc_html( $header_media_url_text ); ?><span class="screen-reader-text"><?php echo wp_kses_post( $header_media_url_text ); ?></span></span>
						</a>
					<?php endif; ?>
				<?php endif; ?>
			</div> <!-- .entry-container -->
		</div><!-- .custom-header-content -->
		<?php
	} // izabel_header_media_text.
endif;

if ( ! function_exists( 'izabel_has_header_media_text' ) ):
	/**
	 * Return Header Media Text fro front page
	 *
	 * @since Audioman Pro 1.0
	 */
	function izabel_has_header_media_text() {
		$header_image = izabel_featured_overall_image();

		if ( is_front_page() ) {
			$header_media_sub_title    	= is_singular() ? '' : get_theme_mod( 'izabel_header_media_sub_title', esc_html__( 'New Arrival', 'izabel' ) );
			$header_media_title    		= get_theme_mod( 'izabel_header_media_title', esc_html__( 'Furniture Store', 'izabel' ) );
			$header_media_text     		= get_theme_mod( 'izabel_header_media_text' );
			$header_media_url      		= get_theme_mod( 'izabel_header_media_url', '#' );
			$header_media_url_text 		= get_theme_mod( 'izabel_header_media_url_text', esc_html__( 'View Details', 'izabel' ) );

			if ( ! $header_media_sub_title && ! $header_media_title && ! $header_media_text && ! $header_media_url && ! $header_media_url_text ) {
				// Bail early if header media text is disabled
				return false;
			}
		} elseif ( 'disable' === $header_image ) {
			return false;
		}

		return true;
	} // izabel_has_header_media_text.
endif;

if ( ! function_exists( 'izabel_header_sub_title' ) ) :
	/**
	 * Display header media text
	 */
	function izabel_header_sub_title( $before = '', $after = '' ) {
		if ( is_front_page() ) {
			$header_media_sub_title = ( is_singular() && ! is_front_page() ) ? '' : get_theme_mod( 'izabel_header_media_sub_title', esc_html__( 'New Arrival', 'izabel' ) );
			if ( $header_media_sub_title ) {
				echo $before . wp_kses_post( $header_media_sub_title ) . $after;
			}
		}  elseif ( is_singular() ) {
			if ( is_page() ) {
				if( ! get_theme_mod( 'izabel_single_page_title' ) ) {
					the_title( $before, $after );
				}
			} else {
				the_title( $before, $after );
			}
		} elseif ( is_404() ) {
			echo $before . esc_html__( 'Nothing Found', 'izabel' ) . $after;
		} elseif ( is_search() ) {
			/* translators: %s: search query. */
			echo $before . sprintf( esc_html__( 'Search Results for: %s', 'izabel' ), '<span>' . get_search_query() . '</span>' ) . $after;
		} else {
			the_archive_title( $before, $after );
		}
	}
endif;

if ( ! function_exists( 'izabel_header_title' ) ) :
	/**
	 * Display header media text
	 */
	function izabel_header_title( $before = '', $after = '' ) {
		if ( is_front_page() ) {
			$header_media_title = get_theme_mod( 'izabel_header_media_title', esc_html__( 'Furniture Store', 'izabel' ) );
			if ( $header_media_title ) {
				echo $before . wp_kses_post( $header_media_title ) . $after;
			}
		}  elseif ( is_singular() ) {
			if ( is_page() ) {
				if( ! get_theme_mod( 'izabel_single_page_title' ) ) {
					the_title( $before, $after );
				}
			} else {
				the_title( $before, $after );
			}
		} elseif ( is_404() ) {
			echo $before . esc_html__( 'Nothing Found', 'izabel' ) . $after;
		} elseif ( is_search() ) {
			/* translators: %s: search query. */
			echo $before . sprintf( esc_html__( 'Search Results for: %s', 'izabel' ), '<span>' . get_search_query() . '</span>' ) . $after;
		} elseif( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
			echo $before . esc_html( woocommerce_page_title( false ) ) . $after;
		} else {
			the_archive_title( $before, $after );
		}
	}
endif;

if ( ! function_exists( 'izabel_header_description' ) ) :
	/**
	 * Display header media description
	 */
	function izabel_header_description( $before = '', $after = '' ) {
		if ( is_front_page() ) {
			echo $before . '<p class="site-header-text">' . wp_kses_post( get_theme_mod( 'izabel_header_media_text' ) ) . '</p>' . $after;
		} elseif ( is_singular() && ! is_page() ) {
			echo $before . '<div class="entry-header"><div class="entry-meta">';
				izabel_entry_posted_on();
			echo '</div><!-- .entry-meta --></div>' . $after;
		} elseif ( is_404() ) {
			echo $before . '<p>' . esc_html__( 'Oops! That page can&rsquo;t be found', 'izabel' ) . '</p>' . $after;
		} else {
			the_archive_description( $before, $after );
		}
	}
endif;

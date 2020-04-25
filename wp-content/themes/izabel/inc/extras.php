<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Izabel
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Izabel 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function izabel_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Always add a front-page class to the front page.
	if ( is_front_page() && ! is_home() ) {
		$classes[] = 'page-template-front-page';
	}

	// Adds a class of (full-width|box) to blogs.
	$classes[] = 'fluid-layout';

	// Adds a class with respect to layout selected.
	$layout  = izabel_get_theme_layout();
	$sidebar = izabel_get_sidebar_id();

	if ( 'no-sidebar-full-width' === $layout ) {
		$classes[] = 'no-sidebar full-width-layout';
	} 
	elseif ( 'right-sidebar' === $layout ) {
		if ( '' !== $sidebar ) {
			$classes[] = 'two-columns-layout content-left';
		}
	}

	$header_media_sub_title = ( is_singular() && ! is_front_page() ) ? '' : get_theme_mod( 'izabel_header_media_sub_title', esc_html__( 'New Arrival', 'izabel' ) );
	$header_media_title = get_theme_mod( 'izabel_header_media_title', esc_html__( 'Furniture Store', 'izabel' ) );
	$header_media_text  = get_theme_mod( 'izabel_header_media_text' );
	$header_media_url = get_theme_mod( 'izabel_header_media_url', '#' );
	$header_media_button_text = get_theme_mod( 'izabel_header_media_url_text', esc_html__( 'View Details', 'izabel' ) );

	 $header_text_enabled = izabel_has_header_media_text();

	if ( ! $header_text_enabled ) {
		$classes[] = 'no-header-media-text';
	}

	$classes[] = 'header-right-menu-disabled';

	$classes[] = 'header-center-layout';

	$classes[] = 'footer-center';

	$enable_slider = izabel_check_section( get_theme_mod( 'izabel_slider_option', 'disabled' ) );
	$header_image = izabel_featured_overall_image();

	if  ( $enable_slider || 'disable' !== $header_image ) {
		$classes[] = 'absolute-header';
	}

	$enable_slider = izabel_check_section( get_theme_mod( 'izabel_slider_option', 'disabled' ) );
	$header_image = izabel_featured_overall_image();

	$classes[] = 'primary-nav-center';

	return $classes;
}
add_filter( 'body_class', 'izabel_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function izabel_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'izabel_pingback_header' );

/**
 * Remove first post from blog as it is already show via recent post template
 */
function izabel_alter_home( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) {
		$cats = get_theme_mod( 'izabel_front_page_category' );

		if ( is_array( $cats ) && ! in_array( '0', $cats ) ) {
			$query->query_vars['category__in'] = $cats;
		}
	}
}
add_action( 'pre_get_posts', 'izabel_alter_home' );

/**
 * Function to add Scroll Up icon
 */
function izabel_scrollup() {
	$disable_scrollup = get_theme_mod( 'izabel_display_scrollup' );

	if ( ! $disable_scrollup ) {
		return;
	}

	echo '<a href="#masthead" id="scrollup" class="backtotop">' . izabel_get_svg( array( 'icon' => 'angle-down' ) ) . '<span class="screen-reader-text">' . esc_html__( 'Scroll Up', 'izabel' ) . '</span></a>' ;

}
add_action( 'wp_footer', 'izabel_scrollup', 1 );

if ( ! function_exists( 'izabel_content_nav' ) ) :
	/**
	 * Display navigation/pagination when applicable
	 *
	 * @since Izabel 1.0
	 */
	function izabel_content_nav() {
		global $wp_query;

		// Don't print empty markup in archives if there's only one page.
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) ) {
			return;
		}

		$pagination_type = get_theme_mod( 'izabel_pagination_type', 'default' );

		/**
		 * Check if navigation type is Jetpack Infinite Scroll and if it is enabled, else goto default pagination
		 * if it's active then disable pagination
		 */
		if ( ( 'infinite-scroll' === $pagination_type ) && class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'infinite-scroll' ) ) {
			return false;
		}

		if ( 'numeric' === $pagination_type && function_exists( 'the_posts_pagination' ) ) {
			the_posts_pagination( array(
				'prev_text'          => esc_html__( 'Previous', 'izabel' ),
				'next_text'          => esc_html__( 'Next', 'izabel' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'izabel' ) . ' </span>',
			) );
		} else {
			the_posts_navigation();
		}
	}
endif; // izabel_content_nav

/**
 * Check if a section is enabled or not based on the $value parameter
 * @param  string $value Value of the section that is to be checked
 * @return boolean return true if section is enabled otherwise false
 */
function izabel_check_section( $value ) {
	global $wp_query;

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	// Front page displays in Reading Settings
	$page_for_posts = get_option('page_for_posts');

	return ( 'entire-site' == $value  || ( ( is_front_page() || ( is_home() && intval( $page_for_posts ) !== intval( $page_id ) ) ) && 'homepage' == $value ) );
}

/**
 * Return the first image in a post. Works inside a loop.
 * @param [integer] $post_id [Post or page id]
 * @param [string/array] $size Image size. Either a string keyword (thumbnail, medium, large or full) or a 2-item array representing width and height in pixels, e.g. array(32,32).
 * @param [string/array] $attr Query string or array of attributes.
 * @return [string] image html
 *
 * @since Izabel 1.0
 */

function izabel_get_first_image( $postID, $size, $attr ) {
	ob_start();

	ob_end_clean();

	$image 	= '';

	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_post_field('post_content', $postID ) , $matches);

	if( isset( $matches [1] [0] ) ) {
		//Get first image
		$first_img = $matches [1] [0];

		return '<img class="pngfix wp-post-image" src="'. esc_url( $first_img ) .'">';
	}

	return false;
}

function izabel_get_theme_layout() {
	$layout = '';

	 if ( is_page_template( 'templates/full-width-page.php' ) ) {
		$layout = 'no-sidebar-full-width';
	} elseif ( is_page_template( 'templates/right-sidebar.php' ) ) {
		$layout = 'right-sidebar';
	} else {
		$layout = get_theme_mod( 'izabel_default_layout', 'right-sidebar' );

		if ( is_front_page() ) {
			$layout = get_theme_mod( 'izabel_homepage_layout', 'no-sidebar-full-width' );
		} elseif ( is_home() || is_archive() || is_search() ) {
			$layout = 'right-sidebar';
		}

		if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_woocommerce() || is_cart() || is_checkout() ) ) {
			$layout = 'no-sidebar-full-width';
		}
	}

	return $layout;
}

function izabel_get_posts_columns() {
	$columns = 'layout-one';

	if ( is_front_page() ) {
		$columns = 'layout-three';
	}

	return $columns;
}

function izabel_get_sidebar_id() {
	$sidebar = '';

	$layout = izabel_get_theme_layout();

	$sidebaroptions = '';

	if ( 'no-sidebar-full-width' === $layout ) {
		return $sidebar;
	}

	// WooCommerce Shop Page excluding Cart and checkout.
	if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
		$shop_id        = get_option( 'woocommerce_shop_page_id' );
		$sidebaroptions = get_post_meta( $shop_id, 'izabel-sidebar-option', true );
	} else {
		global $post, $wp_query;

		// Front page displays in Reading Settings.
		$page_on_front  = get_option( 'page_on_front' );
		$page_for_posts = get_option( 'page_for_posts' );

		// Get Page ID outside Loop.
		$page_id = $wp_query->get_queried_object_id();
		// Blog Page or Front Page setting in Reading Settings.
		if ( $page_id == $page_for_posts || $page_id == $page_on_front ) {
	        $sidebaroptions = get_post_meta( $page_id, 'izabel-sidebar-option', true );
	    } elseif ( is_singular() ) {
	    	if ( is_attachment() ) {
				$parent 		= $post->post_parent;
				$sidebaroptions = get_post_meta( $parent, 'izabel-sidebar-option', true );

			} else {
				$sidebaroptions = get_post_meta( $post->ID, 'izabel-sidebar-option', true );
			}
		}
	}

	if ( is_active_sidebar( 'sidebar-1' ) ) {
		$sidebar = 'sidebar-1'; // Primary Sidebar.
	}

	return $sidebar;
}

/**
 * Display social Menu
 */
function izabel_social_menu() {
	if ( has_nav_menu( 'social-menu' ) ) :
		?>
		<nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Social Links Menu', 'izabel' ); ?>">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'social-menu',
					'link_before'    => '<span class="screen-reader-text">',
					'link_after'     => '</span>',
					'depth'          => 1,
				) );
			?>
		</nav><!-- .social-navigation -->
	<?php endif;
}

if ( ! function_exists( 'izabel_truncate_phrase' ) ) :
	/**
	 * Return a phrase shortened in length to a maximum number of characters.
	 *
	 * Result will be truncated at the last white space in the original string. In this function the word separator is a
	 * single space. Other white space characters (like newlines and tabs) are ignored.
	 *
	 * If the first `$max_characters` of the string does not contain a space character, an empty string will be returned.
	 *
	 * @since Izabel 1.0
	 *
	 * @param string $text            A string to be shortened.
	 * @param integer $max_characters The maximum number of characters to return.
	 *
	 * @return string Truncated string
	 */
	function izabel_truncate_phrase( $text, $max_characters ) {

		$text = trim( $text );

		if ( mb_strlen( $text ) > $max_characters ) {
			//* Truncate $text to $max_characters + 1
			$text = mb_substr( $text, 0, $max_characters + 1 );

			//* Truncate to the last space in the truncated string
			$text = trim( mb_substr( $text, 0, mb_strrpos( $text, ' ' ) ) );
		}

		return $text;
	}
endif; //catch-izabel_truncate_phrase

if ( ! function_exists( 'izabel_get_the_content_limit' ) ) :
	/**
	 * Return content stripped down and limited content.
	 *
	 * Strips out tags and shortcodes, limits the output to `$max_char` characters, and appends an ellipsis and more link to the end.
	 *
	 * @since Izabel 1.0
	 *
	 * @param integer $max_characters The maximum number of characters to return.
	 * @param string  $more_link_text Optional. Text of the more link. Default is "(more...)".
	 * @param bool    $stripteaser    Optional. Strip teaser content before the more text. Default is false.
	 *
	 * @return string Limited content.
	 */
	function izabel_get_the_content_limit( $max_characters, $more_link_text = '(more...)', $stripteaser = false ) {

		$content = get_the_content( '', $stripteaser );

		// Strip tags and shortcodes so the content truncation count is done correctly.
		$content = strip_tags( strip_shortcodes( $content ), apply_filters( 'get_the_content_limit_allowedtags', '<script>,<style>' ) );

		// Remove inline styles / .
		$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

		// Truncate $content to $max_char
		$content = izabel_truncate_phrase( $content, $max_characters );

		// More link?
		if ( $more_link_text ) {
			$link   = apply_filters( 'get_the_content_more_link', sprintf( '<span class="more-button"><a href="%s" class="more-link">%s</a></span>', esc_url( get_permalink() ), $more_link_text ), $more_link_text );
			$output = sprintf( '<p>%s %s</p>', $content, $link );
		} else {
			$output = sprintf( '<p>%s</p>', $content );
			$link = '';
		}

		return apply_filters( 'izabel_get_the_content_limit', $output, $content, $link, $max_characters );

	}
endif; //catch-izabel_get_the_content_limit

if ( ! function_exists( 'izabel_content_image' ) ) :
	/**
	 * Template for Featured Image in Archive Content
	 *
	 * To override this in a child theme
	 * simply fabulous-fluid your own izabel_content_image(), and that function will be used instead.
	 *
	 * @since Izabel 1.0
	 */
	function izabel_content_image() {
		if ( has_post_thumbnail() && izabel_jetpack_featured_image_display() && is_singular() ) {
			global $post, $wp_query;

			// Get Page ID outside Loop.
			$page_id = $wp_query->get_queried_object_id();

			if ( $post ) {
		 		if ( is_attachment() ) {
					$parent = $post->post_parent;

					$individual_featured_image = get_post_meta( $parent, 'izabel-single-image', true );
				} else {
					$individual_featured_image = get_post_meta( $page_id, 'izabel-single-image', true );
				}
			}

			if ( empty( $individual_featured_image ) ) {
				$individual_featured_image = 'default';
			}

			if ( 'disable' === $individual_featured_image ) {
				echo '<!-- Page/Post Single Image Disabled or No Image set in Post Thumbnail -->';
				return false;
			} else {
				$class = array();

				$image_size = 'post-thumbnail';

				if ( 'default' !== $individual_featured_image ) {
					$image_size = $individual_featured_image;
					$class[]    = 'from-metabox';
				} else {
					$layout = izabel_get_theme_layout();

					if ( 'no-sidebar-full-width' === $layout ) {
						$image_size = 'izabel-slider';
					}
				}

				$class[] = $individual_featured_image;
				?>
				<div class="post-thumbnail <?php echo esc_attr( implode( ' ', $class ) ); ?>">
					<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( $image_size ); ?>
					</a>
				</div>
		   	<?php
			}
		} // End if().
	}
endif; // izabel_content_image.

/**
 * Get Featured Posts
 */
function izabel_get_posts( $section ) {
	$type   = 'featured-content';
	$number = get_theme_mod( 'izabel_feat_cont_number', 3 );

	if ( 'feat_cont' === $section ) {
		$type     = 'featured-content';
		$number   = get_theme_mod( 'izabel_feat_cont_number', 3 );
		$cpt_slug = 'featured-content';
	} elseif ( 'portfolio' === $section ) {
		$type     = 'jetpack-portfolio';
		$number   = get_theme_mod( 'izabel_portfolio_number', 5 );
		$cpt_slug = 'jetpack-portfolio';
	} elseif ( 'service' === $section ) {
		$type     = 'ect-service';
		$number   = get_theme_mod( 'izabel_service_number', 4 );
		$cpt_slug = 'ect-service';
	} elseif ( 'testimonial' === $section ) {
		$type     = 'jetpack-testimonial';
		$number   = get_theme_mod( 'izabel_testimonial_number', 4 );
		$cpt_slug = 'jetpack-testimonial';
	} elseif ( 'collection' === $section ) {
		$type     = 'product';
		$number   = get_theme_mod( 'izabel_collection_number', 6 );
		$cpt_slug = '';
	}

	$post_list  = array();
	$no_of_post = 0;

	$args = array(
		'post_type'           => 'post',
		'ignore_sticky_posts' => 1, // ignore sticky posts.
	);

	// Get valid number of posts.
	if ( 'post' === $type || 'page' === $type || $cpt_slug === $type || 'product' === $type ) {
		$args['post_type'] = $type;

		for ( $i = 1; $i <= $number; $i++ ) {
			$post_id = '';

			if ( 'post' === $type ) {
				$post_id = get_theme_mod( 'izabel_' . $section . '_post_' . $i );
			} elseif ( 'page' === $type ) {
				$post_id = get_theme_mod( 'izabel_' . $section . '_page_' . $i );
			} elseif ( $cpt_slug === $type ) {
				$post_id = get_theme_mod( 'izabel_' . $section . '_cpt_' . $i );
			} elseif ( 'product' === $type ) {
				$post_id = get_theme_mod( 'izabel_' . $section . '_product_' . $i );
			}

			if ( $post_id && '' !== $post_id ) {
				$post_list = array_merge( $post_list, array( $post_id ) );

				$no_of_post++;
			}
		}

		$args['post__in'] = $post_list;
		$args['orderby']  = 'post__in';
	} elseif ( 'category' === $type ) {
		if ( $cat = get_theme_mod( 'izabel_' . $section . '_select_category' ) ) {
			$args['category__in'] = $cat;
		}


		$no_of_post = $number;
	}

	$args['posts_per_page'] = $no_of_post;

	if( ! $no_of_post ) {
		return;
	}

	$posts = get_posts( $args );

	return $posts;
}

if ( ! function_exists( 'izabel_sections' ) ) :
	/**
	 * Display Sections on header and footer with respect to the section option set in izabel_sections_sort
	 */
	function izabel_sections( $selector = 'header' ) {
		get_template_part( 'template-parts/header/header-media' );
		get_template_part( 'template-parts/slider/content-slider' );
		get_template_part( 'template-parts/collection/display-collection' );
		get_template_part( 'template-parts/featured-content/display-featured' );
		get_template_part( 'template-parts/testimonial/display-testimonial' );
		get_template_part( 'template-parts/hero-content/content-hero' );
		get_template_part( 'template-parts/portfolio/display-portfolio' );
		get_template_part( 'template-parts/woo-products-showcase/toprated-products' );
		get_template_part( 'template-parts/service/content-service' );
	}
endif;

if ( ! function_exists( 'izabel_get_no_thumb_image' ) ) :
	/**
	 * $image_size post thumbnail size
	 * $type image, src
	 */
	function izabel_get_no_thumb_image( $image_size = 'post-thumbnail', $type = 'image' ) {
		$image = $image_url = '';

		global $_wp_additional_image_sizes;

		$size = $_wp_additional_image_sizes['post-thumbnail'];

		if ( isset( $_wp_additional_image_sizes[ $image_size ] ) ) {
			$size = $_wp_additional_image_sizes[ $image_size ];
		}

		$image_url  = trailingslashit( get_template_directory_uri() ) . 'assets/images/no-thumb.jpg';

		if ( 'post-thumbnail' !== $image_size ) {
			$image_url  = trailingslashit( get_template_directory_uri() ) . 'assets/images/no-thumb-' . $size['width'] . 'x' . $size['height'] . '.jpg';
		}

		if ( 'src' === $type ) {
			return $image_url;
		}

		return '<img class="no-thumb ' . esc_attr( $image_size ) . '" src="' . esc_url( $image_url ) . '" />';
	}
endif;

/**
 * Adds custom overlay for Header Media
 */
function izabel_header_media_image_overlay_css() {
	$overlay = get_theme_mod( 'izabel_header_media_image_opacity' );

	$css = '';

	$overlay_bg = $overlay / 100;

	$color_scheme = get_theme_mod( 'color_scheme', 'default' );

	if ( $overlay ) {
			$css = '.custom-header:after {
				background-color: rgba(0,0,0,' . esc_attr( $overlay_bg ) . ');
		    } '; // Dividing by 100 as the option is shown as % for user
	}
	
	wp_add_inline_style( 'izabel-style', $css );
}
add_action( 'wp_enqueue_scripts', 'izabel_header_media_image_overlay_css', 11 );

/**
 * Adds custom overlay for Slider
 */
function izabel_featured_slider_overlay_css() {
	$overlay = get_theme_mod( 'izabel_slider_opacity' );

	$css = '';

	$overlay_bg = $overlay / 100;

	$color_scheme = get_theme_mod( 'color_scheme', 'default' );

	if ( $overlay ) {
			
			$css = '#feature-slider-section {
				background-color: rgba(0, 0, 0, ' . esc_attr( $overlay_bg ) . ' );
		    } '; // Dividing by 100 as the option is shown as % for user
	}

	wp_add_inline_style( 'izabel-style', $css );
}
add_action( 'wp_enqueue_scripts', 'izabel_featured_slider_overlay_css', 11 );


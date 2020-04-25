<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package Izabel
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class="singular-content-wrap">
			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();

				// Include the single post content template.
				get_template_part( 'template-parts/content/content', 'single' );

				// Comments Templates
				get_template_part( 'template-parts/content/content', 'comment' );

				if ( is_singular( 'attachment' ) ) {
					// Parent post navigation.
					the_post_navigation( array(
						'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'izabel' ),
					) );
				} elseif ( is_singular( 'post' ) ) {
					// Previous/next post navigation.
					the_post_navigation( array(
						'prev_text' => '<span aria-hidden="true" class="nav-subtitle"><span class="nav-subtitle-icon-wrapper">' . izabel_get_svg( array( 'icon' => 'angle-down' ) ) . '</span>' . __( 'Previous', 'izabel' ) . '</span><span class="nav-title">%title</span><span class="screen-reader-text">' . __( 'Previous Post', 'izabel' ) . '</span>' ,
						'next_text' => '<span aria-hidden="true" class="nav-subtitle">'. __( 'Next', 'izabel' ) . '<span class="nav-subtitle-icon-wrapper">' . izabel_get_svg( array( 'icon' => 'angle-down' ) ) . '</span></span><span class="nav-title">%title</span><span class="screen-reader-text">' . __( 'Next Post', 'izabel' ) . '</span>' ,
					) );
				}

				// End of the loop.
			endwhile;
			?>
		</div><!-- .singular-content-wrap -->
	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

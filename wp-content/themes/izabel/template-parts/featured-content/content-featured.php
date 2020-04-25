<?php
/**
 * The template for displaying featured posts on the front page
 *
 * @package Izabel
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'hentry' ); ?>>
	<div class="hentry-inner">
		<?php if ( has_post_thumbnail() ) : ?>
		<a class="post-thumbnail" href="<?php the_permalink(); ?>">
			<?php
			$thumbnail = 'post-thumbnail';

			the_post_thumbnail( $thumbnail );
			?>
		</a>
		<?php endif; ?>

		<?php 
			$title = 'entry-title';
				
		 ?>
		<div class="entry-container product-container">
			<header class="entry-header">
				<?php the_title( '<h2 class="' . esc_attr( $title ) . '"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">','</a></h2>' ); ?>
			</header>


			<?php
				$excerpt = get_the_excerpt();

				echo '<div class="entry-summary"><p>' . $excerpt . '</p></div><!-- .entry-summary -->';
			?>

		</div><!-- .entry-container -->
	</div><!-- .hentry-inner -->
</article>

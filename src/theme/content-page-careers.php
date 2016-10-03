<?php
/**
 * @package RP3
 */

// Query all of our "careers" post (by category name)
$careers = new WP_Query( array(
	'category_name'			=> 'careers',
	'posts_per_page'		=> -1
));

// Feed the results into two arrays, one for jobs and one for internships
if ( $careers->have_posts() ) {
	while ( $careers->have_posts() ) {
		$careers->the_post();

		if ( has_category( 'internships' ) ) {
			$internships[] = $post;
		} else {
			$jobs[] = $post;
		}
	}
}

// Reset the query before moving on.
wp_reset_query();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>

	<div class="entry-content component component--padded">

		<div class="entry-content__container">

			<?php the_content(); ?>

		</div>
		<!-- // .entry-content__container -->

	</div>
	<!-- .entry-content -->


	<div class="interstitial component">

		<?php if ( '' != get_field( 'hero_image' ) ) {
			echo rp3_full_bleed_hero_image( get_field( 'hero_image' ), array(
				'image_size'	=> 'interstitial',
				'classes'		=> 'hero hero-image interstitial'
			) );
		} ?>

	</div>



	<?php // Additional content about our awesome culture ?>


	<?php if ( '' !== get_field( 'secondary_copy' ) ) : ?>

		<section class="careers__secondary-copy component component--padded">

			<?php the_field( 'secondary_copy' ); ?>

		</section>

	<?php endif; ?>



	<?php
	// Jobs
	if ( isset( $jobs ) && is_array( $jobs ) && ( count( $jobs ) > 0 ) ) :
	?>

	<section class="careers component component--padded">

		<header class="careers__header--section">
			<h1>Job Openings</h1>
		</header>
		<!-- // .careers__header—section -->

		<div class="careers__row">

	<?php
		foreach ( $jobs as $post ) :
			setup_postdata( $post );
	?>

			<div id="post-<?php the_ID(); ?>" <?php post_class('careers__article'); ?>>

				<a href="<?php the_permalink(); ?>" rel="bookmark" class="block">

					<header class="careers__header--article">
						<h2 class="careers__title"><?php the_title(); ?></h2>
					</header>
					<!-- // .careers__header—article -->

					<div class="careers__summary">
						<?php // We don't want sharing links here, exactly. ?>
						<?php remove_filter( 'the_excerpt', 'sharing_display', 19 ); ?>
						<?php the_excerpt(); ?>
					</div>
					<!-- // .careers__summary -->

				</a>

			</div>
			<!-- #post-## -->

	<?php endforeach; ?>

		</div>
		<!-- // .careers__row -->

	</section>
	<!-- // .careers -->

	<?php endif; wp_reset_postdata(); ?>



	<?php
	// Internships
	if ( isset( $internships ) && is_array( $internships ) && ( count( $internships ) > 0 ) ) :
	?>

	<section class="careers component component--padded">

		<header class="careers__header--section">
			<h1>Internships</h1>
		</header>
		<!-- // .careers__header -->

		<div class="careers__row">

	<?php
		foreach ( $internships as $post ) :
			setup_postdata( $post );
	?>

			<div id="post-<?php the_ID(); ?>" <?php post_class('careers__article'); ?>>

				<a href="<?php the_permalink(); ?>" rel="bookmark" class="block">

					<header class="careers__header--article">
						<h2 class="careers__title"><?php the_title(); ?></h2>
					</header>
					<!-- // .careers__header—article -->

					<div class="careers__summary">
						<?php // We don't want sharing links here, exactly. ?>
						<?php remove_filter( 'the_excerpt', 'sharing_display', 19 ); ?>
						<?php the_excerpt(); ?>
					</div>
					<!-- // .careers__summary -->

				</a>

			</div>
			<!-- #post-## -->

	<?php endforeach; ?>

		</div>
		<!-- // .careers__row -->

	</section>
	<!-- // .careers -->

	<?php endif; wp_reset_postdata(); ?>

</article>
<!-- #post-## -->

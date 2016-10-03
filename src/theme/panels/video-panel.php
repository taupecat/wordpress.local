<?php
/**
 * Video Panel.
 *
 * @package RP3
 */

/** Get the video embed code and assign an ID to it */

if ( '' !== get_sub_field( 'video_link' ) ) {


	$video = get_sub_field( 'video_link' );

	preg_match( '/src="(.+?)"/', $video, $matches );
	$video_src = $matches[1];

	// Create an id off of the MD5 checksum of the Vimeo source string

	$hash = md5( $video_src );


	// Vimeo

	if ( preg_match( '/vimeo/', $video_src ) ) {

		// Add additional parameters for autoplay and the API

		$params = array(
			'title'			=> 0,
			'byline'		=> 0,
			'portrait'		=> 0,
			'player_id'		=> $hash . '__iframe',
			'platform'		=> 'vimeo'
		);

		$new_src = add_query_arg( $params, $video_src );

		$video = str_replace( $video_src, $new_src, $video );

		// Add the ID to the iframe

		$attributes = 'id="' . $hash . '__iframe"';

		$video = str_replace( '></iframe>', ' ' . $attributes . '></iframe>', $video );

		// Create an ordinary link to the video on Vimeo

	// YouTube

	} elseif ( preg_match( '/youtube/', $video_src ) ) {

		$params = array(
			'player_id'		=> $hash . '__iframe',
			'enablejsapi'	=> 1,
			'platform'		=> 'youtube'
		);

		$new_src = add_query_arg( $params, $video_src );

		$video = str_replace( $video_src, $new_src, $video );

		// Add the ID to the iframe

		$attributes = 'id="' . $hash . '__iframe"';

		$video = str_replace( '></iframe>', ' ' . $attributes . '></iframe>', $video );

		// Create an ordinary link to the video on YouTube
		$video_src = str_replace( '/embed/', '/watch?v=', $video_src );
		$video_src = str_replace( '?feature=oembed', '', $video_src );
	}
}
?>

<!-- Video Panel -->

<?php if ( ( '' !== get_sub_field( 'video_link' ) ) || ( '' != get_sub_field( 'full-width-image' ) ) ) : ?>

	<section id="<?php echo esc_attr( $hash ); ?>" class="video-panel panel video-panel__trigger">

		<a href="<?php echo esc_url( $video_src ); ?>" id="<?php echo esc_attr( $hash . '__trigger' ); ?>" data-id="<?php echo esc_attr( $hash ); ?>" class="video-panel__image video__trigger block <?php echo esc_attr( $params['platform'] ); ?>">

			<?php if ( '' != get_sub_field( 'full-width-image' ) ) : ?>

				<div class="video-panel__image__content">

					<?php
					$image['small'] = wp_get_attachment_image_src( get_sub_field( 'full-width-image' ), 'sixteen_nine_small' );
					$image['small_2x'] = wp_get_attachment_image_src( get_sub_field( 'full-width-image' ), 'sixteen_nine_small_2x' );

					$image['medium'] = wp_get_attachment_image_src( get_sub_field( 'full-width-image' ), 'sixteen_nine_medium' );
					$image['medium_2x'] = wp_get_attachment_image_src( get_sub_field( 'full-width-image' ), 'sixteen_nine_medium_2x' );

					$image['large'] = wp_get_attachment_image_src( get_sub_field( 'full-width-image' ), 'sixteen_nine_large' );
					$image['large_2x'] = wp_get_attachment_image_src( get_sub_field( 'full-width-image' ), 'sixteen_nine_large_2x' );
					?>

					<picture>
						<source srcset="<?php echo esc_url( $image['large'][0] ); ?>, <?php echo esc_url( $image['large_2x'][0] ); ?> 2x" media="(min-width: 37.5rem)" />
						<source srcset="<?php echo esc_url( $image['medium'][0] ); ?>, <?php echo esc_url( $image['medium_2x'][0] ); ?> 2x" media="(min-width: 20.0625rem)" />
						<source srcset="<?php echo esc_url( $image['small'][0] ); ?>, <?php echo esc_url( $image['small_2x'][0] ); ?> 2x" />
						<img srcset="<?php echo esc_url( $image['small'][0] ); ?>, <?php echo esc_url( $image['small_2x'][0] ); ?> 2x" />
					</picture>

				</div>
				<!-- video-panel image content -->

			<?php endif; ?>

			<?php if ( '' !== get_sub_field( 'video_link' ) ) : ?>

				<div class="video-panel__play-button">

					<?php get_template_part( 'template-parts/inline', 'icon-video-play.svg' ); ?>

				</div>

			<?php endif; ?>

		</a>
		<!-- video-panel image -->

		<?php if ( '' !== get_sub_field( 'video_link' ) ) : ?>

			<div id="<?php echo esc_attr( $hash . '__modal' ); ?>" class="video-panel__modal">

				<?php echo $video; ?>

			</div>

		<?php endif; ?>

		<?php if ( '' !== get_sub_field( 'video_caption' ) ) : ?>

			<div class="video-panel__caption">

				<div class="video-panel__caption__container">

					<?php the_sub_field( 'video_caption' ); ?>

				</div>

			</div>

		<?php endif; ?>

	</section>
	<!-- full-width-image-panel -->

<?php endif; ?>

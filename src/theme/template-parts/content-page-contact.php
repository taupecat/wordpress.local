<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package RP3
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'contact' ); ?>>

	<div class="contact__wrapper">

		<section class="contact__content entry-content">

			<?php the_content(); ?>

			<div class="contact__info-and-form">

				<section class="contact__info">


					<?php // Work With Us ?>

					<?php if ( '' != get_field( 'new_business_email' ) ) : ?>

						<div class="contact__info__new-business">

							<h2 class="contact__header">Work With Us</h2>

							<p class="contact__info__email">Jim Lansbury<br />
							<a href="mailto:jlansbury@rp3agency.com">jlansbury@rp3agency.com</a></p>

							<p class="contact__info__email">Beth Johnson<br />
							<a href="mailto:bjohnson@rp3agency.com">bjohnson@rp3agency.com</a></p>

						</div>

					<?php endif; ?>


					<div class="contact__info__media-inquiries">

						<h2 class="contact__header">Media Inquiries</h2>

						<p class="contact__info__email">Chris Finnegan<br />
						<a href="mailto:cfinnegan@rp3agency.com">cfinnegan@rp3agency.com</a></p>

					</div>


					<?php // General Inquiries ?>

					<?php if ( '' != get_field( 'general_inquiries_email' ) ) : ?>

						<div class="contact__info__general-inquiries">

							<h2 class="contact__header">General Inquiries</h2>

							<p class="contact__info__email"><a href="<?php echo esc_url( 'mailto:' . get_field( 'general_inquiries_email' ), array( 'mailto' ) ); ?>"><?php echo esc_html( get_field( 'general_inquiries_email' ) ); ?></a></p>

						</div>

					<?php endif; ?>



					<?php // Careers ?>

					<?php if ( '' != get_field( 'careers_email' ) ) : ?>

						<div class="contact__info__careers">

							<h2 class="contact__header">Careers</h2>

							<p class="contact__info__email"><a href="<?php echo esc_url( 'mailto:' . get_field( 'careers_email' ), array( 'mailto' ) ); ?>"><?php echo esc_html( get_field( 'careers_email' ) ); ?></a></p>

						</div>

					<?php endif; ?>



					<?php // Social Media ?>

					<div class="contact__info__social">

						<h2 class="contact__header">Social</h2>

						<?php wp_nav_menu( array(
							'theme_location'	=> 'contact-social',
							'menu_class'		=> 'social'
						) ); ?>

					</div>
					<!-- // .contact__info__social -->



					<?php // Address ?>

					<div class="contact__info__address">

						<h2 class="contact__header">Address</h2>

						<p>7316 Wisconsin Ave.<br />
						Suite 450<br />
						Bethesda, Maryland 20814</p>

					</div>



					<?php // Telephone & Fax ?>

					<?php if ( ( '' != get_field( 'telephone' ) ) && ( '' != get_field( 'fax' ) ) ) : ?>

						<div class="contact__info__phone-fax">

							<h2 class="contact__header">Phone & Fax</h2>

							<p class="contact__info__phone-fax-numbers">
								t. <?php echo esc_html( get_field( 'telephone' ) ); ?><br>
								f. <?php echo esc_html( get_field( 'fax' ) ); ?>

							</p>

						</div>

					<?php endif; ?>



					<?php // Fact Sheet ?>

					<?php if ( '' != get_field( 'fact_sheet' ) ) : ?>

						<div class="contact__info__fact-sheet">

							<h2 class="contact__header">Fact Sheet</h2>

							<p class="contact__info__fact-sheet-link"><a href="<?php echo esc_url( get_field( 'fact_sheet' ) ); ?>">Download our fact sheet.</a></p>

						</div>

					<?php endif; ?>

				</section>
				<!-- // .contact__info -->

				<section class="contact__form" id="contact__form">

					<?php echo do_shortcode( '[contact-form-7 id="8285" title="Contact Form"]' ); ?>

				</section>
				<!-- // .contact__form -->

			</div>
			<!-- // .contact__info-and-form -->

		</section>
		<!-- // .contact__content -->

	</div>
	<!-- // .contact__wrapper -->


	<section id="map" class="contact__map">

		<div id="contact__map" class="contact__map__container"></div>

	</section>
	<!-- // .contact__map -->

</article>

<?php

get_header(); ?>

<main class="site-main wrapper">
	<div class="site-content">
		<!-- <h1 class="site-title">Startseite</h1> -->

			<!-- PLATZHALTER FÜR STORE HOURS

			<div class="">
				<?php // include ('classes/storeHours/open-closed-sign.php'); ?>
			</div>
			-->


		<section id="news-posts" class="news-posts section-container container--white wrapper" data-matching-link="#news-posts-link">
			<h2 class="news-posts__headline h2__section-headline">Neuheiten, die faszinieren</h2>

			<div class="intro-container wrapper">
				<?php

				$args = array(
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'post_type' => 'homepage-section',
					'p' => 198,
				);

				$loop = new WP_Query( $args );
					while ( $loop->have_posts() ) : $loop->the_post(); ?>
						<?php the_content();?>
					<?php endwhile; ?>

				<?php
				wp_reset_postdata();
				?>

			</div>

			<?php echo do_shortcode("[shortcode_news_posts]"); ?>

		</section>


		<section id="featured-posts" class="featured-posts section-container" data-matching-link="#featured-posts-link">
			<h2 class="featured-posts__headline h2__section-headline">Lieblingsprodukte, die Freude bereiten</h2>

			<div class="intro-container wrapper">
				<?php

				$args = array(
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'post_type' => 'homepage-section',
					'p' => 196,
				);

				$loop = new WP_Query( $args );
					while ( $loop->have_posts() ) : $loop->the_post(); ?>
						<?php the_content();?>
					<?php endwhile; ?>

				<?php
				wp_reset_postdata();
				?>

			</div>

			<?php echo do_shortcode("[shortcode_featured_posts]"); ?>

		</section>


		<?php echo do_shortcode("[shortcode_product_categories]"); ?>

		<section id="services" class="services section-container container--black wrapper" data-matching-link="#services-link">
			<h2 class="services__headline h2__section-headline">Leistungen</h2>

			<div class="services__container">

				<div class="services__intro wrapper">

					<?php

					$args = array(
						'post_status' => 'publish',
						'posts_per_page' => -1,
						'post_type' => 'homepage-section',
						'p' => 204,
					);

					$loop = new WP_Query( $args );
						while ( $loop->have_posts() ) : $loop->the_post(); ?>
							<?php the_content();?>
						<?php endwhile; ?>

					<?php
					wp_reset_postdata();
					?>

				</div>

				<article class="services__card card">
					<div class="services__card-container card__container">
						<a class="services__link" href="/leistungen#verkauf">
							<p class="services__pretitle card__pretitle">Kompetenz trifft Liebe zum Produkt</p>
							<h3 class="services__title card__title">Verkauf</h3>
							<div class="services__content card__content">
								<img class="services__thumbnail--full services__thumbnail card__thumbnail" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/services/services-img_verkauf.png" alt="Bereich Verkauf">
							</div>
						</a>
					</div>
				</article>

				<article class="services__card card">
					<div class="services__card-container card__container">
						<a class="services__link" href="/leistungen#service-hardware">
							<p class="services__pretitle card__pretitle">Reparaturen vom Profi</p>
							<h3 class="services__title card__title">Service Hardware</h3>
							<div class="services__content card__content">
								<img class="services__thumbnail--full services__thumbnail card__thumbnail" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/services/services-img_service-hardware.png" alt="Bereich Service Hardware">
							</div>
						</a>
					</div>
				</article>

				<article class="services__card card">
					<div class="services__card-container card__container">
						<a class="services__link" href="/leistungen#serivice-software">
							<p class="services__pretitle card__pretitle">Erfahrung nutzbar gemacht</p>
							<h3 class="services__title card__title">Software Service</h3>
								<div class="services__content card__content">
									<img class="services__thumbnail--full services__thumbnail card__thumbnail" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/services/services-img_service-software.png" alt="Bereich Service Software">
							</div>
						</a>
					</div>
				</article>

				<article class="services__card card">
					<div class="services__card-container card__container">
						<a class="services__link" href="/leistungen#consulting">
							<p class="services__pretitle card__pretitle">Fachwissen auf Abruf</p>
							<h3 class="services__title card__title">Consulting</h3>
							<div class="services__content card__content">
								<img class="services__thumbnail--full services__thumbnail card__thumbnail" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/services/services-img_consulting.png" alt="Bereich Consulting">
							</div>
						</a>
					</div>
				</article>

			</div>

		</section>

		<section id="faq" class="faq section-container wrapper" data-matching-link="#faq-link">
			<h2 class="faq__headline h2__section-headline">Fragen & Antworten</h2>

				<?php

				$args = array(
					'post_status' => 'publish',
					'posts_per_page' => 1,
					'post_type' => 'homepage-section',
					'p' => 206,
				);

				$loop = new WP_Query( $args );
					while ( $loop->have_posts() ) : $loop->the_post(); ?>
						<div class="faq__intro intro">
							<?php the_content();?>
						</div>
					<?php endwhile; ?>

				<?php
				wp_reset_postdata();
				?>

			<?php echo do_shortcode("[shortcode_faqs]"); ?>

		</section>

		<section id="about-us" class="about-us section-container wrapper" data-matching-link="#faq-link">
			<h2 class="about-us__headline h2__section-headline">Über uns</h2>

			<div class="about-us__container wrapper">

				<?php

				$args = array(
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'post_type' => 'homepage-section',
					'p' => 208,
				);

				$loop = new WP_Query( $args );
					while ( $loop->have_posts() ) : $loop->the_post(); ?>
						<?php the_content();?>
					<?php endwhile; ?>

				<?php
				wp_reset_postdata();
				?>

			</div>

		</section>

		<?php echo do_shortcode('[shortcode_error_found]');  ?>

	</div>
</main>

<?php get_footer(); ?>
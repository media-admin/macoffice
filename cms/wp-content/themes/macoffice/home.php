<?php

get_header(); ?>

<main class="site-main wrapper">
	<div class="site-content">
		<!-- <h1 class="site-title">Startseite</h1> -->

		<section id="featured-posts" class="featured-posts section-container" data-matching-link="#featured-posts-link">
			<h2 class="featured-posts__headline h2__section-headline">Themen im Fokus</h2>

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

		<section id="news-posts" class="news-posts section-container container--white wrapper" data-matching-link="#news-posts-link">
			<h2 class="news-posts__headline h2__section-headline">News</h2>

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

		<section id="product-categories" class="product-categories section-container wrapper" data-matching-link="#product-categories-link">
			<h2 class="product-categories__headline h2__section-headline">Produktfamilien</h2>

			<div class="intro-container">

				<?php

				$args = array(
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'post_type' => 'homepage-section',
					'p' => 200,
				);

				$loop = new WP_Query( $args );
					while ( $loop->have_posts() ) : $loop->the_post(); ?>
						<?php the_content();?>
					<?php endwhile; ?>

				<?php
				wp_reset_postdata();
				?>

			</div>

			<div class="product-categories__container">

				<div class="product-categories__card card">
					<a class="product-categories__link" href="https://www.apple.com/at/mac/" target="_blank">
						<div class="card__container wrapper">
							<img class="product-categories__img" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/product-categories/product-category_mac@2x_web.png"/>
							<div class="product-categories__content card__content">
								<h4  class="product-categories__title card__title">Mac</h4>
							</div>
						</div>
					</a>
				</div>

				<div class="product-categories__card card">
					<a class="product-categories__link" href="<?php bloginfo( 'template_directory' ); ?>/https://www.apple.com/at/iphone/" target="_blank">
						<div class="card__container wrapper">
							<img class="product-categories__img--full" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/product-categories/product-category_iphone@2x_web.png"/>
							<div class="product-categories__content card__content">
								<h4  class="product-categories__title card__title">iPhone</h4>
							</div>
						</div>
					</a>
				</div>

				<div class="product-categories__card card">
					<a class="product-categories__link" href="https://www.apple.com/at/ipad/" target="_blank">
						<div class="card__container wrapper">
							<img class="product-categories__img--full" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/product-categories/product-category_ipad@2x_web.png"/>
							<div class="product-categories__content card__content">
								<h4  class="product-categories__title card__title">iPad</h4>
							</div>
						</div>
					</a>
				</div>

				<div class="product-categories__card card">
					<a class="product-categories__link" href="https://www.apple.com/at/watch/" target="_blank">
						<div class="card__container wrapper">
							<img class="product-categories__img--full" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/product-categories/product-category_watch@2x_web.png"/>
							<div class="product-categories__content card__content">
								<h4  class="product-categories__title card__title">Apple Watch</h4>
							</div>
						</div>
					</a>
				</div>

				<div class="product-categories__card card">
					<a class="product-categories__link" href="https://www.apple.com/at/airpods/" target="_blank">
						<div class="card__container wrapper">
							<img class="product-categories__img--full" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/product-categories/product-category_airpods@2x_web.png"/>
							<div class="product-categories__content card__content">
								<h4  class="product-categories__title card__title">AirPods</h4>
							</div>
						</div>
					</a>
				</div>

				<div class="product-categories__card card">
					<a class="product-categories__link" href="https://www.apple.com/at/airtag/" target="_blank">
						<div class="card__container wrapper">
							<img class="product-categories__img--full" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/product-categories/product-category_airtag@2x_web.png"/>
							<div class="product-categories__content card__content">
								<h4  class="product-categories__title card__title">AirTag</h4>
							</div>
						</div>
					</a>
				</div>

				<div class="product-categories__card card">
					<a class="product-categories__link" href="https://www.apple.com/at/apple-tv-4k/" target="_blank">
						<div class="card__container wrapper">
							<img class="product-categories__img--full" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/product-categories/product-category_appletv@2x_web.png"/>
							<div class="product-categories__content card__content">
								<h4  class="product-categories__title card__title">Apple TV</h4>
							</div>
						</div>
					</a>
				</div>

				<div class="product-categories__card card">
					<a class="product-categories__link" href="https://www.apple.com/at/homepod-mini/" target="_blank">
						<div class="card__container wrapper">
							<img class="product-categories__img--full" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/product-categories/product-category_homepod-mini@2x_web.png"/>
							<div class="product-categories__content card__content">
								<h4  class="product-categories__title card__title">HomePod mini</h4>
							</div>
						</div>
					</a>
				</div>

				<div class="product-categories__card card">
					<a class="product-categories__link" href="https://www.apple.com/at/shop/accessories/all" target="_blank">
						<div class="card__container wrapper">
							<img class="product-categories__img--full" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/product-categories/product-category_accessories@2x_web.png"/>
							<div class="product-categories__content card__content">
								<h4  class="product-categories__title card__title">Zubehör</h4>
							</div>
						</div>
					</a>
				</div>

				<div class="product-categories__card card">
					<a class="product-categories__link" href="https://www.apple.com/at/shop/gift-cards" target="_blank">
						<div class="card__container wrapper">
							<img class="product-categories__img--full" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/product-categories/product-category_apple-gift-cards@2x_web.png"/>
							<div class="product-categories__content card__content">
								<h4  class="product-categories__title card__title">Apple Gift Card</h4>
							</div>
						</div>
					</a>
				</div>

			</div>

		</section>

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
						<a class="services__link" href="/beratung-consulting">
							<p class="services__pretitle card__pretitle">Vor Ort oder Remote</p>
							<h3 class="services__title card__title">Beratung & Consulting</h3>
							<div class="services__content card__content">
								<img class="services__thumbnail--full services__thumbnail card__thumbnail" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/homepage/img_beratung-und-consulting@0.5x_web.png" alt="">
							</div>
						</a>
					</div>
				</article>

				<article class="services__card card">
					<div class="services__card-container card__container">
						<a class="services__link" href="/service-support">
							<p class="services__pretitle card__pretitle">5 Tage die Woche</p>
							<h3 class="services__title card__title">Service & Support</h3>
							<div class="services__content card__content">
								<img class="services__thumbnail--full services__thumbnail card__thumbnail" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/homepage/img_service-und-support@0.5x_web.png" alt="Alt Text">
							</div>
						</a>
					</div>
				</article>

				<article class="services__card card">
					<div class="services__card-container card__container">
						<a class="services__link" href="/reparatur-garantie">
							<p class="services__pretitle card__pretitle">Wir wickeln für Sie ab</p>
							<h3 class="services__title card__title">Reparatur & Garantie</h3>
								<div class="services__content card__content">
									<img class="services__thumbnail--full services__thumbnail card__thumbnail" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/homepage/img_reparatur-und-garantie@0.5x_web.png" alt="Alt Text">
							</div>
						</a>
					</div>
				</article>

				<article class="services__card card">
					<div class="services__card-container card__container">
						<a class="services__link" href="/finanzierung">
							<p class="services__pretitle card__pretitle">Mit einem starken Partner</p>
							<h3 class="services__title card__title">Finanzierung</h3>
							<div class="services__content card__content">
								<img class="services__thumbnail--full services__thumbnail card__thumbnail" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/homepage/img_finanzierung@0.5x_web.png" alt="Alt Text">
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

	</div>
</main>

<?php get_footer(); ?>
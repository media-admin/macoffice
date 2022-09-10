<div class="services__container">

	<div class="wrapper">

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
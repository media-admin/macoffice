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
			<div class="services__card-header">
				<div class="services__card-title-container">
					<a class="services__link" href="/leistungen#verkauf">
						<p class="services__pretitle card__pretitle">Kompetenz trifft Liebe zum Produkt</p>
						<h3 class="services__title card__title">Verkauf</h3>
					</a>
				</div>
				<div class="services__card-apple-role-container">
					<a class="services_card-apple-role-link" href="https://locate.apple.com/at/de/sales?pt=4&lat=47.8162109&lon=16.2386804&address=2700+Wiener+Neustadt%2C+Österreich" target="_blank">
						<img class="services_card-apple-role-logo" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/Logo_Apple-Autorisierter-Haendler--white.svg" alt="Logo Apple Autorisierter Händler">
					</a>
				</div>
			</div>
			<div class="services__content card__content">
				<img class="services__thumbnail--full services__thumbnail card__thumbnail" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/services/services-img_verkauf.png" alt="Bereich Verkauf">
			</div>
		</div>
	</article>

	<article class="services__card card">
		<div class="services__card-container card__container">
			<div class="services__card-header">
				<div class="services__card-title-container">
					<a class="services__link" href="/leistungen#service-hardware">
						<p class="services__pretitle card__pretitle">Reparaturen vom Profi</p>
						<h3 class="services__title card__title">Service Hardware</h3>
					</a>
				</div>
				<div class="services__card-apple-role-container">
					<a class="services_card-apple-role-link" href="https://getsupport.apple.com/repair-locations/details" target="_blank">
						<img class="services_card-apple-role-logo" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/Logo_Apple-Autorisierter-Service-Provider--white.svg" alt="Logo Apple Autorisierter Service Provider">
					</a>
				</div>
			</div>
			<div class="services__content card__content">
				<img class="services__thumbnail--full services__thumbnail card__thumbnail" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/services/services-img_service-hardware.png" alt="Bereich Service Hardware">
			</div>
		</div>
	</article>

	<article class="services__card card">
		<div class="services__card-container card__container">
			<div class="services__card-header">
				<div class="services__card-title-container">
					<a class="services__link" href="/leistungen#service-software">
						<p class="services__pretitle card__pretitle">Erfahrung nutzbar gemacht</p>
						<h3 class="services__title card__title">Software Service</h3>
					</a>
				</div>
				<div class="services__card-apple-role-container">
					<a class="services_card-apple-role-link" href="https://getsupport.apple.com/repair-locations/details" target="_blank">
						<img class="services_card-apple-role-logo" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/Logo_Apple-Autorisierter-Service-Provider--white.svg" alt="Logo Apple Autorisierter Service Provider">
					</a>
				</div>
			</div>
			<div class="services__content card__content">
				<img class="services__thumbnail--full services__thumbnail card__thumbnail" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/services/services-img_service-software.png" alt="Bereich Service Hardware">
			</div>
		</div>
	</article>

	<article class="services__card card">
		<div class="services__card-container card__container">
			<div class="services__card-header">
				<div class="services__card-title-container">
					<a class="services__link" href="/leistungen#consulting">
						<p class="services__pretitle card__pretitle">Fachwissen auf Abruf</p>
						<h3 class="services__title card__title">Consulting</h3>
					</a>
				</div>
				<div class="services__card-apple-role-container">
					<a class="services_card-apple-role-link" href="https://consultants.apple.com/at/profile/2007879" target="_blank">
						<img class="services_card-apple-role-logo" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/Logo_Apple-Consultants-Network--white.svg" alt="Logo Apple Consultants Network">
					</a>
				</div>
			</div>
			<div class="services__content card__content">
				<img class="services__thumbnail--full services__thumbnail card__thumbnail" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/services/services-img_consulting.png" alt="Bereich Service Hardware">
			</div>
		</div>
	</article>

</div>
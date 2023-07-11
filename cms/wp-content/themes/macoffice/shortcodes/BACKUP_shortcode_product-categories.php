<section id="product-categories" class="product-categories section-container wrapper" data-matching-link="#product-categories-link">
	<h2 class="product-categories__headline h2__section-headline"><span>Produktfamilien<span></h2>

	<div class="wrapper">

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

	</div>

</section>
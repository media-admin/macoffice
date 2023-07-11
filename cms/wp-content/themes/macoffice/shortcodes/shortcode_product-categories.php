<section id="product-categories" class="product-categories section-container wrapper" data-matching-link="#product-categories-link">
	<h2 class="product-categories__headline h2__section-headline"><span>Produktfamilien<span></h2>

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

		<?php
			$args = array(
		 		'taxonomy' => 'product_cat',
		 		'orderby' => 'name',
				'show_count'   => false, // true or false
				'pad_counts'   => false, // true or false
				'hierarchical' => false, // true or false
				'title_li'     => '',
				'hide_empty'   => true, // true or false
		 		'order' => 'ASC'
			);

			$product_categories = get_categories( $args );

			foreach ($product_categories as $category) {
				if($category->category_parent == 0) { //this checks for 1st level that you wanted
					echo '<div class="product-categories__card card">';
						echo '<a class="product-categories__link" href="' . get_term_link( $category->slug, $category->taxonomy ) . '">';
							echo '<div class="card__container wrapper">';
									$cat_thumb_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
									$cat_thumb_url = wp_get_attachment_thumb_url( $cat_thumb_id );
								echo '<img class="product-categories__img" src="' . $cat_thumb_url . '" alt="" />';
								echo '<div class="product-categories__content card__content">';
									echo '<h4  class="product-categories__title card__title">' . $category->name . '</h4>';
								echo '</div>';
							echo '</div>';
						echo '</a>';
					echo '</div>';
				}
			}
		wp_reset_postdata();
		?>

	</div>
</section>
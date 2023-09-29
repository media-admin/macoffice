<div class="default-posts__container wrapper">

	<?php

	$args = array(
		'post_status' => 'publish',
		'posts_per_page' => 6,
		'post_type' => 'post',
		'category_name' => 'allgemein',

	);

	$loop = new WP_Query( $args );

		while ( $loop->have_posts() ) : $loop->the_post(); ?>

			<article class="default-posts__card card">
				<a href="<?php the_permalink(); ?>">
					<div class="card__container wrapper">
						<p class="default-posts__pretitle card__pretitle"><?php the_field('post-pretitle');?></p>
						<h3 class="default-posts__title default-posts__title card__title"><?php the_title();?></h3>
						<p class="default-posts__subtitle card__subtitle"><?php the_field('post-subtitle');?></p>
						<div class='dotted line--dotted card__line--dotted'></div>
						<?php the_post_thumbnail('full', ['class' => 'default-posts__thumbnail card__thumbnail']); ?>
						<div class="default-posts__content card__content">
							<?php the_excerpt();?>
						</div>
					</div>
				</a>
			</article>

	<?php endwhile; ?>

	<?php
	wp_reset_postdata();
	?>

</div>

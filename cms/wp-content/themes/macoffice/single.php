<?php

get_header(); ?>

<main class="site-main wrapper">
	<div class="site-content wrapper">
		<p class="featured-posts__pretitle card__pretitle"><?php the_field('post-pretitle');?></p>
		<h1 class="title--single">
			<?php the_title();?>
		</h1>
		<p class="featured-posts__subtitle card__subtitle"><?php the_field('post-subtitle');?></p>
		<div class='dotted line--dotted card__line--dotted'></div>
		<?php the_post_thumbnail('full', ['class' => 'posts__thumbnail thumbnail--full']); ?>
		<?php the_content(); ?>
	</div>
</main>

<?php get_footer(); ?>
<?php

get_header(); ?>

<main class="site-main wrapper">
	<div class="site-content wrapper">

		<?php the_post_thumbnail('full', ['class' => 'posts__thumbnail thumbnail--full']); ?>

		<h1 class="">
			<?php the_title();?>
		</h1>

		<?php
			the_content();
		?>

	</div>
</main>

<?php get_footer(); ?>
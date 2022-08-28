<?php

get_header(); ?>

<main class="site-main wrapper">
	<div class="site-content">

		<h1 class="">
			<?php the_title();?>
		</h1>

		<?php
			the_content();
		?>

	</div>
</main>

<?php get_footer(); ?>
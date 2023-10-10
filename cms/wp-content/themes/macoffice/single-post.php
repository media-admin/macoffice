<?php

get_header(); ?>

<main class="site-main wrapper">
	<div class="site-content wrapper">
		<div class="post-intro__wrapper">
			<div class="post-thumbnail__wrapper">
				<?php the_post_thumbnail('large', ['class' => 'posts__thumbnail thumbnail--full']); ?>
			</div>

			<div class="site-heading__wrapper">
				<p class="featured-posts__pretitle card__pretitle"><?php the_field('post-pretitle');?></p>
				<h1 class="title--single">
					<?php the_title();?>
				</h1>
				<p class="featured-posts__subtitle card__subtitle"><?php the_field('post-subtitle');?></p>
				<div class='dotted line--dotted card__line--dotted'></div>
			</div>
		</div>

		<?php the_content(); ?>

		<div class="post-navigatiooon">
			<hr class="hr__post-navigation hr__post-navigation--before"/>
			<div class="post-navigation">

				<div class="column is-half has-text-right">
					<?php next_post_link( '<small class="is-size-7 is-family-monospace has-text-grey">Zum nächsten Beitrag</small><br><p class="link link--next">%link</p>' ); ?>
				</div>

				<div class="column is-half">
					<?php previous_post_link( '<small class="is-size-7 is-family-monospace has-text-grey">Zum vorherigen Beitrag</small><br><p class="link link--previous">%link</p>' ); ?>
				</div>
			</div>
			<hr class="hr__post-navigation hr__post-navigation--after"/>
		</div>

	</div>
</main>

<?php get_footer(); ?>
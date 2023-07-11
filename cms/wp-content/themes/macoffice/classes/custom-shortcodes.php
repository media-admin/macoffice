<?php

function shortcode_faqs() {
	ob_start();
	get_template_part( 'shortcodes/shortcode_faqs');
	return ob_get_clean();
}

add_shortcode( 'shortcode_faqs', 'shortcode_faqs' );


function shortcode_news_posts() {
	ob_start();
	get_template_part( 'shortcodes/shortcode_posts-news');
	return ob_get_clean();
}

add_shortcode( 'shortcode_news_posts', 'shortcode_news_posts' );


function shortcode_featured_posts() {
	ob_start();
	get_template_part( 'shortcodes/shortcode_posts-featured');
	return ob_get_clean();
}

add_shortcode( 'shortcode_featured_posts', 'shortcode_featured_posts' );


function shortcode_business_posts() {
	ob_start();
	get_template_part( 'shortcodes/shortcode_posts-business');
	return ob_get_clean();
}

add_shortcode( 'shortcode_business_posts', 'shortcode_business_posts' );


function shortcode_private_posts() {
	ob_start();
	get_template_part( 'shortcodes/shortcode_posts-private');
	return ob_get_clean();
}

add_shortcode( 'shortcode_private_posts', 'shortcode_private_posts' );


function shortcode_product_categories() {
	ob_start();
	get_template_part( 'shortcodes/shortcode_product-categories');
	return ob_get_clean();
}

add_shortcode( 'shortcode_product_categories', 'shortcode_product_categories' );


function shortcode_services_overview() {
	ob_start();
	get_template_part( 'shortcodes/shortcode_services-overview');
	return ob_get_clean();
}

add_shortcode( 'shortcode_services_overview', 'shortcode_services_overview' );


function shortcode_google_maps() {
	ob_start();
	get_template_part( 'shortcodes/shortcode_google-maps');
	return ob_get_clean();
}

add_shortcode( 'shortcode_google_maps', 'shortcode_google_maps' );


function shortcode_youtube_video() {
	ob_start();
	get_template_part( 'shortcodes/shortcode_youtube-video');
	return ob_get_clean();
}

add_shortcode( 'shortcode_youtube_video', 'shortcode_youtube_video' );


function shortcode_leasing_calculator() {
	ob_start();
	get_template_part( 'shortcodes/shortcode_leasing-calculator');
	return ob_get_clean();
}

add_shortcode( 'shortcode_leasing_calculator', 'shortcode_leasing_calculator' );



function show_tags()
{
	$post_tags = get_the_tags();
	$separator = ' | ';
	if (!empty($post_tags)) {
		foreach ($post_tags as $tag) {
			$output .= '<a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</a>' . $separator;
		}
		return trim($output, $separator);
	}
}



function all_posts_shortcode() {

	// Parameter für Posts
	$args = array(
		'category' => '',
		'numberposts' => 6,
		'post_status' => 'publish',
		'orderby'   => 'id',
		'order' => 'ASC',
	);

	//Posts holen
	$posts = get_posts($args);

	//Inhalte sammeln
	$content = '';
	foreach ($posts as $post) {

		$content .= '<div class="card column is-one-third">';
		$content .= '<div class="card-image">';
		$content .= '<a class="" href="'.get_permalink($post->ID).'">';
		$content .= '<figure class="image">';
		$content .= '<img  alt="Beitragsbild" src="'.get_the_post_thumbnail_url($post->ID, 'full').'"';
		$content .= '</figure>';
		$content .= '</a>';
		$content .= '</div>';
		$content .= '<div class="card-content">';
		$content .= '<div class="media">';
		$content .= '<div class="media-content">';
		$content .= '<p class="author is-6">Veröffentlicht am <span class="meta__date-published"><time datetime="d.m.Y">'.get_post_time('d.m.Y', $post->ID ).'</time></span></p>';
		$content .= '<a class="" href="'.get_permalink($post->ID).'"><p class="title is-4">'.$post->post_title.'</p></a>';
		$content .= '<p class="author is-6">Verfasser <span class="meta__author">'.get_the_author($post->ID).'</span></p>';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '<div class="content">';
		$content .= '<small class="tags">';
		$content .= '<div class="tags">';
		$content .= '<code class="tag is-danger">';
		$content .= '<i class="fas fa-tags"></i>';
		$content .= '</code>';

		$post_tags = get_the_tags($post->ID);


		if (!empty($post_tags)) {
			foreach ($post_tags as $tag) {
				$content .= '<span class="tag"><a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</a></span>';
			}
		}

		$content .= '</div>';
		$content .= '</small>';
		$content .= '</div>';
		$content .= '</div>';
		$content .= '</div>';

	}

	//Inhalte übergeben
	return $content;

}

add_shortcode( 'all_posts', 'all_posts_shortcode' );

?>
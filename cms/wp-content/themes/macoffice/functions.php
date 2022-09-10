<?php
/**
 * Theme Funktionen und allgemeine Definitionen für die Website "macoffice.at"
 */

/* --- General Theme Functions --- */
function macoffice_theme_features() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-formats', array( 'gallery' ) );
}

add_action('initafter_setup_theme', 'macoffice_theme_features');



/* --- Make default excerpt available --- */
function macoffice_add_excerpts_to_pages() {
		add_post_type_support( 'page', 'excerpt' );
}

add_action( 'init', 'macoffice_add_excerpts_to_pages' );


/* --- Make Custom Background available --- */
add_theme_support( 'custom-background' );


/* --- Activate Thumbnail Images --- */
if ( ! function_exists( 'theme_slug_setup' ) ) :
 function theme_slug_setup() {
	add_theme_support( 'post-thumbnails' );
}
endif;
add_action( 'after_setup_theme', 'theme_slug_setup' );


/* --- Includes the Custom Shortcode Bibliothek of the actual Theme --- */
include( 'classes/custom-shortcodes.php' );


/* --- Support SVG Files --- */
function macoffice_add_upload_ext($checked, $file, $filename, $mimes){
	if(!$checked['type']){
		$wp_filetype = wp_check_filetype( $filename, $mimes );
		$ext = $wp_filetype['ext'];
		$type = $wp_filetype['type'];
		$proper_filename = $filename;

		if($type && 0 === strpos($type, 'image/') && $ext !== 'svg'){
			$ext = $type = false;
		}
		$checked = compact('ext','type','proper_filename');
	}
	return $checked;
}

add_filter('wp_check_filetype_and_ext', 'macoffice_add_upload_ext', 10, 4);





/* --- Defines the default expression for the "Read More"-Link --- */
function macoffice_read_more_text( $text, $post_id ) {
	return '<a class="more-link" href="' . get_permalink() . '">' . __( 'Read More' , 'macoffice' ) . '</a>';
}

add_filter( 'the_content_more_link', 'macoffice_read_more_text', 10, 2 );


/* --- Replaces the IP address at comments (IP-Anonymisierung lt. DSGVO) --- */
function macoffice_replace_comment_ip() {
	 return "127.0.0.1";
}

add_filter( 'pre_comment_user_ip', 'macoffice_replace_comment_ip', 50);


/* --- Adds the Slug to the body tag's class --- */
function macoffice_add_slug_body_class( $classes ) {
	 global $post;
	if ( isset( $post ) ) {
	 $classes[] = $post->post_name;
	}
	return $classes;
}

add_filter( 'body_class', 'macoffice_add_slug_body_class' );



/* === Styles and Scripts === */
function macoffice_register_styles() {

	// Import Cookie Script Stylesheets
	wp_register_style( 'cookie-style', get_template_directory_uri() . '/assets/styles/dywc.css' );
	wp_enqueue_style( 'cookie-style' );

	/* --- Import Cookie Script Stylesheets --- */
	wp_register_style( 'custom-styles', get_template_directory_uri() . '/assets/styles/styles.fa0321dd46c136114300.css' );
	wp_enqueue_style( 'custom-styles' );


	/* --- Import Theme Styles via style.css --- */
	wp_register_style( 'style', get_stylesheet_uri() );
	wp_enqueue_style( 'style' );

}

add_action( 'wp_enqueue_scripts', 'macoffice_register_styles' );



function macoffice_register_scripts() {

	/* --- Import Main Scripts --- */
	wp_register_script( 'main', get_template_directory_uri() . '/assets/scripts/main.fa0321dd46c136114300.js', '', null, true );
	wp_enqueue_script( 'main' );

	/* --- Import Vendors Scripts --- */
	wp_register_script( 'vendors', get_template_directory_uri() . '/assets/scripts/vendors.28fea5dbcae5e022ba8b.js', '', null, true );
	wp_enqueue_script( 'vendors' );

	/* --- Import Modal Scripts --- */
	wp_register_script( 'modal', get_template_directory_uri() . '/assets/scripts/modal.2d9da9bf63f3166dc909.js', '', null, true );
	wp_enqueue_script( 'modal' );

	// Import Button Back-to-Top
	wp_register_script( 'button-back-to-top', get_template_directory_uri() . '/assets/scripts/button-back-to-top.js', '', null, true );
	wp_enqueue_script( 'button-back-to-top' );

	// Import Cookie Notice Scripts
	wp_register_script( 'dywc', get_template_directory_uri() . '/assets/scripts/dywc.js', '', null, true );
	wp_enqueue_script( 'dywc' );

	wp_register_script( 'cookie-notice', get_template_directory_uri() . '/assets/scripts/cookie-notice.js', '', null, true );
	wp_enqueue_script( 'cookie-notice' );

}

add_action( 'wp_enqueue_scripts', 'macoffice_register_scripts' );


/* Support einer jeweils eigenen single.php nach Kategorie

add_filter('single_template', 'check_for_category_single_template');

function check_for_category_single_template( $t ) {
	foreach( (array) get_the_category() as $cat ) {
		if ( file_exists(get_stylesheet_directory() . "/single-category-{$cat->slug}.php") ) return get_stylesheet_directory() . "/single-category-{$cat->slug}.php";
		if($cat->parent) {
			$cat = get_the_category_by_ID( $cat->parent );
			if ( file_exists(get_stylesheet_directory() . "/single-category-{$cat->slug}.php") ) return get_stylesheet_directory() . "/single-category-{$cat->slug}.php";
		}
	}
	return $t;
}
*/


/* --- Menu Support --- */

function macoffice_register_menu() {
	register_nav_menu( 'nav-menu-main', 'Hauptnavigation', 'macoffice' );
	register_nav_menu( 'footer-navigation', 'Footernavigation', 'macoffice' );
	register_nav_menu( 'nav-menu-footer', 'Footermenü', 'macoffice' );
}

add_action( 'init', 'macoffice_register_menu' );


/* === Additional Functions === */

/* --- Makes showing the gallery thumbnails available --- */
function macoffice_get_backend_preview_thumb($post_ID) {
	$post_thumbnail_id = get_post_thumbnail_id($post_ID);
	if ($post_thumbnail_id) {
		$post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');
		return $post_thumbnail_img[0];
	}
}

function macoffice_preview_thumb_column_head($defaults) {
	$defaults['featured_image'] = 'Image';
	return $defaults;
}

add_filter('manage_posts_columns', 'macoffice_preview_thumb_column_head');


function macoffice_preview_thumb_column($column_name, $post_ID) {
	if ($column_name == 'featured_image') {
		$post_featured_image = macoffice_get_backend_preview_thumb($post_ID);
			if ($post_featured_image) {
				echo '<img src="' . $post_featured_image . '" />';
			}
	}
}

add_action('manage_posts_custom_column', 'macoffice_preview_thumb_column', 10, 2);



/* --- Extends CPT PROJECTS to make Tags available --- */
add_action('pre_get_posts', function($query) {
	// This will target the queries used to generate the tag archive template.
	// You may remove the `is_main_query()` condition if you want to get posts
	// by tag outside the loop.
	if (!is_admin() && is_tag() && $query->is_main_query()) {
		// Will set to something like: Array( 'post', 'portfolio' )
		$types = get_taxonomy('post_tag')->object_type;

		// Alter the query to only use the types which are registered to the
		// `post_tag` taxonomy.
		$query->set('project', $types);
	}
});



/* --- Register Google Maps --- */

// Method 1: Filter.
	function my_acf_google_map_api( $api ){
			$api['key'] = 'XXX';
			return $api;
	}
	add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

	// Method 2: Setting.
	function my_acf_init() {
			acf_update_setting('google_api_key', 'AIzaSyAzkr3qs7g4-R6ShYWXKiJ5JZ_i5orikC8');
	}
	add_action('acf/init', 'my_acf_init');





	/* --- Navigation Walker for HAUPTNAVIGATION --- */
	require_once( 'classes/navwalker.php' );
	require_once( 'classes/footer-navwalker.php' );
	require_once( 'classes/footermenu-navwalker.php' );



	/* --- Navigation Walker for FOOTERMENÜ --- */
	class Footer_Walker extends Walker_Nav_Menu {

		function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {

		$classes = empty($item->classes) ? array () : (array) $item->classes;

		$class_names = join(' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );

			!empty ( $class_names ) and $class_names = ' class="'. esc_attr( $class_names ) . '"';

			$output .= '<li class="site-footer__navigation-list-item">';
			$attributes  = 'class="footer-navigation__item"';

			!empty( $item->attr_title ) and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';
			!empty( $item->target ) and $attributes .= ' target="' . esc_attr( $item->target     ) .'"';
			!empty( $item->xfn ) and $attributes .= ' rel="'    . esc_attr( $item->xfn        ) .'"';
			!empty( $item->url ) and $attributes .= ' href="'   . esc_attr( $item->url        ) .'"';
			$title = apply_filters( 'the_title', $item->title, $item->ID );
			$item_output = $args->before
				. "<a  $attributes>"
				. $args->link_before
				. $title
				. '</a></li>'
				. $args->link_after
				. $args->after;
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

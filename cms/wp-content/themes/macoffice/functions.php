<?php
/**
 * Theme Funktionen und allgemeine Definitionen für die Website "macoffice.at"
 */

/* === Default Theme Functions === */

/* --- Activate Theme Functions --- */
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


/* --- Includes the Custom Shortcodes Library of the actual Theme --- */
include( 'classes/custom-shortcodes.php' );


/* --- Adds the Slug to the body tag's class --- */
function macoffice_add_slug_body_class( $classes ) {
	 global $post;
	if ( isset( $post ) ) {
	 $classes[] = $post->post_name;
	}
	return $classes;
}

add_filter( 'body_class', 'macoffice_add_slug_body_class' );


/* --- Supports an own single.php for each category --- */
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







/* --- Defines the default expression for the "Read More"-Link --- */
function macoffice_read_more_text( $text, $post_id ) {
	return '<a class="more-link" href="' . get_permalink() . '">' . __( 'Read More' , 'macoffice' ) . '</a>';
}

add_filter( 'the_content_more_link', 'macoffice_read_more_text', 10, 2 );




/* === Navigation Menus === */

/* --- Menu Support --- */
function macoffice_register_menu() {
	register_nav_menu( 'nav-menu-main', 'Hauptnavigation', 'macoffice' );
	register_nav_menu( 'footer-navigation', 'Footernavigation', 'macoffice' );
	register_nav_menu( 'nav-menu-footer', 'Footermenü', 'macoffice' );
}

add_action( 'init', 'macoffice_register_menu' );



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




/* --- Removes novalidate in WordPress
remove_theme_support('html5', 'comment-form');
--- */



/* === DSGVO === */

/* --- Replaces the IP address at comments (IP-Anonymisierung lt. DSGVO) --- */
function macoffice_replace_comment_ip() {
	 return "127.0.0.1";
}

add_filter( 'pre_comment_user_ip', 'macoffice_replace_comment_ip', 50);









/* === Import Styles and Scripts === */
function macoffice_register_styles() {

	/* --- Import Custom Stylesheets --- */
	wp_register_style( "custom-styles", get_template_directory_uri() . "/assets/styles/css/styles.css" );
	wp_enqueue_style("custom-styles");

	/* --- Import Theme Styles via style.css --- */
	wp_register_style( 'style', get_stylesheet_uri() );
	wp_enqueue_style( 'style' );

}

add_action( 'wp_enqueue_scripts', 'macoffice_register_styles' );


function macoffice_register_scripts() {

	/* --- Import Main Scripts -- */

	/* --- Import Cookie Notice Scripts --- */
	wp_register_script( 'dywc', get_template_directory_uri() . '/assets/scripts/dywc.js', '', null, true );
	wp_enqueue_script( 'dywc' );

	wp_register_script( 'cookie-notice', get_template_directory_uri() . '/assets/scripts/cookie-notice.js', '', null, true );
	wp_enqueue_script( 'cookie-notice' );

	/* --- Import Tabs --- */
	wp_register_script( 'tabs', get_template_directory_uri() . '/assets/scripts/tabs.js', '', null, true );
	wp_enqueue_script( 'tabs' );

	/* --- Import Button Back-to-Top --- */
	wp_register_script( 'button-back-to-top', get_template_directory_uri() . '/assets/scripts/button-back-to-top.js', '', null, true );
	wp_enqueue_script( 'button-back-to-top' );

}

add_action( 'wp_enqueue_scripts', 'macoffice_register_scripts' );





















/* === Additional Functions === */

/* --- Adding the ID to the WordPress Admin Dashboard --- */
function add_column( $columns ){
	$columns['post_id_clmn'] = 'ID'; // $columns['Column ID'] = 'Column Title';
	return $columns;
}
add_filter('manage_posts_columns', 'add_column', 2);

function column_content( $column, $id ){
	if( $column === 'post_id_clmn')
		echo $id;
}
add_action('manage_posts_custom_column', 'column_content', 10, 2);



/* --- Makes showing the gallery thumbnails in Posts available --- */
function macoffice_get_backend_preview_thumb($post_ID) {
	$post_thumbnail_id = get_post_thumbnail_id($post_ID);
	if ($post_thumbnail_id) {
		$post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');
		return $post_thumbnail_img[0];
	}
}

function macoffice_preview_thumb_column_head($defaults) {
	$defaults['featured_image'] = 'Beitragsbild';
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





/* --- Excludes Archiv Category from pagination --- */
remove_filter('pre_term_description', 'wp_filter_kses');

function exclude_category($query) {
	if ( $query->is_singular() ) {
		$query->set( 'cat', '-239' );
	}
	return $query;
}

add_filter( 'pre_get_posts', 'exclude_category' );









/* --- Extends CPT PROJECTS to make Tags available ---
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
*/










/* === WooCommerce === */

/* --- General Features --- */

function macoffice_add_woocommerce_support() {
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 450,
		'single_image_width'    => 450,
		'product_grid'          => array(
			'default_rows'    => 3,
			'min_rows'        => 2,
			'max_rows'        => 8,
			'default_columns' => 3,
			'min_columns'     => 1,
			'max_columns'     => 5,
		),
	) );
}

add_action( 'after_setup_theme', 'macoffice_add_woocommerce_support' );



/* Remove Product gallery Features (zoom, swipe, lightbox) */
remove_theme_support( 'wc-product-gallery-zoom' );
remove_theme_support( 'wc-product-gallery-lightbox' );
remove_theme_support( 'wc-product-gallery-slider' );


/* Adding Product gallery Features (zoom, swipe, lightbox) */
add_theme_support( 'wc-product-gallery-lightbox' );

















/* function hap_hide_the_archive_title( $title ) {
// Skip if the site isn't LTR, this is visual, not functional.
// Should try to work out an elegant solution that works for both directions.
if ( is_rtl() ) {
		return $title;
}
// Split the title into parts so we can wrap them with spans.
$title_parts = explode( ': ', $title, 2 );
// Glue it back together again.
if ( ! empty( $title_parts[1] ) ) {
		$title = wp_kses(
				$title_parts[1],
				array(
						'span' => array(
								'class' => array(),
						),
				)
		);
		$title = '<span class="screen-reader-text">' . esc_html( $title_parts[0] ) . ': </span>' . $title;
}
return $title;
}
add_filter( 'get_the_archive_title', 'hap_hide_the_archive_title' ); */




/* Editing the Shop's Title */
/* function wc_custom_shop_archive_title( $title ) {
	if ( is_shop() && isset( $title['title'] ) ) {
			$title['title'] = 'Produkte';
	}
	return $title;
}

add_filter( 'document_title_parts', 'wc_custom_shop_archive_title' ); */
















/* Adding Sort Option "Is On Stock" */

/**
 * Order product collections by stock status, instock products first.
 */
/* class iWC_Orderby_Stock_Status
{

		public function __construct()
		{
				// Check if WooCommerce is active
				if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
						add_filter('posts_clauses', array($this, 'order_by_stock_status'), 2000, 2);
				}
		}

		public function order_by_stock_status($posts_clauses, $wp_query)
		{
				global $wpdb;
				// only change query on WooCommerce loops
/* 				if (false === is_admin() && $wp_query->is_post_type_archive('product')) {
				if (is_woocommerce() && (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy())) {

						$posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
						$posts_clauses['orderby'] = " istockstatus.meta_value ASC, " . $posts_clauses['orderby'];
						$posts_clauses['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
				}
				return $posts_clauses;
		}
}

new iWC_Orderby_Stock_Status; */





/* function order_by_stock_status($posts_clauses) {
		global $wpdb;
		// only change query on WooCommerce loops
		if (is_woocommerce() && (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy())) {
				$posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
				$posts_clauses['orderby'] = " istockstatus.meta_value ASC, " . $posts_clauses['orderby'];
				$posts_clauses['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
		}
		return $posts_clauses;
}

add_filter( 'posts_clauses', 'order_by_stock_status', 999 ); */










/* Adding Taxonomy Terms to Body Class
function macoffice_custom_taxonomy_in_body_class( $classes ){
	if( is_singular( 'product' ) ) {
		$custom_terms = get_the_terms(0, 'product_cat');
		if ($custom_terms) {
			foreach ($custom_terms as $custom_term) {
				$classes[] = 'product_cat_' . $custom_term->slug;
			}
		}
	}

	if( is_singular( 'product' ) ) {
		$custom_terms = get_the_terms(0, 'product_tag');
		if ($custom_terms) {
			foreach ($custom_terms as $custom_term) {
				$classes[] = 'product_tag_' . $custom_term->slug;
			}
		}
	}
	return $classes;
}

add_filter( 'body_class', 'macoffice_custom_taxonomy_in_body_class' );
*/



/* Redirect Search Result Page */

/* add_filter( 'aws_excerpt_search_result', 'my_aws_excerpt_search_result', 10, 2 );

function my_aws_excerpt_search_result( $query ) {
		if ( $query->is_search && ! is_admin() ) {
				$query->set( 'post_type', 'product' );
				$query->is_post_type_archive = true;
		}
} */




/* add_filter('template_include', 'wpes_support_15956511', 99);
function wpes_support_15956511($template) {
		if (function_exists('WPES') && is_search()) {
				$template_name = 'search-product' . WPES()->current_setting_id . '.php';
				$new_template = locate_template(array($template_name));
				if ('' != $new_template) {
						return $new_template;
				}
		}
		return $template;
} */




















/* --- Breadcrumb Menu --- */

/* Adding Parent Categories to the Breadcrumb Menu */
add_filter('woocommerce_breadcrumb_main_term', function($main, $terms) {
	$url_cat = get_query_var( 'product_cat', false );

	if ($url_cat) {
		foreach($terms as $term) {
			if (preg_match('/'.$term->slug.'$/', $url_cat)) {
				return $term;
			}
		}
	}
	return $main;
}, 100, 2);


/* Adding Parent Tags to the Breadcrumb Menu */
function macoffice_custom_product_tag_crumb( $crumbs, $breadcrumb ){
		// Targetting product tags
		$current_taxonomy  = 'product_tag';
		$current_term      = $GLOBALS['wp_query']->get_queried_object();
		$current_key_index = sizeof($crumbs) - 1;

		// Only product tags
		if( is_a($current_term, 'WP_Term') && term_exists( $current_term->term_id, $current_taxonomy ) ) {
				// The label term name
				$crumbs[$current_key_index][0] = sprintf( __( 'Marken > ' . '%s', 'macoffice' ), $current_term->name );
				// The term link (not really necessary as we are already on the page)
				$crumbs[$current_key_index][1] = get_term_link( $current_term, $current_taxonomy );
		}
		return $crumbs;
}

add_filter( 'woocommerce_get_breadcrumb', 'macoffice_custom_product_tag_crumb', 20, 2 );


/* Changing the Breadcrumb Separator */
function macoffice_change_breadcrumb_delimiter( $defaults ) {
	// Change the breadcrumb delimeter from '/' to '>'
	$defaults['delimiter'] = ' &gt; ';
	return $defaults;
}

add_filter( 'woocommerce_breadcrumb_defaults', 'macoffice_change_breadcrumb_delimiter' );


/**
 * Rename "home" in breadcrumb
 */
add_filter( 'woocommerce_breadcrumb_defaults', 'woo_change_breadcrumb_home_text' );
 /**
	* Change the breadcrumb home text from "Home" to "Shop".
	* @param  array $defaults The default array items.
	* @return array           Modified array
	*/
 function woo_change_breadcrumb_home_text( $defaults ) {
	 $defaults['home'] = 'Startseite';

	 return $defaults;
 }

 add_filter( 'woocommerce_breadcrumb_home_url', 'woo_custom_breadrumb_home_url' );
 /**
	* Change the breadcrumb home link URL from / to /shop.
	* @return string New URL for Home link item.
	*/
 function woo_custom_breadrumb_home_url() {
	 return '/';
 }





/* Makes the Product Fields shown in Quick Edit Section again */
add_action( 'init', function(){
	add_post_type_support( 'product', 'page-attributes' );
});

function macoffice_product_type_columns( $columns) {
	$columns['menu_order']	 = __('Sortierfeld', 'macoffice');
	return $columns;
}
add_filter( 'manage_edit-product_columns', 'macoffice_product_type_columns' );


function macoffice_post_columns_data( $column_name, $post_id ) {
				if ( $column_name == 'menu_order' ) {
								$order = get_post_field('menu_order', $post_id);
								echo $order;
				}
}
add_action( 'manage_product_posts_custom_column', 'macoffice_post_columns_data', 10, 2 );










/* === WOOCOMMERCE === */

// Edit WooCommerce dropdown menu item of shop page//
// Options: menu_order, popularity, rating, date, price, price-desc

function my_woocommerce_catalog_orderby( $orderby ) {
		unset($orderby["relevance"]);
		unset($orderby["menu_order"]);
		unset($orderby["popularity"]);
		unset($orderby["rating"]);
		unset($orderby["date"]);
		unset($orderby["price"]);
		unset($orderby["price-desc"]);
		return $orderby;
}
add_filter( "woocommerce_catalog_orderby", "my_woocommerce_catalog_orderby", 20 );


// remove_filter( 'aws_relevance_scores', 'my_aws_relevance_scores' );

add_filter( 'aws_search_results_products_ids', 'custom_woocommerce_catalog_orderby' );



/**
 * Add custom sorting options (asc/desc)
 * https://woocommerce.com/document/custom-sorting-options-ascdesc/
 */

add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_woocommerce_get_catalog_ordering_args' );

function custom_woocommerce_get_catalog_ordering_args( $sort_args ) {
	$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	if ( 'default_sorting' == $orderby_value ) {
		$sort_args['orderby'] = array(
			'ID' => 'asc'
		);
		$sort_args['order'] = '';
	}



	if ( 'stock_list_asc' == $orderby_value ) {
		$sort_args['orderby'] = array(
			'meta_value_num' => 'asc',
			'price' => 'asc',
			'title' => 'asc'
		);
		$sort_args['order'] = '';
		$sort_args['meta_key'] = '_stock';
	}


	if ( 'stock_list_desc' == $orderby_value ) {
		$sort_args['orderby'] = array(
			'meta_value_num' => 'desc',
			'price' => 'asc',
			'title' => 'asc'
		);
		$sort_args['order'] = '';
		$sort_args['meta_key'] = '_stock';
	}


	if ( 'price_list_asc' == $orderby_value ) {
		$sort_args['orderby'] = array(
			'meta_value_num' => 'asc',
			'title' => 'asc'
		);
		$sort_args['order'] = '';
		$sort_args['meta_key'] = '_price';
	}


	if ( 'price_list_desc' == $orderby_value ) {
		$sort_args['orderby'] = array(
			'meta_value_num' => 'desc',
			'title' => 'asc'
		);
		$sort_args['order'] = '';
		$sort_args['meta_key'] = '_price';
	}


	if ( 'name_list_asc' == $orderby_value ) {
		$sort_args['orderby'] = array(
			'title' => 'asc'
		);
		$sort_args['order'] = '';
	}


	if ( 'name_list_desc' == $orderby_value ) {
		$sort_args['orderby'] = array(
			'title' => 'desc'
		);
		$sort_args['order'] = '';
	}



	return $sort_args;

/*
	$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		if ( 'stock_list_desc' == $orderby_value ) {
			$sort_args['orderby'] = array(
				'_stock' => 'DESC',
				'title' => 'ASC'
			);
			// $sort_args['meta_key'] = 'stock';
		}

		return $sort_args;


		$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		if ( 'price_list_asc' == $orderby_value ) {
			$sort_args['orderby'] = array(
				'_price' => 'asc',
				'title' => 'ASC'
			);
		}

		return $sort_args;


		$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		if ( 'price_list_desc' == $orderby_value ) {
			$sort_args['orderby'] = 'price-desc';
		}

		return $sort_args;


		$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
			if ( 'name_list_asc' == $orderby_value ) {
				$sort_args['orderby'] = 'name';
				$sort_args['order'] = 'ASC';
			}

			return $sort_args;


			$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
				if ( 'name_list_desc' == $orderby_value ) {
					$sort_args['orderby'] = 'name';
					$sort_args['order'] = 'DESC';
				}

				return $sort_args;
*/
}

// remove_filter( 'aws_relevance_scores', 'my_aws_relevance_scores' );

remove_filter( 'catalog_orderby_options', 'custom_woocommerce_catalog_orderby' );
remove_filter( 'show_default_orderby', 'custom_woocommerce_catalog_orderby' );

add_filter( 'woocommerce_default_catalog_orderby_options', 'custom_woocommerce_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'custom_woocommerce_catalog_orderby' );

function custom_woocommerce_catalog_orderby( $sortby ) {

/* 	unset( $catalog_orderby_options['relevance'] ); */

	$sortby['default_sorting'] = 'Standardsortierung';
	$sortby['stock_list_asc'] = 'Nach Verfügbarkeit (aufsteigend)';
	$sortby['stock_list_desc'] = 'Nach Verfügbarkeit (absteigend)';
	$sortby['price_list_asc'] = 'Nach Preis (aufsteigend)';
	$sortby['price_list_desc'] = 'Nach Preis (absteigend)';
	$sortby['name_list_asc'] = 'Nach Name (aufsteigend)';
	$sortby['name_list_desc'] = 'Nach Name (absteigend)';
	return $sortby;
}















/* add_filter( 'posts_search_orderby', 'my_posts_search_orderby', 10, 2 );
function my_posts_search_orderby( $orderby, $query ) {
		if ( $query->is_main_query() && is_search() ) {
				global $wpdb;
				$orderby = "{$wpdb->posts}.post_type";
		}

		return $orderby;
}


add_filter( 'the_posts', 'my_the_posts', 10, 2 );
function my_the_posts( $posts, $query ) {
		if ( $query->is_main_query() && is_search() ) {
				usort( $posts, function ( $a, $b ) {
						return strcmp( $a->post_type, $b->post_type ); // A-Z (ASCending)
//          return strcmp( $b->post_type, $a->post_type ); // Z-A (DESCending)
				} );
		}

		return $posts;
} */



/* function my_woocommerce_catalog_orderby( $orderby ) {
		unset($orderby["relevance"]);
		unset($orderby["menu_order"]);
		unset($orderby["popularity"]);
		unset($orderby["rating"]);
		unset($orderby["date"]);
		unset($orderby["price"]);
		unset($orderby["price-desc"]);
		return $orderby;
}
add_filter( "woocommerce_catalog_orderby", "my_woocommerce_catalog_orderby", 20 ); */






/* function alphabetical_shop_ordering( $sort_args ) {
	$orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	if ( 'ascending' == $orderby_value ) {
		$sort_args['orderby'] = 'title';
		$sort_args['order'] = 'asc';
		$sort_args['meta_key'] = '';
	}
	if ( 'descending' == $orderby_value ) {
		$sort_args['orderby'] = 'title';
		$sort_args['order'] = 'desc';
		$sort_args['meta_key'] = '';
	}
	return $sort_args;
}

add_filter( 'woocommerce_get_catalog_ordering_args', 'alphabetical_shop_ordering' );

function custom_wc_catalog_orderby( $sortby ) {
	$sortby['ascending'] = 'Sort by (A-Z)';
	$sortby['descending'] = 'Sort by (Z-A)';
	return $sortby;
}
add_filter( 'woocommerce_default_catalog_orderby_options', 'custom_wc_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'custom_wc_catalog_orderby' ); */














/* ------------------------------------------------------------------- DELETE ME ------------------------------------------------------------------- */


/**
 * Add custom sorting options (asc/desc)
 * https://stackoverflow.com/questions/43179878/woocommerce-custom-sort-multiple-condition
 */

/*
function vi_custom_woocommerce_catalog_orderby( $sortby ) {
		 $sortby['alphabetical'] = 'Sort by name: custom';
		 $sortby['availability_up'] = 'Verfügbarkeit rauf';
		 return $sortby;
 }
 add_filter( 'woocommerce_default_catalog_orderby_options', 'vi_custom_woocommerce_catalog_orderby' );
 add_filter( 'woocommerce_catalog_orderby', 'vi_custom_woocommerce_catalog_orderby' );

 // Add Alphabetical sorting option to shop page / WC Product Settings
 function vi_alphabetical_woocommerce_shop_ordering( $sort_args2 ) {
	 $orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

		 if ( 'alphabetical' == $orderby_value ) {
				 // $sort_args['orderby'] = 'meta_value_num';
				 // $sort_args['order'] = 'asc';
				 // $sort_args['meta_key'] = '_price';

				 $sort_args2['orderby'] = array(
						'meta_value_num' => 'asc',
						'price' => 'asc',
						//'title' => 'ASC'
					);

					$sort_args2['meta_key'] = array(
						//'_stock',
						'price'
					);
					/*
					'orderby' => array(
					'city_clause' => 'ASC',
					'state_clause' => 'DESC',
					)


					// $sort_args['meta_key'] = '_stock';

		 }


		 if ( 'availability_up' == $orderby_value ) {
					// $sort_args['orderby'] = 'meta_value_num';
					// $sort_args['order'] = 'asc';
					// $sort_args['meta_key'] = '_price';

					$sort_args2['orderby'] = array(
						 'meta_value_num' => 'asc',
						 //'price' => 'asc',
						 'title' => 'ASC'
					 );

					 $sort_args2['meta_key'] = array(
						 'stock',
						 //'_price'
					 );
					 /*
					 'orderby' => array(
					 'city_clause' => 'ASC',
					 'state_clause' => 'DESC',
					 )


					 // $sort_args['meta_key'] = '_stock';

			}

		 return $sort_args;
 }
 add_filter( 'woocommerce_get_catalog_ordering_args', 'vi_alphabetical_woocommerce_shop_ordering' ); */







/**
	* Add custom sorting options (asc/desc)
	* https://stackoverflow.com/questions/43179878/woocommerce-custom-sort-multiple-condition



	function vi_custom_woocommerce_catalog_orderby( $sortby ) {
			$sortby['alphabetical'] = 'Name rauf';
			return $sortby;
	}
	add_filter( 'woocommerce_default_catalog_orderby_options', 'vi_custom_woocommerce_catalog_orderby' );
	add_filter( 'woocommerce_catalog_orderby', 'vi_custom_woocommerce_catalog_orderby' );

	//Add Alphabetical sorting option to shop page / WC Product Settings
	function vi_alphabetical_woocommerce_shop_ordering( $sorting_args ) {
		$orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

			if ( 'alphabetical' == $orderby_value ) {
					$sorting_args['orderby'] = 'meta_value_num';
					$sorting_args['order'] = 'asc';
					$sorting_args['meta_key'] = '';
			}

			return $sorting_args;
	}
	add_filter( 'woocommerce_get_catalog_ordering_args', 'vi_alphabetical_woocommerce_shop_ordering' );

*/




 /**

 function vi_custom_woocommerce_catalog_orderby( $sortby ) {
			$sortby['name_up'] = 'Name rauf';
			$sortby['availability_up'] = 'Verfügbarkeit rauf';
			return $sortby;
	}
	add_filter( 'woocommerce_default_catalog_orderby_options', 'vi_custom_woocommerce_catalog_orderby' );
	add_filter( 'woocommerce_catalog_orderby', 'vi_custom_woocommerce_catalog_orderby' );

	// Add Alphabetical sorting option to shop page / WC Product Settings
	function vi_alphabetical_woocommerce_shop_ordering( $sorting_args ) {
		$orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

			if ( 'alphabetical' == $orderby_value ) {
					// $sort_args['orderby'] = 'meta_value_num';
					// $sort_args['order'] = 'asc';
					// $sort_args['meta_key'] = '_price';

					$sorting_args['orderby'] = array(
						 'meta_value_num' => 'asc',
						 'price' => 'asc',
						 'title' => 'ASC'
					 );

					 $sorting_args['meta_key'] = array(
						 '_stock',
						 'price'
					 );
					 /*
					 'orderby' => array(
					 'city_clause' => 'ASC',
					 'state_clause' => 'DESC',
					 )


					 // $sort_args['meta_key'] = '_stock';

			}


			if ( 'availability_up' == $orderby_value ) {
					 // $sort_args['orderby'] = 'meta_value_num';
					 // $sort_args['order'] = 'asc';
					 // $sort_args['meta_key'] = '_price';

					 $sort_args2['orderby'] = array(
							'meta_value_num' => 'asc',
							//'price' => 'asc',
							'title' => 'ASC'
						);

						$sort_args2['meta_key'] = array(
							'stock',
							//'_price'
						);
						/*
						'orderby' => array(
						'city_clause' => 'ASC',
						'state_clause' => 'DESC',
						)


						// $sort_args['meta_key'] = '_stock';

			 }

			return $sort_args;
	}
	add_filter( 'woocommerce_get_catalog_ordering_args', 'vi_alphabetical_woocommerce_shop_ordering' );




	*/










/* NOPE
add_filter( 'woocommerce_get_catalog_ordering_args', 'enable_catalog_ordering_by_price_asc' );
 function enable_catalog_ordering_by_price_asc( $args ) {
		 if ( isset( $_GET['orderby'] ) ) {
				 if ( 'price_asc' == $_GET['orderby'] ) {
						 return array(
								 'orderby'  => 'price',
								 'order'    => 'ASC',
						 );
				 }
				 // Make a clone of "menu_order" (the default option)
				 elseif ( 'natural_order' == $_GET['orderby'] ) {
						 return array( 'orderby'  => 'menu_order title', 'order' => 'ASC' );
				 }
		 }
		 return $args;
 }

 add_filter( 'woocommerce_catalog_orderby', 'add_catalog_orderby_price_asc' );
 function add_catalog_orderby_price_asc( $orderby_options ) {
		 // Insert "Sort by modified date" and the clone of "menu_order" adding after others sorting options
		 return array(
				 'price_asc' => __("Sort by price ASC", "woocommerce"),
				 'natural_order' => __("Sort by natural shop order", "woocommerce"), // <== To be renamed at your convenience
		 ) + $orderby_options ;

		 return $orderby_options ;
 }


 add_filter( 'woocommerce_default_catalog_orderby', 'default_catalog_orderby_price_asc' );
 function default_catalog_orderby_modified_date( $default_orderby ) {
		 return 'price_asc';
 }

 add_action( 'woocommerce_product_query', 'product_query_by_price_asc' );
 function product_query_by_modified_date( $q ) {
		 if ( ! isset( $_GET['orderby'] ) && ! is_admin() ) {
				 $q->set( 'orderby', 'price' );
				 $q->set( 'order', 'DESC' );
		 }
 } */




/* ------------------------------------------------------------------- DELETE ME ------------------------------------------------------------------- */












/* === Search Result Page === */

add_filter( 'aws_search_results_products_ids', 'my_aws_search_results_products_ids' );

function my_aws_search_results_products_ids( $products ) {
		usort($products, function ($a, $b) {
				$status_a = get_post_meta( $a, '_stock_status', true );
				$status_b = get_post_meta( $b, '_stock_status', true );
				if ( ! $status_a || ! $status_b ) {
						return 0;
				}
				if ( 'instock' === $status_a ) {
						return -1;
				}
				if ( 'instock' === $status_b ) {
						return 1;
				}
				return 0;
		});
		return $products;
}





/* Customized Sorting on Search Result Page */

add_filter('aws_products_order_by', 'my_aws_products_order_by');

function my_aws_products_order_by( $order_by ) {
		if ( isset( $_GET['orderby'] ) ) {
				switch( sanitize_text_field( $_GET['orderby'] ) ) {
						case 'price_list_asc':
								$order_by = 'price';
								break;
						case 'price_list_desc':
								$order_by = 'price-desc';
								break;
						case 'name_list_asc':
								$order_by = 'title';
								break;
						case 'name_list_desc':
								$order_by = 'title-asc';
								break;
						case 'stock_list_asc':
								$order_by = 'stock_quantity-asc';
								break;
						case 'stock_list_desc':
								$order_by = 'stock_quantity-desc';
								break;
				}
		}
		return $order_by;
}












/* === Shop Page === */

/* Remove unused Data */

// remove Archive Description
remove_action('woocommerce_archive_description','woocommerce_taxonomy_archive_description', 10, 0);

// remove Button Add to Cart
remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart', 10, 0);

// remove Sidebars
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );


/* Adding customized Text */

function macoffice_change_archive_description() {
	return '<mark>Jo geht jo eh!</mark>';
}

add_action( 'woocommerce_archive_description', 'macoffice_change_archive_description', 27 );




/* --- Adding the Brand ---
function macoffice_shop_display_brand() {
	global $product;
	$brand_tag = wc_get_product_tag_list( $product->get_id(), '' );

	// Output
	echo '<div class="product-meta product-brand">';
	if ( ! empty( $brand_tag ) ){
		echo $brand_tag;
		} else {
			echo 'Noch keine Marke hinterlegt';
		}
	echo '</div>';

}

add_action( 'woocommerce_shop_loop_item_title', 'macoffice_shop_display_brand', 9 );
*/







/* Adding the ID */

function macoffice_shop_display_ids() {

	global $product;

	if ( $product->get_sku() ) {
/* 		echo '<div class="product-meta product-sku">Art.-Nr.: MO-' . $product->get_sku() . '</div>'; */
		echo '<div class="product-meta product-sku">Art.-Nr.: ' . $product->get_sku() . '</div>';
	}
}

add_action( 'woocommerce_after_shop_loop_item', 'macoffice_shop_display_ids', 15 );




/* Editing and display the In Stock Text */

function macoffice_shop_display_availability_text() {

	global $product;

	$stock = $product->get_stock_quantity();
	error_log( $stock );

	if ( $stock == "0" ) {
		$availability = 'lagernd';
	} elseif ( $stock == "1" ) {
		$availability = 'kurzfristig verfügbar';
	} elseif ( $stock == "2" ) {
		$availability = 'bald/demnächst verfügbar';
	} elseif ( $stock == "3" ) {
		$availability = 'auf Anfrage';
	} else {
		$availability = 'auf Anfrage';
	}

	echo $availability;
}

add_action( 'woocommerce_after_shop_loop_item', 'macoffice_shop_display_availability_text', 15 );



/* Display the Shop Modified Date */
add_action( 'woocommerce_after_shop_loop', 'after_shop_loop_item_action_callback', 15 );
function after_shop_loop_item_action_callback() {
		global $product;

		echo '<div class="shop-date-modified">Stand vom ' . $product->get_date_modified()->date('d.m.Y, H:i') . '</div>';
}






















/* === Single Product Page === */

/* --- Remove unused Data --- */

// remove Breadcrumb
// remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);


// remove Price
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );


// remove Product Meta
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );


// remove Rating Stars
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );


// remove Additional Information Tabs
remove_action('woocommerce_after_single_product_summary','woocommerce_output_product_data_tabs', 10);


// remove Related Products
remove_action('woocommerce_after_single_product_summary','woocommerce_output_related_products', 20);



/* Adding the Brand

function macoffice_single_product_display_brand() {

	global $product;

	$brand_tag = wc_get_product_tag_list( $product->get_id(), '' );

	echo '<div class="single-product-meta single-product-brand product-tags">';
	if ( ! empty( $brand_tag ) ){
		echo $brand_tag;
		} else {
			echo 'Noch keine Marke hinterlegt';
		}
	echo '</div>';

}

add_action( 'woocommerce_single_product_summary', 'macoffice_single_product_display_brand', 4 );
*/





/* Adding the SKU

function macoffice_single_product_show_sku(){
	global $product;
	$sku = $product->get_sku();
	if ($sku != null) {
		echo '<span class="single-product-sku">';
		echo '<span class="single-product-label">Artikelnummer</span>' . $product->get_sku();
		echo '</span>';
	}
}

add_action( 'woocommerce_single_product_summary', 'macoffice_single_product_show_sku', 11 );
*/




/* Adding the ID */

function macoffice_single_product_show_id(){
	global $product;
	$sku = $product->get_id();
	if ($sku != null) {
		echo '<span class="single-product-sku">';
/* 		echo '<span class="single-product-label">Art.-Nr.:</span> MO-' . $product->get_sku(); */
		echo '<span class="single-product-label">Art.-Nr.:</span> ' . $product->get_sku();
		echo '</span>';
	}
}

add_action( 'woocommerce_single_product_summary', 'macoffice_single_product_show_id', 11 );


/* Adding the Price */
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 11 );





/* Adding the Delivery Time ---------------------------> Backup after Initializing Additional Delivery time if not on stock

function macoffice_single_product_display_delivery_time() {
	echo '<span class="single-product-delivery-time">';
	echo '<span class="single-product-label">Lieferzeit</span>' . $product->get_term('woocommerce_catalog_ordering');
	echo '</span>';
}

add_action( 'woocommerce_catalog_ordering', 'macoffice_single-product_display_delivery_time', 10 );

<--------------------------- */




	/* Adding Engraving

	function macoffice_single_product_display_engraving() {

		global $product;

		$product_attributes = $product->get_attributes(); // Get the product attributes

		// Output

		if ( !empty( $engraving_id ) ) {
			$engraving_id = $product_attributes['pa_gravur']['options']['0']; // returns the ID of the term
			$engraving_value = get_term( $engraving_id )->name; // gets the term name of the term from the ID

			echo '<span class="single-product-engraving">';
			echo $engraving_value;
			echo '</span>';
		} else {
			echo '<span class="single-product-engraving">';
			echo 'Nein';
			echo '</span>';
		}

	}

	add_action( 'woocommerce_single_product_summary', 'macoffice_single_product_display_engraving', 20 );

	*/







/* Editing the In Stock Text */

function macoffice_custom_get_availability_text( $availability, WC_Product $product ) {

	$stock = $product->get_stock_quantity();
	error_log( $stock );

	// if ( $product->is_in_stock() ) {
	if ( $stock == "0" ) {
		$availability = 'lagernd';
	} elseif ( $stock == "1" ) {
		$availability = 'kurzfristig verfügbar';
	} elseif ( $stock == "2" ) {
		$availability = 'bald/demnächst verfügbar';
	} elseif ( $stock == "3" ) {
		$availability = 'auf Anfrage';
	} else {
		$availability = 'auf Anfrage';
	}


	return $availability;
}

add_filter( 'woocommerce_get_availability_text', 'macoffice_custom_get_availability_text', 99, 2 );




	/* Adding the Order Stuff

	woocommerce_before_variations_form
	woocommerce_before_single_variation
	woocommerce_single_variation
	woocommerce_after_single_variation

	*/



	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

	add_filter( "woocommerce_is_sold_individually" , "woocommerce_is_sold_individually_callback", 20, 2 );
	function woocommerce_is_sold_individually_callback( $status, $product ){
		if ( $product->get_sold_individually() ){
			return true;
		}
		return false;
	}



	/* Adding the Quantity Field

	function macoffice_quantity_minus_sign() {
		echo '<div class="quantity-section">';
		echo '<div class="quantity-wrapper">';
		echo '<button type="button" class="minus" >-</button>';
	}

	add_action( 'woocommerce_before_add_to_cart_quantity', 'macoffice_quantity_minus_sign' );



	function macoffice_quantity_plus_sign() {
		echo '<button type="button" class="plus" >+</button>';
		echo '</div>';
		echo '</div>';
	}

	add_action( 'woocommerce_after_add_to_cart_quantity', 'macoffice_quantity_plus_sign' );






	function macoffice_quantity_inputs_for_woocommerce_loop_add_to_cart_link( $html, $product ) {
		if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
			$html = '<form action="' . esc_url( $product->add_to_cart_url() ) . '" class="cart stuff" method="post" enctype="multipart/form-data">';
				$html .= woocommerce_quantity_input( array(), $product, false );
				$html .= '<button type="submit" class="button alt">' . esc_html( $product->add_to_cart_text() ) . '</button>';
				$html .= '</form>';
		}
		return $html;
	}

	add_filter( 'woocommerce_loop_add_to_cart_link', 'macoffice_quantity_inputs_for_woocommerce_loop_add_to_cart_link', 10, 3 );



	/* Adding Quantity Buttons "Plus" and "Minus"

	function macoffice_quantity_plus_minus() {
		 // To run this on the single product page

			if ( ! is_product() ) return;

		 ?>
		 <script type="text/javascript">

				jQuery(document).ready(function(jQuery){

							jQuery('form.cart').on( 'click', 'button.plus, button.minus', function() {

							// Get current quantity values
							var qty = jQuery( this ).closest( 'form.cart' ).find( '.qty' );
							var val = parseFloat(qty.val());
							var max = parseFloat(qty.attr( 'max' ));
							var min = parseFloat(qty.attr( 'min' ));
							var step = parseFloat(qty.attr( 'step' ));


							// Change the value if plus or minus
							if ( jQuery( this ).is( '.plus' ) ) {
								 if ( max && ( max <= val ) ) {
										qty.val( max );
								 }
							else {
								 qty.val( val + step );
									 }
							}
							else {
								 if ( min && ( min >= val ) ) {
										qty.val( min );
								 }
								 else if ( val > 1 ) {
										qty.val( val - step );
								 }
							}

					 });

				});

		 </script>
		 <?php
	}

	add_action( 'wp_footer', 'macoffice_quantity_plus_minus' );

	*/










	/* Changing the WooCommerce Shop Button's Text "Read More" */

	function macoffice_change_readmore_text( $translated_text, $text, $domain ) {

		if ( ! is_admin() && $domain === 'woocommerce' && $translated_text === 'Weiterlesen') {
			$translated_text = 'Zu den Details';
		}

		return $translated_text;
	}

	add_filter( 'gettext', 'macoffice_change_readmore_text', 20, 3 );






	/* ????????????????????????????

	function macoffice_out_of_stock_button( $args ){

		global $product;

		if( $product && !$product->is_in_stock() ){
			return '<a href="' . home_url( 'contact' ) . '">Contact us</a>';
		}
		return $args;
	}


	add_filter( 'woocommerce_loop_add_to_cart_link', 'macoffice_out_of_stock_button' );


	*/



	/* CHANGING THE URL AND PAGE TITLE

	function jpb_custom_meta_permalink( $link, $post ){

	$post_meta = get_post_meta( $post->ID, '<insert your meta key here>', true );

	if( empty( $post_meta ) || !is_string( $post_meta ) )

		 $post_meta = '<insert your default value (could be an empty string) here>';

	$link = str_replace( '!!custom_field_placeholder!!', $post_meta, $link );

	return $link;

	}



	add_filter( 'post_link', 'jpb_custom_meta_permalink', 10, 2 );



	function append_sku_string( $link, $post ) {

	$post_meta = get_post_meta( $post->ID, '_sku', true );

			 if ( 'product' == get_post_type( $post ) ) {

				 $link = $link . '#' .$post_meta;

				 return $link;

			 }

	}

	add_filter( 'post_type_link', 'append_sku_string', 1, 2 );

	*/


	/*

	function append_sku_to_titles() {

	 $all_ids = get_posts( array(
			'post_type' => 'product',
			'numberposts' => -1,
			'post_status' => 'publish',
			'fields' => 'ids'
	));

	foreach ( $all_ids as $id ) {
					$_product = wc_get_product( $id );
					$_sku = $_product->get_sku();
					$_title = $_product->get_title();

					$new_title = $_title . " " . $_sku;

					/*
					*   Tested.
					*   echo $_title + $_sku;
					*   echo("<script>console.log('Old: ".$_title. " - ". $_sku."');</script>");
					*   echo("<script>console.log('New: ".$new_title."');</script>");
					*/


					/*
					$updated = array();
					$updated['ID'] = $id;
					$updated['post_title'] = $new_title;

					wp_update_post( $updated );
	}}

	// Call the function with footer (*Attention)
	add_action( 'wp_footer', 'append_sku_to_titles' );










/* Adding the Description */
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10, 0);


function macoffice_output_long_description() {
	global $product;
	?>

	 <?php
			echo '<div class="single-product-description">';
			echo '<h2 class="single-product-description-header">Produktbeschreibung</h2>';

			$output = wpautop($product->get_description());

			echo $output;
			echo '</div>';
	 ?>

<?php
}

add_action( 'woocommerce_after_single_product_summary', 'macoffice_output_long_description', 15 );




/* Display the Product Modified Date */
add_action( 'woocommerce_after_single_product_summary', 'after_single_product_loop_item_action_callback', 15 );
function after_single_product_loop_item_action_callback() {
		global $product;

		echo '<br><span class="product-date-modified">Stand vom ' . $product->get_date_modified()->date('d.m.Y, H:i') . '</span>';
}



/* Adding Related Products  */
add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 50 );



/* Change WooCommerce "Related products" text */
add_filter('gettext', 'change_rp_text', 10, 3);
add_filter('ngettext', 'change_rp_text', 10, 3);

function change_rp_text($translated, $text, $domain)
	{
 	if ($text === 'Related products' && $domain === 'woocommerce') {
		 	$translated = esc_html__('Weitere beliebte Artikel', $domain);
 	}
 	return $translated;
	}






/*
If a product has cross sell products, show those first, and fill up to 4 total products with others from the same category
Or
If a product has no cross sell products, show 4 products from the same category
*/
function filter_woocommerce_related_products( $related_posts, $product_id, $args ) {
	// Taxonomy
	$taxonomy = 'product_cat';

	// Show products
	$show_products = 4;

	// Get product
	$product = wc_get_product( $product_id );

	// Get cross sell IDs
	$cross_sell_ids = $product->get_cross_sell_ids();

	// Calculate how many filler products are needed
	$category_product_needed_count = $show_products - count( $cross_sell_ids );

	// If category product needed
	if ( $category_product_needed_count >= 1 ) {
			// Retrieves product term ids for a taxonomy.
			$product_cats_ids = wc_get_product_term_ids( $product_id, $taxonomy );

			// Get product id(s) from a certain category, by category-id
			$product_ids_from_cats_ids = get_posts( array(
					'post_type'   => 'product',
					'numberposts' => $category_product_needed_count,
					'post_status' => 'publish',
					'fields'      => 'ids',
					'tax_query'   => array(
							array(
									'taxonomy' => $taxonomy,
									'field'    => 'id',
									'terms'    => $product_cats_ids,
									'operator' => 'IN',
							)
					),
			));

			// Merge array
			$related_posts = array_merge( $cross_sell_ids, $product_ids_from_cats_ids );
	} else {
			// Slice array until show products
			$related_posts = array_slice( $cross_sell_ids, 0, $show_products );
	}

	// Return
	return $related_posts;

}
add_filter( 'woocommerce_related_products', 'filter_woocommerce_related_products', 10, 3 );

// Order by
function filter_woocommerce_output_related_products_args( $args ) {
	$args['orderby'] = 'id';
	$args['order'] = 'ASC';

	return $args;
}

add_filter( 'woocommerce_output_related_products_args', 'filter_woocommerce_output_related_products_args', 10, 1 );

















	/* --- Shopping Cart --- */

/* 	function macoffice_cart_info_shipping_costs( $args ){
		echo '<p>Hinweis: Versand für Österreich ab 50,- € kostenlos - darunter 4,99 € , Ausland bis 80€ 9,99 €</p>';
	}

	add_filter( 'woocommerce_after_cart_contents', 'macoffice_cart_info_shipping_costs',99,1 ); */
<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( );

/* === Remove unused Data === */

// Removes the Add to Cart Button
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10, 0);



/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
/* 		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1> */
		<h1 class="search-title"> <?php echo $wp_query->found_posts; ?> <?php _e( 'Suchergebnisse für: ', 'locale' ); ?>: "<?php the_search_query(); ?>" </h1>

	<?php endif; ?>

	<?php
	// SHOW ONLY IF NOT SHOP PAGE AND MORE THAN 20 PRODUCTS

	if (!is_shop() ) {
		$product_counter = wc_get_loop_prop( 'total' );
		if ($product_counter > 20)  {
			$product_categories = get_terms([
				'taxonomy' => get_queried_object()->taxonomy,
				'parent'   => get_queried_object_id(),
			]);
			echo '<div class="product-categories__container">';
				foreach ($product_categories as $category) {
					if($category->category_parent == 0) { //this checks for 1st level that you wanted
						echo '<div class="product-categories__card card">';
							echo '<a class="product-categories__link" href="' . get_term_link( $category->slug, $category->taxonomy ) . '">';
								echo '<div class="card__container wrapper">';
									$cat_thumb_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
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
			echo '</div>';
			} else {
				echo "";
			}
		}
	?>

	<?php
	/**p
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header>
<?php
if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

echo do_shortcode('[shortcode_error_found]');

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );

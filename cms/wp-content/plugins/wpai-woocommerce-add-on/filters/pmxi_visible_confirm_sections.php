<?php

/**
 *
 * Filter sections visibility on import confirm screen.
 *
 * @param $sections
 * @param $post_type
 *
 * @return array
 */
function pmwi_pmxi_visible_confirm_sections($sections, $post_type) {
	// Render order's template only for bundle and import with WP All Import featured.
	if ( in_array($post_type, ['shop_order','product']) && class_exists('WooCommerce') ) {
        return array();
    }
	return $sections;
}
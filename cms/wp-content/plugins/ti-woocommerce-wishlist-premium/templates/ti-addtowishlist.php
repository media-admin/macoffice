<?php
/**
 * The Template for displaying add to wishlist product button.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/ti-addtowishlist.php.
 *
 * @version             2.5.0
 * @package           TInvWishlist\Template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
wp_enqueue_script( 'tinvwl' );
?>
<div class="tinv-wraper woocommerce tinv-wishlist <?php echo esc_attr( $class_position ) ?>"
	 data-tinvwl_product_id="<?php echo $product->get_id(); ?>">
	<?php if ( $quantity ) { ?>
		<input type="hidden" name="quantity" value="<?php echo absint( $quantity ); ?>"/>
	<?php } ?>
	<?php do_action( 'tinvwl_wishlist_addtowishlist_button', $product, $loop ); ?>
	<?php do_action( 'tinvwl_wishlist_addtowishlist_dialogbox', $product, $loop, $class_position ); ?>
	<div
		class="tinvwl-tooltip"><?php echo wp_kses_post( tinv_get_option( 'add_to_wishlist' . ( $loop ? '_catalog' : '' ), 'text' ) ); ?></div>
</div>

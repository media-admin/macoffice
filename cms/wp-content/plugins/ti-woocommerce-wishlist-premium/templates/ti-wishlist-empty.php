<?php
/**
 * The Template for displaying empty wishlist.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/ti-wishlist-empty.php.
 *
 * @version             2.5.2
 * @package           TInvWishlist\Template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div class="tinv-wishlist woocommerce tinv-wishlist-clear">
	<?php do_action( 'tinvwl_before_wishlist', $wishlist ); ?>
	<?php if ( function_exists( 'wc_print_notices' ) && isset( WC()->session ) ) {
		wc_print_notices();
	} ?>
	<p class="cart-empty woocommerce-info">
		<?php if ( get_current_user_id() === $wishlist['author'] ) {
			$msg = esc_html__( 'Your {wishlist_title} is currently empty.', 'ti-woocommerce-wishlist-premium' );
		} else {
			$msg = esc_html__( '{wishlist_title} is currently empty.', 'ti-woocommerce-wishlist-premium' );
		}

		echo tinvwl_message_placeholders( $msg, null, $wishlist );
		?>
	</p>

	<?php do_action( 'tinvwl_wishlist_is_empty' ); ?>

	<p class="return-to-shop">
		<a class="button tinvwl-button wc-backward"
		   href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'Return To Shop', 'ti-woocommerce-wishlist-premium' ) ) ); ?></a>
	</p>
</div>

<?php
/**
 * Admin wishlists page class
 *
 * @package           TInvWishlist\Admin
 * @since 2.6.0
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) or exit;

/**
 * Admin wishlists page class
 */
class TInvWL_Admin_Wishlist extends TInvWL_Admin_BaseSection {

	/**
	 * Returns menu array
	 *
	 * @return array
	 */
	public function menu(): array {
		return [
			'title'      => __( 'Wishlists', 'ti-woocommerce-wishlist-premium' ),
			'method'     => [ $this, '_print_' ],
			'slug'       => '',
			'capability' => 'tinvwl_wishlists',
			'roles'      => ['administrator', 'shop_manager' ],
		];
	}

	/**
	 * General page wishlists
	 *
	 * @param int $id Id parameter.
	 * @param string $cat Category parameter.
	 */
	public function _print_general( int $id = 0, string $cat = '' ): void {
		$data = [
			'_header' => __( 'Wishlists', 'ti-woocommerce-wishlist-premium' ),
			'table'   => new TInvWL_Admin_Wishlist_Table( $this->_name, $this->_version ),
		];
		$data = apply_filters( 'tinvwl_wishlist_general', $data );
		TInvWL_View::render( 'wishlists', $data );
	}

	/**
	 * General page subscribers wishlist
	 *
	 * @param integer $id Id parameter.
	 * @param string $cat Category parameter.
	 */
	function _print_subscribers( $id = 0, $cat = '' ) {
		$wl       = new TInvWL_Wishlist( $this->_name );
		$wishlist = $wl->get_by_id( $id );
		if ( empty( $wishlist ) ) {
			TInvWL_View::set_redirect( $this->admin_url( '' ) );
		}
		if ( 'private' === $wishlist['status'] ) {
			TInvWL_View::set_redirect( $this->admin_url( '' ) );
		}
		$data = array(
			'_header' => sprintf( __( 'Wishlist "%s" Subscribers', 'ti-woocommerce-wishlist-premium' ), esc_html( $wishlist['title'] ) ),
			'table'   => new TInvWL_Admin_Wishlist_Subscribers( $wishlist, $this->_name, $this->_version ),
		);
		$data = apply_filters( 'tinvwl_wishlist_subscribers', $data );
		TInvWL_View::render( 'wishlists', $data );
	}
}

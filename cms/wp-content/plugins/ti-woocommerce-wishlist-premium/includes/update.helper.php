<?php
/**
 * Update plugin class
 *
 * @since             1.0.0
 * @package           TInvWishlist
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Update plugin class.
 */
class TInvWL_Update {

	/**
	 * Plugin name.
	 *
	 * @var string
	 */
	private string $_name;

	/**
	 * Current version.
	 *
	 * @var string
	 */
	private string $_version;

	/**
	 * Previous version.
	 *
	 * @var string
	 */
	private string $_prev;

	/**
	 * Regular expression for sorting version function.
	 *
	 * @var string
	 */
	private const REGEXP = '/^up_/i';

	/**
	 * TInvWL_Update constructor.
	 *
	 * Get update methods and apply.
	 *
	 * @param string $version Current version.
	 * @param string $previous_version Previous version.
	 */
	public function __construct( string $version, string $previous_version = '0' ) {
		$this->_name    = TINVWL_PREFIX;
		$this->_version = $version;
		$this->_prev    = $previous_version;
		$methods        = array_filter( get_class_methods( $this ), [ $this, 'filter' ] );

		if ( ! empty( $methods ) ) {
			uasort( $methods, [ $this, 'sort' ] );
			foreach ( $methods as $method ) {
				call_user_func( [ $this, $method ], $previous_version );
			}
		}
	}

	/**
	 * Filter methods.
	 *
	 * @param string $method Method name from this class.
	 *
	 * @return bool
	 */
	public function filter( string $method ): bool {
		if ( ! preg_match( self::REGEXP, $method ) ||
		     version_compare( $this->_prev, $this->prepare( $method ), 'ge' ) ) {
			return false;
		}

		return version_compare( $this->_version, $this->prepare( $method ), 'ge' );
	}

	/**
	 * Sort methods.
	 *
	 * @param string $method1 Method name first from this class.
	 * @param string $method2 Method name second from this class.
	 *
	 * @return int
	 */
	public function sort( string $method1, string $method2 ): int {
		return version_compare( $this->prepare( $method1 ), $this->prepare( $method2 ) );
	}

	/**
	 * Convert method name to version.
	 *
	 * @param string $method Method name from this class.
	 *
	 * @return string
	 */
	public function prepare( string $method ): string {
		return str_replace( '_', '.', preg_replace( self::REGEXP, '', $method ) );
	}

	/**
	 * Example of the method updating.
	 *
	 * @param string $previous_version Previous version.
	 */
	public function up_0_0_0( string $previous_version = '0' ): void {
		// Empty method used for demonstration.
	}

	/**
	 * Reset database version
	 *
	 * @param string $previous_version Previous version value.
	 */
	function up_p_1_1_2_1( $previous_version = 0 ) {
		if ( version_compare( 'p.1.1.2', $previous_version, 'ge' ) && version_compare( 'p.1.0.0', $previous_version, 'le' ) ) {
			$prev_db = get_option( TINVWL_PREFIX . '_db_verp', null );
			if ( ! is_null( $prev_db ) ) {
				update_option( TINVWL_PREFIX . '_db_verp', '0.0.9' );
			}
		}
	}

	/**
	 * Set runed wizard.
	 *
	 * @param string $previous_version Previous version.
	 */
	function up_p_1_1_2_9_1( string $previous_version = '0' ): void {
		update_option( 'tinvwl_wizard', true );
	}

	/**
	 * Backwards compatibility of the quantity functional
	 */
	function up_p_1_4_9_1() {
		if ( $value = tinv_get_option( 'product_table', 'colm_quantity' ) ) {
			tinv_update_option( 'general', 'quantity_func', $value );
		}
	}

	/**
	 * Fix name field
	 */
	function up_p_1_5_1() {
		if ( $value = tinv_get_option( 'product_table', 'add_to_card' ) ) {
			tinv_update_option( 'product_table', 'add_to_cart', $value );
		}
		if ( $value = tinv_get_option( 'product_table', 'text_add_to_card' ) ) {
			tinv_update_option( 'product_table', 'text_add_to_cart', $value );
		}
		if ( $value = tinv_get_option( 'table', 'add_select_to_card' ) ) {
			tinv_update_option( 'table', 'add_select_to_cart', $value );
		}
		if ( $value = tinv_get_option( 'table', 'text_add_select_to_card' ) ) {
			tinv_update_option( 'table', 'text_add_select_to_cart', $value );
		}
		if ( $value = tinv_get_option( 'table', 'add_all_to_card' ) ) {
			tinv_update_option( 'table', 'add_all_to_cart', $value );
		}
		if ( $value = tinv_get_option( 'table', 'text_add_all_to_card' ) ) {
			tinv_update_option( 'table', 'text_add_all_to_cart', $value );
		}
	}

	/**
	 * Clean up empty wishlists.
	 */
	function up_p_1_7_0( $previous_version = 0 ) {
		if ( $previous_version ) {
			global $wpdb;
			$wishlists_table       = sprintf( '%s%s_%s', $wpdb->prefix, $this->_name, 'lists' );
			$wishlists_items_table = sprintf( '%s%s_%s', $wpdb->prefix, $this->_name, 'items' );
			$sql                   = "DELETE FROM wl USING `{$wishlists_table}` AS wl WHERE NOT EXISTS( SELECT * FROM `{$wishlists_items_table}` WHERE {$wishlists_items_table}.wishlist_id = wl.ID ) AND wl.type='default'";
			$wpdb->get_results( $sql, ARRAY_A ); // WPCS: db call ok; no-cache ok; unprepared SQL ok.
		}
	}

	function up_p_1_9_0() {

		$class = tinv_get_option( 'add_to_wishlist_catalog', 'class' );
		if ( 'button' == tinv_get_option( 'add_to_wishlist_catalog', 'type' ) && empty( $class ) ) {
			tinv_update_option( 'add_to_wishlist_catalog', 'class', 'button tinvwl-button' );
		}
		$class = tinv_get_option( 'add_to_wishlist', 'class' );
		if ( 'button' == tinv_get_option( 'add_to_wishlist', 'type' ) && empty( $class ) ) {
			tinv_update_option( 'add_to_wishlist', 'class', 'button tinvwl-button' );
		}
		$class = tinv_get_option( 'add_to_wishlist_cart', 'class' );
		if ( 'button' == tinv_get_option( 'add_to_wishlist_cart', 'type' ) && empty( $class ) ) {
			tinv_update_option( 'add_to_wishlist_cart', 'class', 'button tinvwl-button' );
		}
		$class = tinv_get_option( 'add_to_wishlist_cart', 'item_class' );
		if ( 'button' == tinv_get_option( 'add_to_wishlist_cart', 'item_type' ) && empty( $class ) ) {
			tinv_update_option( 'add_to_wishlist_cart', 'item_class', 'button tinvwl-button' );
		}
		if ( 'font-icon' == tinv_get_option( 'topline', 'icon' ) ) {
			tinv_update_option( 'topline', 'icon', 'heart' );
		}
		if ( 'font-icon' == tinv_get_option( 'add_to_wishlist', 'icon' ) ) {
			tinv_update_option( 'add_to_wishlist', 'icon', 'heart' );
		}
		if ( 'font-icon' == tinv_get_option( 'add_to_wishlist_catalog', 'icon' ) ) {
			tinv_update_option( 'add_to_wishlist_catalog', 'icon', 'heart' );
		}
		if ( 'font-icon' == tinv_get_option( 'add_to_wishlist_cart', 'icon' ) ) {
			tinv_update_option( 'add_to_wishlist_cart', 'icon', 'heart' );
		}
		if ( 'font-icon' == tinv_get_option( 'add_to_wishlist_cart', 'item_icon' ) ) {
			tinv_update_option( 'add_to_wishlist_cart', 'item_icon', 'heart' );
		}
	}

	/**
	 * Buttons class fallback.
	 */
	function up_1_16_2() {
		{
			wp_schedule_single_event( time(), 'tinvwl_flush_rewrite_rules' );
		}
	}
}

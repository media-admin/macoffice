<?php

/**
 * WooCommerce Brands plugin integration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('AWS_Woo_Brands')) :

    /**
     * Class for main plugin functions
     */
    class AWS_Woo_Brands {

        /**
         * @var AWS_Woo_Brands The single instance of the class
         */
        protected static $_instance = null;

        public $form_id = 1;
        public $filter_id = 1;

        /**
         * Main AWS_Woo_Brands Instance
         *
         * Ensures only one instance of AWS_Woo_Brands is loaded or can be loaded.
         *
         * @static
         * @return AWS_Woo_Brands - Main instance
         */
        public static function instance()
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Constructor
         */
        public function __construct() {

            add_action( 'aws_search_start', array( $this, 'search_start' ), 10, 3  );

            add_filter( 'aws_indexed_content', array( $this, 'aws_indexed_content'), 10, 3 );

            add_filter( 'aws_search_pre_filter_single_product', array( $this, 'aws_search_pre_filter_single_product'), 10, 3  );

            add_filter( 'aws_admin_page_options', array( $this, 'add_admin_options' ) );

        }

        /*
         * On search start
         */
        public function search_start( $s, $form_id, $filter_id  ) {
            $this->form_id = $form_id;
            $this->filter_id = $filter_id;
        }

        /*
         * Index custom tabs content
         */
        public function aws_indexed_content( $content, $id, $product ) {

            if ( apply_filters( 'aws_indexed_brands', true ) ) {

                $brands = $this->get_product_brands( $id );
                if ( $brands && is_array( $brands ) ) {

                    foreach( $brands as $brand ) {
                        if ( isset( $brand['name'] ) ) {
                            $brand_name = $brand['name'];
                            if ( function_exists( 'wp_encode_emoji' ) ) {
                                $brand_name = wp_encode_emoji( $brand_name );
                            }
                            $brand_name = AWS_Helpers::strip_shortcodes( $brand_name );
                            $content = $content . ' ' . $brand_name;
                        }
                    }

                }
            }

            return $content;

        }

        /*
         * Add brands to search results output
         */
        public function aws_search_pre_filter_single_product( $new_result, $post_id, $product ) {

            $show_brands = AWS_PRO()->get_settings( 'show_result_brands', $this->form_id, $this->filter_id, 'woocommerce-brands/woocommerce-brands.php' );

            if ( $show_brands === 'true' ) {
                $brands = $this->get_product_brands( $post_id );
                $new_result['brands'] = $brands;
            }

            return $new_result;

        }

        /*
         * Add brands admin options
         */
        public function add_admin_options( $options ) {

            $new_options = array();

            if ( $options ) {
                foreach ( $options as $section_name => $section ) {
                    foreach ( $section as $values ) {

                        $new_options[$section_name][] = $values;

                        if ( isset( $values['id'] ) && $values['id'] === 'show_stock' ) {

                            $new_options[$section_name][] = array(
                                "name"  => __( "Show brands in products", "advanced-woo-search" ),
                                "desc"  => __( "Show brands with all products in search results.", "advanced-woo-search" ),
                                "id"    => "show_result_brands",
                                "inherit" => "true",
                                "value" => 'false',
                                "type"  => "radio",
                                "depends" => AWS_Helpers::is_plugin_active( 'woocommerce-brands/woocommerce-brands.php' ),
                                'choices' => array(
                                    'true'  => __( 'On', 'advanced-woo-search' ),
                                    'false' => __( 'Off', 'advanced-woo-search' )
                                )
                            );

                        }

                    }
                }

                return $new_options;

            }

            return $options;

        }

        /*
         * Get product brands
         */
        private function get_product_brands( $id ) {

            $terms = get_the_terms( $id, 'product_brand' );

            if ( is_wp_error( $terms ) ) {
                return '';
            }

            if ( empty( $terms ) ) {
                return '';
            }

            $brands_array = array();

            foreach ( $terms as $term ) {

                $thumb_src = AWS_Helpers::get_term_thumbnail( $term->term_id );

                $brands_array[] = array(
                    'name'  => $term->name,
                    'image' => $thumb_src
                );

            }

            return $brands_array;

        }

    }

endif;

AWS_Woo_Brands::instance();
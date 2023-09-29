<?php

/**
 * AWS plugin integration for WPML
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('AWS_WPML')) :

    /**
     * Class for main plugin functions
     */
    class AWS_WPML {

        /**
         * @var AWS_WPML The single instance of the class
         */
        protected static $_instance = null;

        private $data = array();

        /**
         * Main AWS_WPML Instance
         *
         * Ensures only one instance of AWS_WPML is loaded or can be loaded.
         *
         * @static
         * @return AWS_WPML - Main instance
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

            add_filter( 'aws_options_get_tax_terms', array( $this, 'aws_options_get_tax_terms' ), 1, 2 );

            add_filter( 'aws_filters_condition_group', array( $this, 'tax_condition_group' ), 1 );

            add_filter( 'aws_filters_condition_group', array( $this, 'product_condition_group' ), 1 );

            add_filter( 'aws_indexed_data', array( $this, 'indexed_data_trans_fallback' ), 1, 2 );

        }

        /*
         * Add new options terms for other languages
         */
        public function aws_options_get_tax_terms( $options, $tax_name ) {

            global $sitepress;

            if ( $sitepress ) {

                $current_lang = $sitepress->get_current_language();
                $default_language = $sitepress->get_default_language();

                if ( $current_lang !== $default_language ) {

                    $sitepress->switch_lang( $default_language );

                    $tax = get_terms( array(
                        'taxonomy'   => $tax_name,
                        'hide_empty' => false,
                    ) );

                    if ( ! empty( $tax ) ) {
                        foreach ( $tax as $tax_item ) {
                            $options[$tax_item->term_id] = $tax_item->name;
                        }
                    }

                    $sitepress->switch_lang( $current_lang );

                }

            }

            return $options;

        }

        /*
         * Fix condition rules for product taxonomies
         */
        public function tax_condition_group( $group_rules ) {

            foreach( $group_rules as $key => $condition_rule ) {

                $condition_name = $condition_rule['param'];
                $tax_name = '';

                if ( $condition_name === 'product_category' || $condition_name === 'product_tag' ) {
                    $tax_name = $condition_name;
                } elseif ( $condition_name === 'product_taxonomy' || $condition_name === 'product_attributes' || $condition_name === 'term_taxonomy' ) {
                    $tax_name = isset( $condition_rule['suboption'] ) ? $condition_rule['suboption'] : '';
                }

                if ( $tax_name ) {

                    global $sitepress;

                    if ( $sitepress ) {

                        $current_lang = $sitepress->get_current_language();

                        $trid = $sitepress->get_element_trid( intval( $condition_rule['value'] ), 'tax_' . $tax_name );
                        if ( $trid ) {
                            $translations = $sitepress->get_element_translations( $trid, 'tax_' . $tax_name );
                            if ( $translations && isset( $translations[$current_lang] ) ) {
                                $term_trans = $translations[$current_lang];
                                $group_rules[$key]['value'] = $term_trans->element_id;
                            }
                        }

                    }

                }

            }

            return $group_rules;

        }

        /*
         * Fix condition rules for products
         */
        public function product_condition_group( $group_rules ) {

            foreach( $group_rules as $key => $condition_rule ) {

                if ( $condition_rule['param'] === 'product' ) {

                    global $sitepress;

                    if ( $sitepress ) {

                        $current_lang = $sitepress->get_current_language();

                        $trid = $sitepress->get_element_trid( intval( $condition_rule['value'] ), 'post_product' );
                        if ( $trid ) {
                            $translations = $sitepress->get_element_translations( $trid, 'post_product' );
                            if ( $translations && isset( $translations[$current_lang] ) ) {
                                $term_trans = $translations[$current_lang];
                                $group_rules[$key]['value'] = $term_trans->element_id;
                            }
                        }

                    }

                }

            }

            return $group_rules;

        }

        /*
         * Use translation if available or fallback to default language ( if enabled )
         */
        public function indexed_data_trans_fallback( $data, $id ) {

            global $sitepress;

            $product_sync = false;
            $product_variation_sync = false;
            $default_lang = '';
            $all_languages = array();
            $translations = array();

            if ( function_exists( 'wpml_get_setting' ) ) {
                $custom_posts_sync = wpml_get_setting('custom_posts_sync_option', false );
                if ( $custom_posts_sync ) {
                    $product_sync = isset( $custom_posts_sync['product'] ) && $custom_posts_sync['product'] == 2;
                    $product_variation_sync = isset( $custom_posts_sync['product_variation'] ) && $custom_posts_sync['product_variation'] == 2;
                }
            }

            if ( has_filter( 'wpml_default_language' ) ) {
                $default_lang = apply_filters('wpml_default_language', NULL );
            }

            if ( has_filter( 'wpml_post_language_details' ) ) {
                $current_lang_details = apply_filters( 'wpml_post_language_details', NULL, $id );
            }

            if ( has_filter( 'wpml_active_languages' ) ) {
                $all_languages_a = apply_filters( 'wpml_active_languages', NULL );
                if ( ! empty( $all_languages_a ) ) {
                    foreach ( $all_languages_a as $lang_item ) {
                        $lang_item_code = $lang_item['language_code'];
                        $all_languages[$lang_item_code] = $lang_item_code;
                    }
                }
            }

            if ( ! empty( $all_languages ) && $data['lang'] === $default_lang && $sitepress && method_exists( $sitepress, 'get_element_trid' ) && method_exists( $sitepress, 'get_element_translations' ) ) {

                $type = '';
                if ( $data['type'] !== 'child' && $product_sync ) {
                    $type = 'post_product';
                }
                if ( $data['type'] === 'child' && $product_variation_sync ) {
                    $type = 'post_product_variation';
                }

                if ( $type ) {

                    $trid = $sitepress->get_element_trid( $id, $type );
                    if ( $trid ) {
                        $translations = $sitepress->get_element_translations( $trid ) ;
                    }

                    foreach( $all_languages as $lang_code ) {
                        if ( ! empty( $translations ) && isset( $translations[$lang_code] ) ) {
                            continue;
                        }
                        $data['lang'] .= ' ' . $lang_code;
                    }

                }

            }

            return $data;

        }

    }

endif;

AWS_WPML::instance();
<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


if ( ! class_exists( 'AWS_Admin_Helpers' ) ) :

    /**
     * Class for plugin help methods
     */
    class AWS_Admin_Helpers {

        /**
         * Get array of allowed tags for wp_kses function
         * @param array $allowed_tags Tags that is allowed to display
         * @return array $tags
         */
        static public function get_kses( $allowed_tags = array() ) {

            $tags = array(
                'a' => array(
                    'href' => array(),
                    'title' => array()
                ),
                'br' => array(),
                'em' => array(),
                'strong' => array(),
                'b' => array(),
                'code' => array(),
                'blockquote' => array(
                    'cite' => array(),
                ),
                'p' => array(),
                'i' => array(),
                'h1' => array(),
                'h2' => array(),
                'h3' => array(),
                'h4' => array(),
                'h5' => array(),
                'h6' => array(),
                'img' => array(
                    'alt' => array(),
                    'src' => array()
                )
            );

            if ( is_array( $allowed_tags ) && ! empty( $allowed_tags ) ) {
                foreach ( $tags as $tag => $tag_arr ) {
                    if ( array_search( $tag, $allowed_tags ) === false ) {
                        unset( $tags[$tag] );
                    }
                }

            }

            return $tags;

        }

        /**
         * Get array of default allowed tags for textarea
         * @return array $tags
         */
        static public function kses_textarea_allowed_tags() {
            return array( 'a', 'br', 'em', 'strong', 'b', 'code', 'blockquote', 'p', 'i' );
        }

        /**
         * Filter index option when plugin is updated
         * @param array $options Array of plugin settings
         * @param int $options_key Current option key
         * @param int $key Current tab option key
         * @param int|string $option Current tab option value
         * @return array $options
         */
        static public function filter_index_sources_on_update( $options, $options_key, $key, $option ) {

            if ( isset( $option['id'] ) && $option['id'] === 'index_sources_attr' && isset( $option['choices'] ) && is_array( $option['choices'] ) ) {
                foreach ( $option['choices'] as $attr_val => $attr_label ) {
                    $options[$options_key][$key]['value'][$attr_val] = 1;
                }
            }

            if ( isset( $option['id'] ) && $option['id'] === 'index_sources_tax' && isset( $option['choices'] ) && is_array( $option['choices'] ) ) {
                foreach ( $option['choices'] as $attr_val => $attr_label ) {
                    $options[$options_key][$key]['value'][$attr_val] = 1;
                }
            }

            if ( isset( $option['id'] ) && $option['id'] === 'index_sources_meta' ) {

                $settings = AWS_Admin_Options::get_settings();
                $search_for_meta = array();
                $search_for_meta_choices = array();

                if ( $settings ) {
                    foreach ($settings as $search_instance_num => $search_instance_settings) {
                        if ( isset( $search_instance_settings['filters'] ) ) {
                            foreach( $search_instance_settings['filters'] as $filter_num => $filter_settings ) {
                                if ( isset( $filter_settings['search_in_meta'] ) && is_array( $filter_settings['search_in_meta'] ) && ! empty(  $filter_settings['search_in_meta'] ) ) {
                                    foreach( $filter_settings['search_in_meta'] as $meta_key => $meta_enables ) {
                                        if ( $meta_enables && ! isset( $search_for_meta[$meta_key] ) ) {
                                            $search_for_meta[$meta_key] = 1;
                                            $search_for_meta_choices[$meta_key] = $meta_key;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $options[$options_key][$key]['value'] = $search_for_meta;
                $options[$options_key][$key]['choices'] = $search_for_meta_choices;

            }

            if ( isset( $option['id'] ) && $option['id'] === 'index_sources' && isset( $option['value'] ) ) {
                $options[$options_key][$key]['value']['attr'] = 1;
                $options[$options_key][$key]['value']['tax'] = 1;
                $options[$options_key][$key]['value']['meta'] = 1;
            }

            return $options;

        }

        /**
         * Pagination for admin options meta fields
         * @return string Pagination html output
         */
        static public function meta_fields_pagination() {

            $output = '';

            if ( ! isset( $_GET['section'] ) || $_GET['section'] !== 'meta' ) {
                return $output;
            }

            $fields_count = AWS_Helpers::get_custom_fields_count();
            $limit = 500;
            $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;

            if ( $fields_count <= $limit ) {
                return $output;
            }

            $page_links = paginate_links( array(
                'base' => add_query_arg( 'pagenum', '%#%' ),
                'format' => '',
                'prev_text' => __( '&laquo;', 'advanced-woo-search' ),
                'next_text' => __( '&raquo;', 'advanced-woo-search' ),
                'total' => ceil( $fields_count / $limit ) ,
                'current' => $pagenum
            ) );

            if ( $page_links ) {
                $output = '<div class="tablenav"><div style="margin: 10px 0 0;">' . $page_links . '</div></div>';
            }

            return $output;

        }

        /*
         * Check for incorrect filtering rules and return them
         * @return string
         */
        static public function check_for_incorrect_filtering_rules( $filters ) {

            $incorrect_rules_string = '';
            $check_rules = array( 'product', 'current_user', 'current_user_role', 'current_user_device', 'current_page', 'current_page_template', 'current_page_type', 'current_page_archives' );

            if ( $filters && ! empty( $filters ) ) {
                foreach ( $filters as $cond_group ) {

                    $maybe_wrong_rules = array();

                    foreach ( $cond_group as $cond_rule ) {
                        $rule_name = $cond_rule['param'];
                        if ( array_search( $rule_name, $check_rules ) !== false && $cond_rule['operator'] === 'equal' ) {
                            $maybe_wrong_rules[$rule_name][] = $cond_rule;
                        }
                        if ( isset( $maybe_wrong_rules[$rule_name] ) && count( $maybe_wrong_rules[$rule_name] ) > 1 ) {
                            foreach ( $maybe_wrong_rules[$rule_name] as $rule ) {
                                $rule_value = isset( $rule['value']  ) ? $rule['value'] : '';
                                $incorrect_rules_string .= $rule['param'] . ' -> ' . 'equal to' . ' -> ' . $rule_value .  '<br>';
                            }
                            break;
                        }
                    }

                    if ( $incorrect_rules_string ) {
                        break;
                    }

                }
            }

            return $incorrect_rules_string;

        }

        /*
         * Check for incorrect filtering rules and return them
         * @return string
         */
        static public function user_admin_capability() {

            /**
             * What capability current user must have to view settings page
             * @since 2.99
             * @param string $capability Minimal capability required to view plugin settings page
             */
            return apply_filters( 'aws_admin_capability', 'manage_options' );

        }

    }

endif;
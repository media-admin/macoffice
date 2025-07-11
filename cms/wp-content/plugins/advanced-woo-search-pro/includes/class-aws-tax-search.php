<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'AWS_Tax_Search' ) ) :

    /**
     * Class for admin condition rules
     */
    class AWS_Tax_Search {

        /**
         * @var array AWS_Tax_Search Data
         */
        private $data = array();

        /**
         * @var array AWS_Tax_Search Taxonomy name
         */
        private $taxonomy;

        /**
         * @var string AWS_Tax_Search Search logic
         */
        private $search_logic;

        /**
         * @var string AWS_Tax_Search Exact search or not
         */
        private $search_exact;

        /**
         * @var string AWS_Users_Search Search rule ( %s%, s% )
         */
        private $search_rule;

        /**
         * @var string AWS_Tax_Search Search string
         */
        private $search_string;

        /**
         * @var string AWS_Tax_Search Unfiltered search string
         */
        private $search_string_unfiltered;

        /**
         * @var array AWS_Tax_Search Search terms array
         */
        private $search_terms;

        /**
         * @var array AWS_Tax_Search Normalized search terms array
         */
        private $search_terms_normalized;

        /**
         * @var array AWS_Tax_Search Advanced filters
         */
        private $filters;

        /**
         * @var AWS_Tax_Search Number of search results $results_num
         */
        private $results_num = 10;

        /**
         * @var AWS_Tax_Search ID of current form instance $form_id
         */
        private $form_id = 0;

        /**
         * @var AWS_Tax_Search ID of current filter $filter_id
         */
        private $filter_id = 0;

        /*
         * Constructor
         */
        public function __construct( $taxonomy, $data ) {

            /**
             * Filters array taxonomies search data
             * @since 1.84
             * @param array $data Array of search data
             * @param string $taxonomy Taxonomy name
             */
            $data = apply_filters( 'aws_tax_search_data', $data, $taxonomy );

            $this->data = $data;

            $this->taxonomy = $taxonomy;
            $this->search_logic = isset( $data['search_logic'] ) ? $data['search_logic'] : 'or';
            $this->search_exact = isset( $data['search_exact'] ) ? $data['search_exact'] : 'false';
            $this->search_rule = isset( $data['search_rule'] ) ? $data['search_rule'] : 'contains';
            $this->search_string = isset( $data['s'] ) ? $data['s'] : '';
            $this->search_string_unfiltered = isset( $data['s_nonormalize'] ) ? $data['s_nonormalize'] : $this->search_string ;
            $this->search_terms = isset( $data['search_terms'] ) ? $data['search_terms'] : array();
            $this->search_terms_normalized = array();
            $this->filters = isset( $data['adv_filters']['term'] ) ? $data['adv_filters']['term'] : array();
            $this->results_num = isset( $data['pages_results_num'] ) ? $data['pages_results_num'] : 10;
            $this->form_id = isset( $data['form_id'] ) ? $data['form_id'] : 1;
            $this->filter_id = isset( $data['filter_id'] ) ? $data['filter_id'] : 1;

        }

        /**
         * Get search results
         * @return array Results array
         */
        public function get_results() {

            if ( ! $this->search_terms || empty( $this->search_terms ) ) {
                return array();
            }

            global $wpdb;

            $search_query = '';
            $search_logic_operator = 'OR';
            $search_string_unfiltered = '';

            if ( $this->search_logic === 'and' ) {
                $search_logic_operator = 'AND';
            }

            $like = '%' . $wpdb->esc_like( $this->search_string_unfiltered ) . '%';

            if ( $this->search_exact === 'true' ) {
                $filtered_terms_full = $wpdb->prepare( '( name = "%s" )', $this->search_string_unfiltered );
            } elseif ( $this->search_rule === 'begins' ) {
                $filtered_terms_full = $wpdb->prepare( '( name LIKE %s OR name LIKE %s )', $wpdb->esc_like( $this->search_string_unfiltered ) . '%', '% ' . $wpdb->esc_like( $this->search_string_unfiltered ) . '%' );
            } else {
                $filtered_terms_full = $wpdb->prepare( '( name LIKE %s )', $like );
            }

            $search_array = array_map( array( 'AWS_Helpers', 'singularize' ), $this->search_terms  );
            $this->search_terms_normalized = $search_array;

            // Group terms in groups with their synonyms
            $new_search_array = array();
            $search_array_with_synonyms = $this->synonyms( $search_array );
            foreach ( $search_array_with_synonyms as $term_with_synonyms ) {
                $group_terms = $this->get_search_array( $term_with_synonyms );
                $new_search_array[] = '(' . implode( ' OR ', $group_terms ) . ')';
            }

            $search_array = $new_search_array;
            $search_array_chars = $this->get_unfiltered_search_array();

            if ( $search_array_chars ) {
                $search_string_unfiltered = sprintf( 'OR ( %s )', implode( sprintf( ' %s ', $search_logic_operator ), $this->get_search_array( $search_array_chars ) ) );
            }

            $search_query .= sprintf( ' AND ( ( %s ) OR %s %s )', implode( sprintf( ' %s ', $search_logic_operator ), $search_array ), $filtered_terms_full, $search_string_unfiltered );

            $search_results = $this->query( $search_query );
            $result_array = $this->output( $search_results );

            return $result_array;

        }

        /**
         * Search query
         * @param string $search_query SQL query
         * @return array SQL query results
         */
        private function query( $search_query ) {

            global $wpdb;

            $filters = '';
            $taxonomies_array = array_map( array( $this, 'prepare_tax_names' ), $this->taxonomy );
            $taxonomies_names = implode( ',', $taxonomies_array );

            /**
             * Max number of terms to show
             * @since 1.64
             * @param int
             */
            $terms_number = apply_filters( 'aws_search_terms_number', $this->results_num );
            if ( ! $terms_number ) {
                return array();
            }

            $filters = $this->get_filters();

            $relevance_array = $this->get_relevance_array();

            if ( $relevance_array && ! empty( $relevance_array ) ) {
                $relevance_query = sprintf( ' (SUM( %s )) ', implode( ' + ', $relevance_array ) );
            } else {
                $relevance_query = '0';
            }

            // For multilingual shops
            $search_query .= $this->get_lang_query();

            $sql = "
			SELECT
				distinct($wpdb->terms.name),
				$wpdb->terms.term_id,
				$wpdb->term_taxonomy.taxonomy,
				$wpdb->term_taxonomy.count,
				{$relevance_query} as relevance
			FROM
				$wpdb->terms
				, $wpdb->term_taxonomy
			WHERE 1 = 1
				{$search_query}
				AND $wpdb->term_taxonomy.taxonomy IN ( {$taxonomies_names} )
				AND $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id
			    AND count > 0
			    {$filters}
			    GROUP BY term_id
			    ORDER BY relevance DESC, term_id DESC
			LIMIT 0, {$terms_number}";

            $sql = trim( preg_replace( '/\s+/', ' ', $sql ) );

            /**
             * Filter terms search query
             * @since 1.82
             * @param string $sql Sql query
             * @param string $taxonomy Taxonomy name
             * @param string $search_query Search query
             */
            $sql = apply_filters( 'aws_terms_search_query', $sql, $this->taxonomy, $search_query );

            $search_results = $wpdb->get_results( $sql );

            return $search_results;

        }

        /**
         * Order and output search results
         * @param array SQL query results
         * @return array Array of taxonomies results
         */
        private function output( $search_results ) {

            $result_array = array();

            if ( ! empty( $search_results ) && !is_wp_error( $search_results ) ) {

                foreach ( $search_results as $result ) {

                    $term_image = '';
                    $count = '';
                    $parent = '';
                    $slug = '';

                    if ( $result->count > 0 ) {
                        $count = $result->count;
                    }

                    $term = get_term( $result->term_id, $result->taxonomy );

                    if ( $term != null && !is_wp_error( $term ) ) {
                        $term_link  = get_term_link( $term );
                        $term_image = AWS_Helpers::get_term_thumbnail( $result->term_id );
                        $parent     = is_object( $term ) && property_exists( $term, 'parent' ) ? $term->parent : '';
                        $slug       = $term->slug;
                    } else {
                        continue;
                    }

                    $new_result = array(
                        'name'     => $result->name,
                        'id'       => $result->term_id,
                        'slug'     => $slug,
                        'count'    => $count,
                        'link'     => $term_link,
                        'excerpt'  => '',
                        'parent'   => $parent,
                        'image'    => $term_image
                    );

                    /**
                     * Filters taxonomy term result
                     * @since 2.87
                     * @param array $new_result Taxonomy term result
                     * @param object $term Term object
                     * @param string $taxonomy Name of taxonomy
                     */
                    $new_result = apply_filters( 'aws_search_tax_result_item', $new_result, $term, $result->taxonomy );

                    $result_array[$result->taxonomy][] = $new_result;

                }

                /**
                 * Filters array of custom taxonomies that must be displayed in search results
                 *
                 * @since 1.55
                 *
                 * @param array $result_array Array of custom taxonomies
                 * @param string $taxonomy Name of taxonomy
                 * @param string $s Search query
                 */
                $result_array = apply_filters( 'aws_search_tax_results', $result_array, $this->taxonomy, $this->search_string );

            }

            return $result_array;

        }

        /**
         * Get taxonomies relevance array
         *
         * @return array Relevance array
         */
        private function get_relevance_array() {

            global $wpdb;

            $relevance_array = array();

            $relevance_scores = AWS_Helpers::get_relevance_scores( $this->data );

            $relevance_full = $relevance_scores['tax_name'] * 2;
            $relevance_array[] = $wpdb->prepare( "( case when ( name = '%s' ) then {$relevance_full} else 0 end )", $this->search_string_unfiltered );

            foreach ( $this->search_terms as $search_term ) {

                $search_term_len = strlen( $search_term );

                $relevance_equal = $relevance_scores['tax_name'] + 20 * $search_term_len;
                $relevance_like = $relevance_scores['tax_name'] / 2 + 2 * $search_term_len;

                $like = '%' . $wpdb->esc_like( $search_term ) . '%';
                $like_unfiltered = '%' . $wpdb->esc_like( $this->search_string_unfiltered ) . '%';
                $match_full_words = '\\b' . $wpdb->esc_like( $search_term ) . '\\b';

                // match full words inside taxonomy name
                $relevance_array[] = $wpdb->prepare( "( case when ( name REGEXP '%s' ) then {$relevance_equal} else 0 end )", $match_full_words);

                if ( $this->search_rule === 'begins' ) {
                    $relevance_array[] = $wpdb->prepare( "( case when ( name LIKE %s OR name LIKE %s ) then {$relevance_like} else 0 end )", $wpdb->esc_like( $search_term ) . '%', '% ' . $wpdb->esc_like( $search_term ) . '%' );
                } else {
                    $relevance_array[] = $wpdb->prepare( "( case when ( name LIKE %s ) then {$relevance_like} else 0 end )", $like );
                }

                if ( $terms_desc_search = apply_filters( 'aws_search_terms_description', false ) ) {

                    $relevance_desc = $relevance_scores['tax_desc'] / 2 + 2 * $search_term_len;
                    $relevance_desc_equal = $relevance_scores['tax_desc'] + 20 * $search_term_len;

                    $relevance_array[] = $wpdb->prepare( "( case when ( description REGEXP '%s' ) then {$relevance_desc_equal} else 0 end )", $match_full_words);

                    if ( $this->search_rule === 'begins' ) {
                        $relevance_array[] = $wpdb->prepare( "( case when ( description LIKE %s OR description LIKE %s ) then {$relevance_desc} else 0 end )", $wpdb->esc_like( $search_term ) . '%', '% ' . $wpdb->esc_like( $search_term ) . '%' );
                        $relevance_array[] = $wpdb->prepare( "( case when ( description LIKE %s OR description LIKE %s  ) then {$relevance_desc} else 0 end )", $wpdb->esc_like( $this->search_string_unfiltered ) . '%', '% ' . $wpdb->esc_like( $this->search_string_unfiltered ) . '%' );
                    } else {
                        $relevance_array[] = $wpdb->prepare( "( case when ( description LIKE %s ) then {$relevance_desc} else 0 end )", $like );
                        $relevance_array[] = $wpdb->prepare( "( case when ( description LIKE %s ) then {$relevance_desc} else 0 end )", $like_unfiltered );
                    }

                }

            }

            /**
             * Filter array of relevance sql queries
             * @param array $relevance_array Array with relevance sql queries
             * @param array $taxonomy Taxonomy names array
             * @param array $this->search_terms Search terms array
             * @param array $this->data Search data
             * @since 3.31
             */
            $relevance_array = apply_filters( 'aws_tax_search_relevance_array', $relevance_array, $this->taxonomy, $this->search_terms, $this->data );

            return $relevance_array;

        }

        /**
         * Get sql query for multilingual results
         *
         * @return string SQL query
         */
        private function get_lang_query() {

            global $wpdb;

            $search_query = '';

            $lang = isset( $_REQUEST['lang'] ) ? sanitize_text_field( $_REQUEST['lang'] ) : '';

            if ( $lang ) {

                if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {

                    $tax_names_arr = array();
                    foreach ( $this->taxonomy as $key => $tax_name ) {
                        $tax_names_arr[] = 'tax_' . $tax_name;
                    }

                    $tax_names_arr = array_map( array( $this, 'prepare_tax_names' ), $tax_names_arr );
                    $tax_names = implode( ',', $tax_names_arr );

                    $search_query = " AND $wpdb->terms.term_id IN (
                        SELECT element_id
                        FROM {$wpdb->prefix}icl_translations
                        WHERE language_code = '{$lang}'
                        AND element_type IN ( {$tax_names} )
                    )";

                } else {

                    $terms = get_terms( array(
                        'taxonomy'   => $this->taxonomy,
                        'hide_empty' => false,
                        'fields'     => 'ids',
                        'lang'       => $lang
                    ) );
                    if ( $terms ) {
                        $search_query = sprintf( " AND ( " . $wpdb->terms . ".term_id IN ( %s ) )", implode( ',', $terms ) );
                    } else {
                        $search_query = " AND 1=2";
                    }

                }

            }

            return $search_query;

        }

        /**
         * Get taxonomies search array
         * @param array Search terms array
         * @return array Terms
         */
        private function get_search_array( $search_terms ) {

            global $wpdb;

            $search_array = array();

            foreach ( $search_terms as $search_term ) {

                $like = '%' . $wpdb->esc_like( $search_term ) . '%';
                $like_unfiltered = '%' . $wpdb->esc_like( $this->search_string_unfiltered ) . '%';

                if ( $this->search_exact === 'true' ) {
                    $search_array[] = $wpdb->prepare( '( name = "%s" )', $search_term );
                } elseif ( $this->search_rule === 'begins' ) {
                    $search_array[] = $wpdb->prepare( '( name LIKE %s OR name LIKE %s )', $wpdb->esc_like( $search_term ) . '%', '% ' . $wpdb->esc_like( $search_term ) . '%' );
                } else {
                    $search_array[] = $wpdb->prepare( '( name LIKE %s )', $like );
                }

                if ( $terms_desc_search = apply_filters( 'aws_search_terms_description', false ) ) {
                    if ( $this->search_exact === 'true' ) {
                        $search_array[] = $wpdb->prepare( '( description = "%s" )', $search_term );
                        $search_array[] = $wpdb->prepare( '( description = "%s" )', $this->search_string_unfiltered );
                    } elseif ( $this->search_rule === 'begins' ) {
                        $search_array[] = $wpdb->prepare( '( description LIKE %s OR description LIKE %s )', $wpdb->esc_like( $search_term ) . '%', '% ' . $wpdb->esc_like( $search_term ) . '%' );
                        $search_array[] = $wpdb->prepare( '( description LIKE %s OR description LIKE %s )', $wpdb->esc_like( $this->search_string_unfiltered ) . '%', '% ' . $wpdb->esc_like( $this->search_string_unfiltered ) . '%' );
                    } else {
                        $search_array[] = $wpdb->prepare( '( description LIKE %s )', $like );
                        $search_array[] = $wpdb->prepare( '( description LIKE %s )', $like_unfiltered );
                    }
                }

            }

            /**
             * Filter array of search sql queries
             * @param array $search_array Array with search sql queries
             * @param array $taxonomy Taxonomy names array
             * @param array $search_terms Search terms
             * @param array $this->data Search data
             * @since 3.31
             */
            $search_array = apply_filters( 'aws_tax_search_array', $search_array, $this->taxonomy, $search_terms, $this->data );

            return $search_array;

        }

        /**
         * Get taxonomies search array with special chars
         *
         * @return array Terms
         */
        private function get_unfiltered_search_array() {

            $no_normalized_str = $this->search_string_unfiltered;

            $no_normalized_str = AWS_Helpers::html2txt( $no_normalized_str );
            $no_normalized_str = trim( $no_normalized_str );

            if ( function_exists( 'mb_strtolower' ) ) {
                $no_normalized_str = mb_strtolower( $no_normalized_str );
            }

            $no_normalized_str = strtr( $no_normalized_str, AWS_Helpers::get_diacritic_chars() );

            $search_array_chars = array_unique( explode( ' ', $no_normalized_str ) );
            $search_array_chars = AWS_Helpers::filter_stopwords( $search_array_chars );

            if ( $search_array_chars && $this->search_logic !== 'and' ) {
                foreach ( $search_array_chars as $search_array_chars_index => $search_array_chars_term ) {
                    if ( array_search( $search_array_chars_term, $this->search_terms_normalized ) !== false ) {
                        unset( $search_array_chars[$search_array_chars_index] );
                    }
                }
            }

            if ( count( $search_array_chars ) === 1 && array_values($search_array_chars)[0] === $this->search_string_unfiltered ) {
                $search_array_chars = array();
            }

            return $search_array_chars;

        }

        /**
         * Generate SQL string for terms filtering
         * @return string Terms filters sql string
         */
        private function get_filters() {

            global $wpdb;

            $filters = '';
            $excludes_array = array();

            /**
             * Exclude certain taxonomies terms from search
             * @since 1.83
             * @param array $excludes_array Array of terms Ids
             */
            $excludes_array = apply_filters( 'aws_search_tax_exclude', $excludes_array, $this->taxonomy, $this->search_string );

            foreach( $this->taxonomy as $taxonomy_name ) {

                /**
                 * Exclude certain terms from search ( deprecated )
                 * @since 1.49
                 * @param array
                 */
                $exclude_terms = apply_filters( 'aws_terms_exclude_' . $taxonomy_name, array() );

                if ( $exclude_terms && is_array( $exclude_terms ) && ! empty( $exclude_terms ) ) {
                    $excludes_array = array_merge( $excludes_array, $exclude_terms );
                }

            }

            if ( $excludes_array && ! empty( $excludes_array ) ) {
                $filters .= sprintf( " AND ( " . $wpdb->terms . ".term_id NOT IN ( %s ) )", implode( ',', array_map( array( $this, 'prepare_tax_names' ), $excludes_array ) ) );
            }

            // Advanced filters
            $adv_filters_arr_obj = new AWS_Search_Filters( $this->filters, $this->form_id, $this->filter_id );
            $adv_filters_string = $adv_filters_arr_obj->filter();

            if ( $adv_filters_string ) {
                $filters .= sprintf( ' AND ( %s )', $adv_filters_string );
            }

            return $filters;

        }

        /*
         * Prepare taxonomy names for query
         * @param string $name Taxonomy name
         * @return string Prepared string
         */
        private function prepare_tax_names( $name ) {
            global $wpdb;
            return $wpdb->prepare('%s', $name);
        }

        /*
         * Add synonyms
         * @param array $search_terms Search term
         * @return array Search term with synonyms
         */
        private function synonyms( $search_terms ) {

            if ( $search_terms && ! empty( $search_terms ) ) {

                $new_search_terms = array();
                foreach( $search_terms as $search_term ) {
                    $current_search_term_arr = array( $search_term => 1 );
                    $new_search_terms[$search_term] = array_keys( AWS_Helpers::get_synonyms( $current_search_term_arr, true ) );
                }

                return $new_search_terms;

            }

            return $search_terms;

        }

    }

endif;
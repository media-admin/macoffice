<?php

if( ! class_exists('PMWI_Updater') ) {

    class PMWI_Updater {
        private $api_url  = '';
        private $api_data = array();
        private $name     = '';
        private $slug     = '';
        private $_plugin_file = '';
        private $did_check = false;
        private $version;

        /**
         * Class constructor.
         *
         * @uses plugin_basename()
         * @uses hook()
         *
         * @param string $_api_url The URL pointing to the custom API endpoint.
         * @param string $_plugin_file Path to the plugin file.
         * @param array $_api_data Optional data to send with API calls.
         * @return void
         */
        function __construct( $_api_url, $_plugin_file, $_api_data = null ) {
            $this->api_url  = trailingslashit( $_api_url );
            $this->api_data = urlencode_deep( $_api_data );
            $this->name     = plugin_basename( $_plugin_file );
            $this->slug     = basename( $_plugin_file, '.php');
            $this->version  = $_api_data['version'];

            // Set up hooks.
            $this->init();
            add_action( 'admin_init', array( $this, 'show_changelog' ) );
        }

        /**
         * Set up WordPress filters to hook into WP's update process.
         *
         * @uses add_filter()
         *
         * @return void
         */
        public function init() {

            add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ), 21 );
            add_filter( 'plugins_api', array( $this, 'plugins_api_filter' ), 10, 3 );

            add_action( 'after_plugin_row_' . $this->name, array( $this, 'show_update_notification' ), 10, 2 );
            add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

			add_action( 'in_plugin_update_message-'.$this->name, [$this, 'custom_update_note'], 10, 2);
        }

		public function custom_update_note( $data, $response ){

			// Only show a custom note if one was included in the update data.
			if ( is_object($response) && !empty($response->custom_update_note) && !empty($response->update_note_version)){
				// Ensure that this version is the same or older than the note's target version.
				if( version_compare($this->version, $response->update_note_version, '<=')) {
					echo wp_kses( $response->custom_update_note, 'post' );
				}
			}
		}

        /**
         * Show row meta on the plugin screen.
         *
         * @param	mixed $links Plugin Row Meta
         * @param	mixed $file  Plugin Base file
         * @return	array
         */
        public function plugin_row_meta( $links, $file ) {
            if ( $file == $this->name ) {
                $row_meta = array(
                    'changelog'    => '<a href="' . admin_url( 'plugin-install.php?tab=plugin-information&plugin=wpai-woocommerce-add-on&section=changelog&TB_iframe=true&width=600&height=800' ) . '" class="thickbox open-plugin-details-modal" title="' . esc_attr( __( 'View WP All Import - WooCommerce Add-On Pro Changelog', 'wpai_woocommerce_addon_plugin' ) ) . '">' . __( 'Changelog', 'wpai_woocommerce_addon_plugin' ) . '</a>',
                );

                return array_merge( $links, $row_meta );
            }

            return (array) $links;
        }

        /**
         * Check for Updates at the defined API endpoint and modify the update array.
         *
         * This function dives into the update API just when WordPress creates its update array,
         * then adds a custom API call and injects the custom plugin data retrieved from the API.
         * It is reassembled from parts of the native WordPress plugin update code.
         * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
         *
         * @uses api_request()
         *
         * @param array   $_transient_data Update array build by WordPress.
         * @return array Modified update array with custom plugin data.
         */
        function check_update( $_transient_data ) {

            global $pagenow;
            global $wpdb;

            if( ! is_object( $_transient_data ) ) {
                $_transient_data = new stdClass;
            }

            if( 'plugins.php' == $pagenow && is_multisite() ) {
                return $_transient_data;
            }

            if( empty( $_transient_data ) ) return $_transient_data;

            if ( empty( $_transient_data->response ) || empty( $_transient_data->response[ $this->name ] ) ) {

                $cache_key    = md5( 'edd_plugin_' .sanitize_key( $this->name ) . '_version_info' );
                $version_info = get_transient( $cache_key );

                if( false === $version_info ) {

                    $timeout = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $this->slug . '_timeout_' . $cache_key ) );

                    // Has to be get_row instead of get_var because of funkiness with 0, false, null values
                    if ( is_object( $timeout ) ) {
                        $value = $timeout->option_value;
                        // cache time is not expired
                        if ( $value >= strtotime("now") )
                        {
                            $cache_value = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $this->slug . '_' . $cache_key ) );        
                            if ( is_object( $cache_value ) and ! empty($cache_value->option_value)) {
                                $version_info = maybe_unserialize($cache_value->option_value);
                            }
                        }
                    }

                    if( false === $version_info ) {
                        $version_info = $this->api_request( 'check_update', array( 'slug' => $this->slug ) );
                        $transient_result = set_transient( $cache_key, $version_info, 3600 * 24 );

                        $wpdb->query( $wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name = %s", $this->slug . '_' . $cache_key) );
                        $wpdb->query( $wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name = %s", $this->slug . '_timeout_' . $cache_key) );
                                         
                        $wpdb->query( $wpdb->prepare("INSERT INTO $wpdb->options ( option_value, option_name, autoload ) VALUES ( %s, %s, 'no' )", maybe_serialize( $version_info ), $this->slug . '_' . $cache_key) );
                        $wpdb->query( $wpdb->prepare("INSERT INTO $wpdb->options ( option_value, option_name ) VALUES ( %s, %s )", strtotime("+1 hour"), $this->slug . '_timeout_' . $cache_key) );
                        
                    }

                }

                if( ! is_object( $version_info ) ) {
                    return $_transient_data;
                }

                if ( false !== $version_info && is_object( $version_info ) && isset( $version_info->new_version ) ) {

                    $this->did_check = true;

                    if( version_compare( $this->version, $version_info->new_version, '<' ) ) {

                        $_transient_data->response[ $this->name ] = $version_info;

                    }

                    $_transient_data->last_checked = time();
                    $_transient_data->checked[ $this->name ] = $this->version;

                }

            }

            return $_transient_data;
        }

        /**
         * show update nofication row -- needed for multisite subsites, because WP won't tell you otherwise!
         *
         * @param string  $file
         * @param array   $plugin
         */
        public function show_update_notification( $file, $plugin ) {

            if( ! current_user_can( 'update_plugins' ) ) {
                return;
            }

            if( ! is_multisite() ) {
                return;
            }

            if ( $this->name != $file ) {
                return;
            }

            // Remove our filter on the site transient
            remove_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ), 21 );

            $update_cache = get_site_transient( 'update_plugins' );

            if ( ! is_object( $update_cache ) || empty( $update_cache->response ) || empty( $update_cache->response[ $this->name ] ) ) {

                global $wpdb;

                $cache_key    = md5( 'edd_plugin_' .sanitize_key( $this->name ) . '_version_info' );
                $version_info = get_transient( $cache_key );

                if( false === $version_info ) {

                    $timeout = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $this->slug . '_timeout_' . $cache_key ) );

                    // Has to be get_row instead of get_var because of funkiness with 0, false, null values
                    if ( is_object( $timeout ) ) {
                        $value = $timeout->option_value;
                        // cache time is not expired
                        if ( $value >= strtotime("now") )
                        {
                            $cache_value = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $this->slug . '_' . $cache_key ) );        
                            if ( is_object( $cache_value ) and ! empty($cache_value->option_value)) {
                                $version_info = maybe_unserialize($cache_value->option_value);
                            }
                        }
                    }

                    if( false === $version_info ) {

                        $version_info = $this->api_request( 'plugin_latest_version', array( 'slug' => $this->slug ) );

                        $transient_result = set_transient( $cache_key, $version_info, 3600 * 24 );

                        $wpdb->query( $wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name = %s", $this->slug . '_' . $cache_key) );
                        $wpdb->query( $wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name = %s", $this->slug . '_timeout_' . $cache_key) );
                                         
                        $wpdb->query( $wpdb->prepare("INSERT INTO $wpdb->options ( option_value, option_name, autoload ) VALUES ( %s, %s, 'no' )", maybe_serialize( $version_info ), $this->slug . '_' . $cache_key) );
                        $wpdb->query( $wpdb->prepare("INSERT INTO $wpdb->options ( option_value, option_name ) VALUES ( %s, %s )", strtotime("+1 hour"), $this->slug . '_timeout_' . $cache_key) );                       
                        
                    }
                }

                if( ! is_object( $version_info ) ) {
                    return;
                }

	            if(!is_object($update_cache)) {
		            $update_cache = new stdClass();
	            }

                if( version_compare( $this->version, $version_info->new_version, '<' ) ) {

                    $update_cache->response[ $this->name ] = $version_info;

                }

                $update_cache->last_checked = time();
                $update_cache->checked[ $this->name ] = $this->version;

                set_site_transient( 'update_plugins', $update_cache );

            } else {

                $version_info = $update_cache->response[ $this->name ];

            }

            // Restore our filter
            add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ), 21 );

            if ( ! empty( $update_cache->response[ $this->name ] ) && version_compare( $this->version, $version_info->new_version, '<' ) ) {

                // build a plugin list row, with update notification
                $wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
                echo '<tr class="plugin-update-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange"><div class="update-message">';

                $changelog_link = self_admin_url( 'index.php?edd_sl_action=view_plugin_changelog&plugin=' . $this->name . '&slug=' . $this->slug . '&TB_iframe=true&width=772&height=911' );

                if ( empty( $version_info->download_link ) ) {
                    printf(
                        __( 'There is a new version of %1$s available. <a target="_blank" class="thickbox" href="%2$s">View version %3$s details</a>.', 'edd' ),
                        esc_html( $version_info->name ),
                        esc_url( $changelog_link ),
                        esc_html( $version_info->new_version )
                    );
                } else {
                    printf(
                        __( 'There is a new version of %1$s available. <a target="_blank" class="thickbox" href="%2$s">View version %3$s details</a> or <a href="%4$s">update now</a>.', 'edd' ),
                        esc_html( $version_info->name ),
                        esc_url( $changelog_link ),
                        esc_html( $version_info->new_version ),
                        esc_url( wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $this->name, 'upgrade-plugin_' . $this->name ) )
                    );
                }

                echo '</div></td></tr>';
            }
        }

        /**
         * Updates information on the "View version x.x details" page with custom data.
         *
         * @uses api_request()
         *
         * @param mixed   $_data
         * @param string  $_action
         * @param object  $_args
         * @return object $_data
         */
        function plugins_api_filter( $_data, $_action = '', $_args = null ) {


            if ( $_action != 'plugin_information' ) {

                return $_data;

            }

            if ( ! isset( $_args->slug ) || ( $_args->slug != $this->slug ) ) {

                return $_data;

            }

            global $wpdb;

            $cache_key    = md5( 'edd_plugin_' .sanitize_key( $this->name ) . '_version_info' );
            $_data = get_transient( $cache_key );

            if( false === $_data ) {

                $timeout = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $this->slug . '_timeout_' . $cache_key ) );

                // Has to be get_row instead of get_var because of funkiness with 0, false, null values
                if ( is_object( $timeout ) ) {
                    $value = $timeout->option_value;
                    // cache time is not expired
                    if ( $value >= strtotime("now") )
                    {
                        $cache_value = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $this->slug . '_' . $cache_key ) );        
                        if ( is_object( $cache_value ) and ! empty($cache_value->option_value)) {
                            $_data = maybe_unserialize($cache_value->option_value);
                        }
                    }
                }

                if( false === $_data ) {
                    $to_send = array(
                        'slug'   => $this->slug,
                        'is_ssl' => is_ssl(),
                        'fields' => array(
                            'banners' => false, // These will be supported soon hopefully
                            'reviews' => false
                        )
                    );

                    $_data = $this->api_request( 'plugin_information', $to_send );

                    if ( false !== $_data ) {
                        
                        $transient_result = set_transient( $cache_key, $_data, 3600 * 24 );

                        $wpdb->query( $wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name = %s", $this->slug . '_' . $cache_key) );
                        $wpdb->query( $wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name = %s", $this->slug . '_timeout_' . $cache_key) );
                                         
                        $wpdb->query( $wpdb->prepare("INSERT INTO $wpdb->options ( option_value, option_name, autoload ) VALUES ( %s, %s, 'no' )", maybe_serialize( $_data ), $this->slug . '_' . $cache_key) );
                        $wpdb->query( $wpdb->prepare("INSERT INTO $wpdb->options ( option_value, option_name ) VALUES ( %s, %s )", strtotime("+1 hour"), $this->slug . '_timeout_' . $cache_key) );
                                                                      
                    }   
                }                         
            }

            return $_data;
        }


        /**
         * Disable SSL verification in order to prevent download update failures
         *
         * @param array   $args
         * @param string  $url
         *
         * @return array|object
         */
        function http_request_args( $args, $url ) {
            // If it is an https request and we are performing a package download, disable ssl verification
            if ( strpos( $url, 'https://' ) !== false && strpos( $url, 'edd_action=package_download' ) ) {
                $args['sslverify'] = true;
            }
            return $args;
        }

        /**
         * Calls the API and, if successfull, returns the object delivered by the API.
         *
         * @uses get_bloginfo()
         * @uses wp_remote_post()
         * @uses is_wp_error()
         *
         * @param string  $_action The requested action.
         * @param array   $_data   Parameters for the API action.
         * @return false||object
         */
        private function api_request( $_action, $_data ) {

            global $wp_version;

            $data = array_merge( $this->api_data, $_data );        

            if ( $data['slug'] != $this->slug )
                return false;

            /*if ( empty( $data['license'] ) )
                return;*/

            if( $this->api_url == home_url() ) {
                return false; // Don't allow a plugin to ping itself
            }                                

            $api_params = array(
                'edd_action' => 'get_version',
                'license'    => false,
                'item_name'  => isset( $data['item_name'] ) ? $data['item_name'] : false,
                'item_id'    => isset( $data['item_id'] ) ? $data['item_id'] : false,
                'slug'       => $data['slug'],
                'author'     => $data['author'],
                'url'        => home_url(),
                'version'    => $this->version
            );            

            // if ( defined('WP_DEBUG') and WP_DEBUG )
            // {
            //     $uploads = wp_upload_dir();
            //     file_put_contents($uploads['basedir'] . "/log.txt", date("d-m-Y H:i:s") . ' - ' .json_encode($api_params) . "\n", FILE_APPEND);
            // }

	        // Send request based on provided API URL.
	        if( strpos($this->api_url, 'update.') !== false){
		        $request = wp_remote_get( esc_url_raw(add_query_arg($api_params, $this->api_url.'check_version/?')), array( 'timeout' => 15, 'sslverify' => true ) );
	        }else{
		        $request = wp_remote_post( $this->api_url, array( 'timeout' => 15, 'sslverify' => true, 'body' => $api_params ) );
	        }

            if ( ! is_wp_error( $request ) ) {
                $request = json_decode( wp_remote_retrieve_body( $request ) );
            }

            if ( $request && isset( $request->banners ) ) {
                $request->banners = maybe_unserialize( $request->banners );
            }

            if ( $request && isset( $request->sections ) ) {
                $request->sections = maybe_unserialize( $request->sections );
            } else {
                $request = false;
            }

            return $request;
        }

        public function show_changelog() {


            if( empty( $_REQUEST['edd_sl_action'] ) || 'view_plugin_changelog' != $_REQUEST['edd_sl_action'] ) {
                return;
            }

            if( empty( $_REQUEST['plugin'] ) ) {
                return;
            }

            if( empty( $_REQUEST['slug'] ) ) {
                return;
            }

            if( ! current_user_can( 'update_plugins' ) ) {
                wp_die( __( 'You do not have permission to install plugin updates', 'edd' ), __( 'Error', 'edd' ), array( 'response' => 403 ) );
            }

            $response = $this->api_request( 'show_changelog', array( 'slug' => $_REQUEST['slug'] ) );

            if( $response && isset( $response->sections['changelog'] ) ) {
                echo '<div style="background:#fff;padding:10px;">' . $response->sections['changelog'] . '</div>';
            }


            exit;
        }
    }

}

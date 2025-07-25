<?php
/**
 * Uninstall plugin
 * Deletes all the plugin data
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;


global $wpdb;

if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( ! function_exists( 'aws_pro_is_plugin_installed' ) ) {

    function aws_pro_is_plugin_installed( $plugin ) {
        $plugins_array = get_plugins();
        if ( is_multisite() ) {
            $plugins_array = get_mu_plugins();
        }
        return isset( $plugins_array[$plugin] );
    }

}

delete_option( 'aws_pro_settings' );
delete_option( 'aws_pro_common_opts' );
delete_option( 'aws_pro_plugin_ver' );
delete_option( 'aws_pro_reindex_version' );
delete_option( 'aws_pro_index_table_version' );
delete_option( 'aws_cron_job' );
delete_option( 'aws_hide_welcome_notice' );
delete_option( 'aws_pro_autoupdates' );
delete_option( 'aws_pro_stopwords' );
delete_option( 'aws_pro_synonyms' );
delete_option( 'aws_pro_seamless' );
delete_option( 'aws_instances' );
delete_option( 'aws_main_instance' );

if ( ! aws_pro_is_plugin_installed( 'advanced-woo-search/advanced-woo-search.php' ) ) {
    $wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "aws_index" );
    $wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "aws_cache" );
}
<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7c535432cb2318823e1dc9ec7826d778
{
    public static $files = array (
        '3773ef3f09c37da5478d578e32b03a4b' => __DIR__ . '/../..' . '/jetpack_vendor/automattic/jetpack-assets/actions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'Q' => 
        array (
            'QuadLayers\\WP_Plugin_Table_Links\\' => 33,
            'QuadLayers\\WP_Plugin_Suggestions\\' => 33,
            'QuadLayers\\WP_Notice_Plugin_Required\\' => 37,
            'QuadLayers\\WP_Notice_Plugin_Promote\\' => 36,
            'QuadLayers\\WP_Dashboard_Widget_News\\' => 36,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'QuadLayers\\WP_Plugin_Table_Links\\' => 
        array (
            0 => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-plugin-table-links/src',
        ),
        'QuadLayers\\WP_Plugin_Suggestions\\' => 
        array (
            0 => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-plugin-suggestions/src',
        ),
        'QuadLayers\\WP_Notice_Plugin_Required\\' => 
        array (
            0 => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-notice-plugin-required/src',
        ),
        'QuadLayers\\WP_Notice_Plugin_Promote\\' => 
        array (
            0 => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-notice-plugin-promote/src',
        ),
        'QuadLayers\\WP_Dashboard_Widget_News\\' => 
        array (
            0 => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-dashboard-widget-news/src',
        ),
    );

    public static $classMap = array (
        'Automattic\\Jetpack\\Assets' => __DIR__ . '/../..' . '/jetpack_vendor/automattic/jetpack-assets/src/class-assets.php',
        'Automattic\\Jetpack\\Assets\\Semver' => __DIR__ . '/../..' . '/jetpack_vendor/automattic/jetpack-assets/src/class-semver.php',
        'Automattic\\Jetpack\\Composer\\Manager' => __DIR__ . '/..' . '/automattic/jetpack-composer-plugin/src/class-manager.php',
        'Automattic\\Jetpack\\Composer\\Plugin' => __DIR__ . '/..' . '/automattic/jetpack-composer-plugin/src/class-plugin.php',
        'Automattic\\Jetpack\\Constants' => __DIR__ . '/../..' . '/jetpack_vendor/automattic/jetpack-constants/src/class-constants.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'QuadLayers\\PWB\\Admin\\Admin_Tab' => __DIR__ . '/../..' . '/lib/admin/class-admin-tab.php',
        'QuadLayers\\PWB\\Admin\\Brands_Custom_Fields' => __DIR__ . '/../..' . '/lib/admin/class-brands-custom-fields.php',
        'QuadLayers\\PWB\\Admin\\Brands_Exporter' => __DIR__ . '/../..' . '/lib/admin/class-brands-exporter.php',
        'QuadLayers\\PWB\\Admin\\Coupon' => __DIR__ . '/../..' . '/lib/admin/class-coupon.php',
        'QuadLayers\\PWB\\Admin\\Dummy_Data' => __DIR__ . '/../..' . '/lib/admin/class-dummy-data.php',
        'QuadLayers\\PWB\\Admin\\Edit_Brands_Page' => __DIR__ . '/../..' . '/lib/admin/class-edit-brands-page.php',
        'QuadLayers\\PWB\\Admin\\Exporter_Support' => __DIR__ . '/../..' . '/lib/admin/class-exporter-support.php',
        'QuadLayers\\PWB\\Admin\\Importer_Support' => __DIR__ . '/../..' . '/lib/admin/class-importer-support.php',
        'QuadLayers\\PWB\\Admin\\Migrate' => __DIR__ . '/../..' . '/lib/admin/class-migrate.php',
        'QuadLayers\\PWB\\Admin\\System_Status' => __DIR__ . '/../..' . '/lib/admin/class-system-status.php',
        'QuadLayers\\PWB\\Plugin' => __DIR__ . '/../..' . '/lib/class-plugin.php',
        'QuadLayers\\PWB\\Product' => __DIR__ . '/../..' . '/lib/class-product.php',
        'QuadLayers\\PWB\\Rest_Api' => __DIR__ . '/../..' . '/lib/class-rest-api.php',
        'QuadLayers\\PWB\\Shortcodes\\AZ_Listing' => __DIR__ . '/../..' . '/lib/shortcodes/class-az-listing.php',
        'QuadLayers\\PWB\\Shortcodes\\All_Brands' => __DIR__ . '/../..' . '/lib/shortcodes/class-all-brands.php',
        'QuadLayers\\PWB\\Shortcodes\\Brand' => __DIR__ . '/../..' . '/lib/shortcodes/class-brand.php',
        'QuadLayers\\PWB\\Shortcodes\\Carousel' => __DIR__ . '/../..' . '/lib/shortcodes/class-carousel.php',
        'QuadLayers\\PWB\\Shortcodes\\Product_Carousel' => __DIR__ . '/../..' . '/lib/shortcodes/class-product-carousel.php',
        'QuadLayers\\PWB\\Term' => __DIR__ . '/../..' . '/lib/class-term.php',
        'QuadLayers\\PWB\\Widgets\\Brands_Dropdown' => __DIR__ . '/../..' . '/lib/widgets/class-brands-dropdown.php',
        'QuadLayers\\PWB\\Widgets\\Brands_List' => __DIR__ . '/../..' . '/lib/widgets/class-brands-list.php',
        'QuadLayers\\PWB\\Widgets\\Filter_By_Brand' => __DIR__ . '/../..' . '/lib/widgets/class-filter-by-brand.php',
        'QuadLayers\\PWB\\WooCommerce' => __DIR__ . '/../..' . '/lib/class-woocommerce.php',
        'QuadLayers\\WP_Dashboard_Widget_News\\Load' => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-dashboard-widget-news/src/Load.php',
        'QuadLayers\\WP_Notice_Plugin_Promote\\Load' => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-notice-plugin-promote/src/Load.php',
        'QuadLayers\\WP_Notice_Plugin_Promote\\PluginByFile' => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-notice-plugin-promote/src/PluginByFile.php',
        'QuadLayers\\WP_Notice_Plugin_Promote\\PluginBySlug' => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-notice-plugin-promote/src/PluginBySlug.php',
        'QuadLayers\\WP_Notice_Plugin_Promote\\PluginBySlugV2' => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-notice-plugin-promote/src/PluginBySlugV2.php',
        'QuadLayers\\WP_Notice_Plugin_Promote\\Traits\\PluginActions' => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-notice-plugin-promote/src/Traits/PluginActions.php',
        'QuadLayers\\WP_Notice_Plugin_Promote\\Traits\\PluginActionsLinks' => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-notice-plugin-promote/src/Traits/PluginActionsLinks.php',
        'QuadLayers\\WP_Notice_Plugin_Promote\\Traits\\PluginDataByFile' => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-notice-plugin-promote/src/Traits/PluginDataByFile.php',
        'QuadLayers\\WP_Notice_Plugin_Required\\Load' => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-notice-plugin-required/src/Load.php',
        'QuadLayers\\WP_Notice_Plugin_Required\\Plugin' => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-notice-plugin-required/src/Plugin.php',
        'QuadLayers\\WP_Plugin_Suggestions\\Load' => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-plugin-suggestions/src/Load.php',
        'QuadLayers\\WP_Plugin_Suggestions\\Page' => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-plugin-suggestions/src/Page.php',
        'QuadLayers\\WP_Plugin_Suggestions\\Table' => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-plugin-suggestions/src/Table.php',
        'QuadLayers\\WP_Plugin_Table_Links\\Load' => __DIR__ . '/../..' . '/jetpack_vendor/quadlayers/wp-plugin-table-links/src/Load.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7c535432cb2318823e1dc9ec7826d778::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7c535432cb2318823e1dc9ec7826d778::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7c535432cb2318823e1dc9ec7826d778::$classMap;

        }, null, ClassLoader::class);
    }
}

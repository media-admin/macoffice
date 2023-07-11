<?php

namespace MatthiasWeb\RealMediaLibrary\Vendor\MatthiasWeb\Utils;

use MO;
use Translation_Entry;
use WP_Textdomain_Registry;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * Base i18n management for backend and frontend.
 */
trait Localization
{
    /**
     * Get the directory where the languages folder exists.
     *
     * The returned string array should look like this:
     * [0] = Full path to the "languages" folder
     * [1] = Used textdomain
     * [2]? = Use different prefix domain in filename
     *
     * @param string $type
     * @return string[]
     */
    protected abstract function getPackageInfo($type);
    /**
     * Clear the MO cache directory for a given plugin.
     *
     * @param string $slug
     */
    public function clearMoCacheDir($slug)
    {
        $cacheDir = $this->getMoCacheDir($slug);
        if ($cacheDir !== \false) {
            // @codeCoverageIgnoreStart
            if (!\defined('PHPUNIT_FILE')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }
            // @codeCoverageIgnoreEnd
            $files = \list_files($cacheDir);
            if (\is_array($files)) {
                foreach ($files as $file) {
                    @\unlink($file);
                }
            }
        }
    }
    /**
     * Obtain language key from a file name.
     *
     * @param string $file
     */
    public function getLanguageFromFile($file)
    {
        $availableLanguages = \get_available_languages();
        $availableLanguages[] = 'en_US';
        \preg_match_all('/-(' . \join('|', $availableLanguages) . ')-/m', \basename($file), $matches, \PREG_SET_ORDER, 0);
        if (\count($matches) === 0) {
            return \false;
        }
        return $matches[0][1];
    }
    /**
     * Cache the results of the `MO` parser in a file in `wp-content/languages/mo-cache` because it is extremely
     * slow on some WordPress installations.
     *
     * @param boolean $plugin_override
     * @param string $domain
     * @param string $mofile
     * @see https://core.trac.wordpress.org/ticket/32052
     * @see https://core.trac.wordpress.org/ticket/17268
     * @see https://app.clickup.com/t/861m3qqb7
     */
    public function override_load_textdomain($plugin_override, $domain, $mofile)
    {
        /**
         * Var.
         *
         * @var WP_Textdomain_Registry $wp_textdomain_registry Check if null as only available since WP 6.1: https://github.com/WordPress/WordPress/commit/bb9f57429a8d3d8b08935f26dc547dcf947b0704
         */
        global $l10n, $l10n_unloaded, $wp_textdomain_registry;
        // Do not override other mechanism
        if ($plugin_override === \false) {
            // Do not run this caching mechanism for other plugins, only ours
            list($packagePath, $packageDomain) = $this->getPackageInfo(Constants::LOCALIZATION_BACKEND);
            if ($domain !== $packageDomain) {
                return $plugin_override;
            }
            // Override mofile as core does
            //$mofile = apply_filters('load_textdomain_mofile', $mofile, $domain);
            // Extract locale from mofile
            $mofileLocale = \explode('-', \basename($mofile));
            $mofileLocale = \explode('.', $mofileLocale[\count($mofileLocale) - 1], 2)[0];
            // Never load `.mo` files from `wp-content/plugins/languages` as we do manage all our translations ourself.
            $avoidPath = \constant('WP_LANG_DIR') . '/plugins/';
            // Path to the language file within our plugin
            $packageFilePath = \trailingslashit($packagePath) . \basename($mofile);
            /**
             * By default, we do not allow to retrieve translations from the official WordPress translation hub as this could
             * lead to issues with freemium software (e.g. PRO-version strings are not translated). All the translation is
             * managed via translate.devowl.io.
             *
             * @hook DevOwl/Utils/Localization/AllowExternalMofile
             * @param {boolean} $allow
             * @param {string} $mofile
             * @param {string} $mofileLocale
             * @since 1.12.29
             */
            if (\substr($mofile, 0, \strlen($avoidPath)) === $avoidPath && \is_readable($packageFilePath) && !\apply_filters('DevOwl/Utils/Localization/AllowExternalMofile', \false, $mofile, $mofileLocale)) {
                // Always use our internal translation instead of the `wp-content/languages` folder
                $mofile = $packageFilePath;
            }
            if (!\is_readable($mofile)) {
                return $plugin_override;
            }
            // Detect plugin slug
            $pluginDir = \constant('WP_PLUGIN_DIR') . \DIRECTORY_SEPARATOR;
            if (\strpos($mofile, $pluginDir) !== 0) {
                return $plugin_override;
            }
            $slug = \strrev(\basename(\strrev(\substr($mofile, \strlen($pluginDir)))));
            // Check if folder is writable for this plugin
            $destinationFolder = $this->getMoCacheDir($slug);
            if ($destinationFolder === \false) {
                return $plugin_override;
            }
            // Read from existing cache
            $moFileTime = \filemtime($mofile);
            $cachedMoJsonPath = \trailingslashit($destinationFolder) . \md5($mofile . $moFileTime) . '.json';
            $data = \false;
            if (\is_readable($cachedMoJsonPath)) {
                $data = \json_decode(\file_get_contents($cachedMoJsonPath), ARRAY_A);
            }
            // First of all, we need to get the parent `$locale` variable so we can "recall" it
            // See https://github.com/WordPress/wordpress-develop/blob/28f10e4af559c9b4dbbd1768feff0bae575d5e78/src/wp-includes/l10n.php#L718-L733
            // phpcs:disable
            $backtrace = @\debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, 5);
            // phpcs:enable
            $locale = null;
            foreach ($backtrace as $bt) {
                if (isset($bt['function'], $bt['args']) && $bt['function'] === 'load_textdomain' && \count($bt['args']) > 2 && isset($bt['args'][2])) {
                    $locale = $bt['args'][2];
                    break;
                }
            }
            if (!$locale) {
                $locale = \determine_locale();
            }
            // On some environments, `trailingslashit` function is not loaded correctly. Let's require it.
            // See also: https://stackoverflow.com/q/74653740/5506547
            if (!\function_exists('trailingslashit')) {
                require_once ABSPATH . WPINC . '/formatting.php';
            }
            $mo = new MO();
            if (!$data || !isset($data['mtime']) || $moFileTime > $data['mtime']) {
                // Parse MO file, prepare cache and write it
                if (!$mo->import_from_file($mofile)) {
                    if ($wp_textdomain_registry) {
                        $wp_textdomain_registry->set($domain, $locale, \false);
                    }
                    return \false;
                }
                $data = ['mtime' => $moFileTime, 'file' => $mofile, 'entries' => $mo->entries, 'headers' => $mo->headers];
                \file_put_contents($cachedMoJsonPath, \json_encode($data));
            } else {
                // Load from cache, and keep `Translation_Entry` instances intact
                // See https://github.com/WordPress/wordpress-develop/blob/28f10e4af559c9b4dbbd1768feff0bae575d5e78/src/wp-includes/pomo/entry.php
                $mo->entries = [];
                $mo->headers = $data['headers'];
                foreach ($data['entries'] as $key => $entry) {
                    $mo->entries[$key] = new Translation_Entry($entry);
                }
            }
            if (isset($l10n[$domain])) {
                $mo->merge_with($l10n[$domain]);
            }
            unset($l10n_unloaded[$domain]);
            $l10n[$domain] =& $mo;
            if ($wp_textdomain_registry) {
                $wp_textdomain_registry->set($domain, $locale, \dirname($mofile));
            }
            return \true;
        }
        return $plugin_override;
    }
    /**
     * Add filters to WordPress runtime.
     */
    public function hooks()
    {
        \add_filter('override_load_textdomain', [$this, 'override_load_textdomain'], 1, 3);
    }
    /**
     * Get the languages which are available in the POT file. Why multiple? Imagine you want to
     * use the pot file for `en_US` and `en_GB`. This can be useful for the `@devowl-wp/multilingual`
     * package, especially the `TemporaryTextDomain` feature.
     */
    public function getPotLanguages()
    {
        return ['en_US', 'en_GB', 'en_CA', 'en_NZ', 'en_AU', 'en'];
    }
    /**
     * Get the cache directory for cached MO files after parsing. It also checks, if the directory
     * is writable and create the path for a given plugin slug.
     *
     * This function is expensive, so we cached it to `$GLOBALS`.
     *
     * @param string $slug
     */
    public function getMoCacheDir($slug)
    {
        global $devowl_mo_cache_dir;
        $devowl_mo_cache_dir = $devowl_mo_cache_dir ?? [];
        if (!isset($devowl_mo_cache_dir[$slug])) {
            $path = \defined('WP_LANG_DIR') ? \constant('WP_LANG_DIR') : '';
            if (empty($path)) {
                return \false;
            }
            $path = \trailingslashit($path) . 'mo-cache/' . $slug;
            if (\wp_mkdir_p($path) && \wp_is_writable($path)) {
                $devowl_mo_cache_dir[$slug] = $path;
            } else {
                $devowl_mo_cache_dir[$slug] = '';
            }
        }
        return empty($devowl_mo_cache_dir[$slug]) ? \false : $devowl_mo_cache_dir[$slug];
    }
}

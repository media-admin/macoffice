<?php

namespace MatthiasWeb\RealMediaLibrary\Vendor\MatthiasWeb\Utils;

// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Utility helpers.
 */
class Utils
{
    /**
     * Get the nonce salt of the current WordPress installation. This one can be used to hash data unique to the WordPress instance.
     *
     * @return string
     */
    public static function getNonceSalt()
    {
        $salt = '';
        /**
         * In some cases, hosting providers generate the salts with lower case constant names.
         * I do not know if this works correctly, as PHP's method to obtain a constant is case-
         * sensitive and e.g. `wp_salt()` also expects an uppercase constant name.
         *
         * If a lowercase constant exists, use it, instead try the uppercase one and throw an error
         * if needed (PHP > 8).
         */
        foreach (['nonce_salt', 'NONCE_SALT'] as $constant) {
            if (\defined($constant)) {
                $salt = \constant($constant);
                break;
            }
        }
        /**
         * For older WordPress versions, WordPress did not have salts (https://api.wordpress.org/secret-key/1.1/).
         * They came with newer version of WordPress (https://api.wordpress.org/secret-key/1.1/salt/). But fortunately,
         * `wp_salt` generates a salt in database if not yet given:
         *
         * https://github.com/WordPress/WordPress/blob/1553e3fa008d331adab1c26d221035fbe1876d1f/wp-includes/pluggable.php#L2455-L2459
         */
        if (empty($salt)) {
            \wp_salt('nonce');
            // Call once to ensure `nonce_salt` is in database
            $salt = \get_site_option('nonce_salt', '');
        }
        return $salt;
    }
}

<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit7685ae3b97fe97950d32e3464b7d1d56
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit7685ae3b97fe97950d32e3464b7d1d56', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit7685ae3b97fe97950d32e3464b7d1d56', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit7685ae3b97fe97950d32e3464b7d1d56::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}

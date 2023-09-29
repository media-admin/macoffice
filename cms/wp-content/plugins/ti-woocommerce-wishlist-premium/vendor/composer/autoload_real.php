<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitd5e591c71f8e947f1cf63ac4166f3809
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

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitd5e591c71f8e947f1cf63ac4166f3809', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitd5e591c71f8e947f1cf63ac4166f3809', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitd5e591c71f8e947f1cf63ac4166f3809::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}

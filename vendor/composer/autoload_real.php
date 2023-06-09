<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitc4183d9c5d5fadcb396f40a7d16f6cff
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

        spl_autoload_register(array('ComposerAutoloaderInitc4183d9c5d5fadcb396f40a7d16f6cff', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitc4183d9c5d5fadcb396f40a7d16f6cff', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitc4183d9c5d5fadcb396f40a7d16f6cff::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}

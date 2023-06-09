<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc4183d9c5d5fadcb396f40a7d16f6cff
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc4183d9c5d5fadcb396f40a7d16f6cff::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc4183d9c5d5fadcb396f40a7d16f6cff::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc4183d9c5d5fadcb396f40a7d16f6cff::$classMap;

        }, null, ClassLoader::class);
    }
}

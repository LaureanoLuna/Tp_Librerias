<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit288d72ebde6c5d22a93734ee9e8d2ff9
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Delight\\Http\\' => 13,
            'Delight\\Db\\' => 11,
            'Delight\\Cookie\\' => 15,
            'Delight\\Base64\\' => 15,
            'Delight\\Auth\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Delight\\Http\\' => 
        array (
            0 => __DIR__ . '/..' . '/delight-im/http/src',
        ),
        'Delight\\Db\\' => 
        array (
            0 => __DIR__ . '/..' . '/delight-im/db/src',
        ),
        'Delight\\Cookie\\' => 
        array (
            0 => __DIR__ . '/..' . '/delight-im/cookie/src',
        ),
        'Delight\\Base64\\' => 
        array (
            0 => __DIR__ . '/..' . '/delight-im/base64/src',
        ),
        'Delight\\Auth\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit288d72ebde6c5d22a93734ee9e8d2ff9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit288d72ebde6c5d22a93734ee9e8d2ff9::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit288d72ebde6c5d22a93734ee9e8d2ff9::$classMap;

        }, null, ClassLoader::class);
    }
}

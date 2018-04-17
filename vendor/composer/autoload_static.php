<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1cc557571870cffa18dbb467bc7672a0
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'LLMS\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'LLMS\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1cc557571870cffa18dbb467bc7672a0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1cc557571870cffa18dbb467bc7672a0::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}

<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbd692bd72b5157722c72dde1f3e2d0ab
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbd692bd72b5157722c72dde1f3e2d0ab::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbd692bd72b5157722c72dde1f3e2d0ab::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}

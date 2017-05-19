<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita73384f00a1170ac117edb04fb06829d
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita73384f00a1170ac117edb04fb06829d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita73384f00a1170ac117edb04fb06829d::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}

<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3c52271217cd7709332cd2a4df37ce53
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'DJEM\\Typograph\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'DJEM\\Typograph\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3c52271217cd7709332cd2a4df37ce53::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3c52271217cd7709332cd2a4df37ce53::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}

<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInited76bc1ba5455cfafbab0644b1dc287d
{
    public static $prefixesPsr0 = array (
        'M' => 
        array (
            'Mustache' => 
            array (
                0 => __DIR__ . '/..' . '/mustache/mustache/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInited76bc1ba5455cfafbab0644b1dc287d::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}

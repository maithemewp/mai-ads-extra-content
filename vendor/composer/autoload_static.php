<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdd03b2e1282f3eb0204a0a5e2bd98d37
{
    public static $files = array (
        '7166494aeff09009178f278afd86c83f' => __DIR__ . '/..' . '/yahnis-elsts/plugin-update-checker/load-v4p13.php',
        'ad901de1e5d16b81f427bfe3dc3de508' => __DIR__ . '/..' . '/cmb2/cmb2/init.php',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitdd03b2e1282f3eb0204a0a5e2bd98d37::$classMap;

        }, null, ClassLoader::class);
    }
}

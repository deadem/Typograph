<?php

spl_autoload_register(function ($class) {
    $classes = [
        'DJEM\Typograph' => 'app/Typograph.php'
    ];

    if (isset($classes[$class])) {
        require __DIR__.'/../'.$classes[$class];
    }
}, true, false);

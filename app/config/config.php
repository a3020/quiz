<?php

return [
    // View settings
    'view' => [
        'template_path' => __DIR__.'/../../src/Templates',
        'twig' => [
            'cache' => __DIR__.'/../storage/cache/twig',
            'debug' => true,
            'auto_reload' => true,
        ],
    ],
    'logger' => [
        'name' => 'app',
        'path' => __DIR__.'/../storage/log/app.log',
    ],
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

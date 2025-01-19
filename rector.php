<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/helpers',
        __DIR__ . '/routes',
    ])
    // uncomment to reach your current PHP version
    // ->withPhpSets()
    // ->withPhpSets(php74:true)
    ->withPreparedSets(
        typeDeclarations:true,
    );

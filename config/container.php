<?php

use App\Domains\Services\DomainService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

return [
    'singletons' => [
        EntityManager::class => function () {
            $conn = [
                'driver' => 'pdo_sqlite',
                'path' => dirname(__DIR__) . '/db.sqlite',
            ];

            $config = Setup::createAnnotationMetadataConfiguration([dirname(__DIR__) . '/src'], true, null, null, false);
            return EntityManager::create($conn, $config);
        },
        DomainService::class,
    ]
];

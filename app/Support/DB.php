<?php

namespace App\Support;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Capsule\Manager as Capsule;

class DB
{
    /**
     * @throws BindingResolutionException
     */
    public static function migrate()
    {
        $default = config('database.default', 'mysql');
        $connection = config('database.connections.'.$default);

        $capsule = new Capsule;

        $capsule->addConnection($connection);

        $container = Container::getInstance();
        $databaseMigrationRepository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migration');
        if (!$databaseMigrationRepository->repositoryExists()) {
            $databaseMigrationRepository->createRepository();
        }
        $container->instance(MigrationRepositoryInterface::class, $databaseMigrationRepository);
        $container->instance(ConnectionResolverInterface::class, $capsule->getDatabaseManager());

        $paths = [
            __DIR__.'/../../database/migrations',
        ];

        $migrator = $container->make(Migrator::class);
        $migrator->run($paths); // Run
    }
}

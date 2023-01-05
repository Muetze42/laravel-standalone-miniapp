<?php

namespace App;

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

class Kernel
{
    /**
     * Bootrap this app
     *
     * @return void
     */
    public function bootstrap()
    {
        $this->loadEnvFile();
        $this->setupDatabase();
    }

    protected function setupDatabase()
    {
        $default = config('database.default', 'mysql');
        $connection = config('database.connections.'.$default);

        $capsule = new Capsule;

        $capsule->addConnection($connection);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    /**
     * @return void
     */
    protected function loadEnvFile()
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
    }
}

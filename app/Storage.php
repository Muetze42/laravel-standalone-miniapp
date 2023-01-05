<?php

namespace App;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\FilesystemManager;

class Storage
{
    public static function instance(string $disk = 'app'): Filesystem
    {
        $container = new Container;
        $container->instance('app', $container);
        $config = config('filesystems');
        $container['config'] = new Repository($config);
        /** @var Application $container */
        $filesystemManager = new FilesystemManager($container);

        return $filesystemManager->build(config('filesystems.filesystems.disks.'.$disk));
    }
}

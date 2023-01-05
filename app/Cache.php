<?php

namespace App;

use DateInterval;
use DateTimeInterface;
use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Closure;

class Cache
{
    protected static ?Repository $instance = null;

    /**
     * @return Repository|null
     */
    protected static function getInstance(): ?Repository
    {
        if (!self::$instance) {
            /* @var Application $app */
            $app = new Container;
            $app->singleton('files', function () {
                return new Filesystem();
            });
            $app->singleton('config', function () {
                return [
                    'cache.default'      => 'files',
                    'cache.stores.files' => [
                        'driver' => 'file',
                        'path'   => __DIR__.'/../storage/cache',
                    ]
                ];
            });

            $cacheManager = new CacheManager($app);
            self::$instance = $cacheManager->driver();
        }

        return self::$instance;
    }

    /**
     * Store an item in the cache.
     *
     * @param string $key
     * @param mixed $value
     * @param DateTimeInterface|DateInterval|int|null $ttl
     * @return bool
     */
    public static function put(string $key, $value, $ttl = null): bool
    {
        return self::getInstance()->put($key, $value, $ttl);
    }

    /**
     * Store an item in the cache if the key does not exist.
     *
     * @param string $key
     * @param mixed $value
     * @param DateTimeInterface|DateInterval|int|null $ttl
     * @return bool
     */
    public static function add(string $key, $value, $ttl = null): bool
    {
        return self::getInstance()->add($key, $value, $ttl);
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param string $key
     * @param mixed $value
     * @return int|bool
     */
    public static function increment(string $key, $value = 1): bool
    {
        return self::getInstance()->increment($key, $value);
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param string $key
     * @param mixed $value
     * @return int|bool
     */
    public static function decrement(string $key, $value = 1): bool
    {
        return self::getInstance()->decrement($key, $value);
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function forever(string $key, $value): bool
    {
        return self::getInstance()->forever($key, $value);
    }

    /**
     * Get an item from the cache, or execute the given Closure and store the result forever.
     *
     * @param string $key
     * @param Closure $callback
     * @return mixed
     */
    public static function sear(string $key, Closure $callback)
    {
        return self::getInstance()->sear($key, $callback);
    }

    /**
     * Get an item from the cache, or execute the given Closure and store the result forever.
     *
     * @param string $key
     * @param Closure $callback
     * @return mixed
     */
    public static function rememberForever(string $key, Closure $callback)
    {
        return self::getInstance()->rememberForever($key, $callback);
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key
     * @return bool
     */
    public static function forget(string $key): bool
    {
        return self::getInstance()->forget($key);
    }

    /**
     * Get the cache store implementation.
     *
     * @return Store
     */
    public static function getStore(): Store
    {
        return self::getInstance()->getStore();
    }

}

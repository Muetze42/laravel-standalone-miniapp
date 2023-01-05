<?php

use App\Config;
use Illuminate\Support\Carbon;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Date;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env(string $key, $default = null)
    {
        return Env::get($key, $default);
    }
}

if (!function_exists('config')) {
    /**
     * @param array|string|null $key
     * @param mixed $default
     * @return mixed
     */
    function config($key = null, $default = null)
    {
        return Config::get($key, $default);
    }
}

if (!function_exists('now')) {
    /**
     * Create a new Carbon instance for the current time.
     *
     * @param DateTimeZone|string|null $tz
     * @return Carbon
     */
    function now($tz = null): Carbon
    {
        return Date::now($tz);
    }
}

if (!function_exists('view')) {
    function view(string $view, array $data = []): string
    {
        $viewsPaths = [
            __DIR__.'/../resources/views',
        ];
        $compiledPath = __DIR__.'/../storage/views';

        $filesystem = new Filesystem;
        $eventDispatcher = new Dispatcher(new Container);

        $viewResolver = new EngineResolver;
        $bladeCompiler = new BladeCompiler($filesystem, $compiledPath);

        $viewResolver->register('blade', function () use ($bladeCompiler) {
            return new CompilerEngine($bladeCompiler);
        });

        $viewResolver->register('php', function () use ($filesystem) {
            return new PhpEngine($filesystem);
        });

        $viewFinder = new FileViewFinder($filesystem, $viewsPaths);
        $viewFactory = new Factory($viewResolver, $viewFinder, $eventDispatcher);

        return $viewFactory->make($view, $data)->render();
    }
}

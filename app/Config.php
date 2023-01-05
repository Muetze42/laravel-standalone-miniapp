<?php

namespace App;

use App\Support\ConfigRepository;

class Config
{
    /**
     * @var ConfigRepository|null
     */
    protected static ?ConfigRepository $instance = null;

    /**
     * @return ConfigRepository|null
     */
    protected static function getInstance(): ?ConfigRepository
    {
        if (!self::$instance) {
            self::$instance = new ConfigRepository;
        }

        return self::$instance;
    }

    /**
     * @param array|string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key = null, $default = null)
    {
        return self::getInstance()->get($key, $default);
    }
}

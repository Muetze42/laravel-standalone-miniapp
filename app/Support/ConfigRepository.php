<?php

namespace App\Support;

use Illuminate\Config\Repository;

class ConfigRepository extends Repository
{
    protected string $configPath;

    public function __construct(array $items = [])
    {
        parent::__construct($items);

        $this->configPath = __DIR__.'/../../config';
        $this->loadConfigFiles();
    }

    protected function loadConfigFiles()
    {
        $files = glob($this->configPath.'/*.php');
        $files = array_map('realpath', $files);

        foreach ($files as $file) {
            $this->set(basename($file, '.php'), require $file);
        }
    }
}

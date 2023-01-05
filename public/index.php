<?php

use App\Kernel;
use Illuminate\Database\Capsule\Manager as Capsule;

$vendor = phpversion()[0] == '7' ? 'vendor74' : 'vendor';
require __DIR__.'/../'.$vendor.'/autoload.php';

(new Kernel)->bootstrap();

/*
 * Run Migrations if tables not exist
 * Todo: Need every call?
 * */
if (!Capsule::schema()->hasTable('migrations')) {
    /** @noinspection PhpUnhandledExceptionInspection */
    \App\Support\DB::migrate();

    /* Add some sample data */
    $content = \App\Storage::instance()->get('videos.json');
    $items = json_decode($content, true);
    foreach ($items as $item) {
        \App\Models\Video::firstOrCreate(
            ['title' => $item['title']],
            [
                'description'  => $item['description'],
                'published_at' => now(),
            ]
        );
    }
}

echo view('example');

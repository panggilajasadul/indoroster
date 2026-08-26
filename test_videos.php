<?php

use App\Livewire\VideoInspiration;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$c = new VideoInspiration;
$c->mount();
print_r($c->videos);

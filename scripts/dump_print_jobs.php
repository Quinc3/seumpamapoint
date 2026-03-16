<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$job = DB::table('print_jobs')->orderBy('id','desc')->first();
if (! $job) {
    echo "No print jobs\n";
    exit(0);
}
print_r($job);

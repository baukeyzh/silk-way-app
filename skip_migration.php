<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$batch = \DB::table('migrations')->max('batch');
\DB::table('migrations')->insert([
    'migration' => '2026_03_25_125656_add_picked_at_to_cargo_table',
    'batch' => $batch,
]);
echo "Done\n";

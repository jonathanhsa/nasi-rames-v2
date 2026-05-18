<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

// Setup old connection
Config::set('database.connections.sqlite_old', [
    'driver' => 'sqlite',
    'database' => __DIR__ . '/database/kantin.db',
    'prefix' => '',
]);

$tables = ['users', 'menus', 'orders', 'order_items'];

foreach ($tables as $table) {
    echo "Migrating {$table}...\n";
    $records = DB::connection('sqlite_old')->table($table)->get();
    foreach ($records as $record) {
        $recordArray = (array) $record;
        
        // Disable foreign key checks for mysql temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        try {
            DB::table($table)->insert($recordArray);
        } catch (\Exception $e) {
            echo "Error inserting into {$table}: " . $e->getMessage() . "\n";
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
    echo "Migrated " . count($records) . " records for {$table}.\n";
}
echo "Migration complete.\n";

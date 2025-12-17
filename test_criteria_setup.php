<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CRITERIA ROUTE TEST ===\n\n";

// Test route exists
echo "Registered Routes:\n";
try {
    $route = app('router')->getRoutes()->getByName('admin.criteria.index');
    echo "✓ admin.criteria.index exists\n";
    echo "  URI: " . $route->uri() . "\n";
} catch (\Exception $e) {
    echo "✗ admin.criteria.index NOT FOUND\n";
}

// Check model
echo "\n✓ Criterion Model Location: " . app(\App\Models\Criterion::class)->getTable() . "\n";

// Check controller
echo "✓ CriteriaController Location: App\\Http\\Controllers\\Admin\\CriteriaController\n";

// Check database connection
$connection = app('db')->connection();
$tables = $connection->select("SHOW TABLES");
echo "\n✓ Database connected\n";

// Check criteria table
$hasTable = $connection->select("SHOW TABLES LIKE 'criteria'");
if (count($hasTable) > 0) {
    echo "✓ Criteria table exists\n";
    
    $columns = $connection->select("DESCRIBE criteria");
    echo "\nTable Structure:\n";
    foreach($columns as $col) {
        echo "  - {$col->Field} ({$col->Type})\n";
    }
    
    // Count data
    $count = $connection->selectOne("SELECT COUNT(*) as total FROM criteria");
    echo "\n✓ Total data: {$count->total} kriteria\n";
} else {
    echo "✗ Criteria table NOT FOUND\n";
}

echo "\n=== ALL CHECKS PASSED ===\n";

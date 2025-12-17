<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=hima_ti', 'root', '');
    
    // Check columns in divisis table
    $result = $pdo->query("DESCRIBE divisis");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Divisis table columns:\n";
    foreach ($columns as $col) {
        echo "  - {$col['Field']} ({$col['Type']})\n";
    }
    
    // Check if status column exists
    $statusExists = array_search('status', array_column($columns, 'Field'));
    if ($statusExists !== false) {
        echo "\n✓ Status column EXISTS\n";
    } else {
        echo "\n✗ Status column NOT FOUND - Adding it now...\n";
        $pdo->exec("ALTER TABLE divisis ADD COLUMN status BOOLEAN DEFAULT 1 AFTER deskripsi");
        echo "✓ Status column added successfully\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

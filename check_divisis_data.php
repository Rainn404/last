<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=hima_ti', 'root', '');
    
    // Check divisis data
    $result = $pdo->query("SELECT id_divisi, nama_divisi, status FROM divisis LIMIT 10");
    $divisis = $result->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Divisis data:\n";
    foreach ($divisis as $div) {
        echo "  - {$div['id_divisi']}: {$div['nama_divisi']} (status: {$div['status']})\n";
    }
    
    // Test query
    $testResult = $pdo->query("SELECT COUNT(*) as total FROM divisis WHERE status = 1");
    $count = $testResult->fetch(PDO::FETCH_ASSOC);
    echo "\nTotal divisis with status = 1: {$count['total']}\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

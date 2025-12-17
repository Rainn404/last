<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=hima_ti', 'root', '');
    
    // Get super admin user
    $result = $pdo->query("SELECT id, name, email, role FROM users WHERE email = 'tipolitalahima@gmail.com'");
    $user = $result->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "Super Admin User Found:\n";
        echo "  ID: {$user['id']}\n";
        echo "  Name: {$user['name']}\n";
        echo "  Email: {$user['email']}\n";
        echo "  Role: {$user['role']}\n";
        echo "\n✓ User can access /admin/pelanggaran\n";
    } else {
        echo "Super Admin user not found\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

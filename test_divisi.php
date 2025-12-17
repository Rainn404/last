<?php
// Test script untuk debug divisi
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Set URL untuk divisi
$_SERVER['REQUEST_URI'] = '/divisi';
$_SERVER['REQUEST_METHOD'] = 'GET';

$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

echo "Status Code: " . $response->getStatusCode() . "\n";
echo "Content Length: " . strlen($response->getContent()) . "\n";

// Check if content contains divisi-grid
$content = $response->getContent();
if (strpos($content, 'divisi-grid') !== false) {
    echo "✓ divisi-grid found in response\n";
} else {
    echo "✗ divisi-grid NOT found in response\n";
}

if (strpos($content, 'glass-divisi-card') !== false) {
    echo "✓ glass-divisi-card found in response\n";
} else {
    echo "✗ glass-divisi-card NOT found in response\n";
}

if (strpos($content, '@foreach(\$divisis as \$divisi)') !== false) {
    echo "✗ Blade syntax not compiled - ISSUE!\n";
} else {
    echo "✓ Blade syntax compiled\n";
}

echo "\nFirst 2000 chars of response:\n";
echo substr($content, 0, 2000) . "\n";
?>

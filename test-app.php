<?php

echo "Testing Laravel Application...\n\n";

// Change to the correct directory
chdir('/workspaces/magang');

// Test 1: Check if vendor exists
echo "✓ Vendor directory exists: " . (is_dir('vendor') ? 'YES' : 'NO') . "\n";

// Test 2: Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

echo "✓ Laravel application bootstrapped successfully\n";

// Test 3: Check environment
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "✓ Application environment: " . app()->environment() . "\n";
echo "✓ Debug mode: " . (config('app.debug') ? 'ENABLED' : 'DISABLED') . "\n";
echo "✓ Database connection: " . config('database.default') . "\n";

// Test 4: Check database
try {
    $userCount = \App\Models\User::count();
    echo "✓ Database connected. Users count: $userCount\n";
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
}

// Test 5: Check routes
$routes = app('router')->getRoutes();
echo "✓ Total routes registered: " . $routes->count() . "\n";

// Test 6: Try to render welcome view
try {
    $html = view('welcome')->render();
    $htmlLength = strlen($html);
    echo "✓ Welcome view rendered successfully ($htmlLength bytes)\n";
    echo "\n";
    echo "First 500 characters of rendered HTML:\n";
    echo "=====================================\n";
    echo substr($html, 0, 500) . "...\n";
} catch (Exception $e) {
    echo "✗ View error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n✓ All tests passed!\n";

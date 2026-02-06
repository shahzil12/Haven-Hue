<?php
// Force error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<div style='font-family: monospace; padding: 20px;'>";
echo "<h1>Vercel Laravel Boot Debugger</h1>";

// Manually clear cache before anything else
$storagePath = '/tmp/storage';
if (is_dir($storagePath . '/framework/cache')) {
    $files = glob($storagePath . '/framework/cache/*.php');
    foreach ($files as $file) {
        @unlink($file);
    }
    echo "<p style='color:blue'>[Debug] Cleared /tmp config cache manually.</p>";
}

try {
    echo "<p><strong>Step 1:</strong> Checking vendor/autoload.php... ";
    if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
        throw new Exception("File not found: vendor/autoload.php");
    }
    echo "<span style='color:green'>FOUND</span></p>";

    echo "<p><strong>Step 2:</strong> Requiring vendor/autoload.php... ";
    require __DIR__ . '/../vendor/autoload.php';
    echo "<span style='color:green'>DONE</span></p>";

    echo "<p><strong>Step 3:</strong> Requiring bootstrap/app.php... ";
    if (!file_exists(__DIR__ . '/../bootstrap/app.php')) {
        throw new Exception("File not found: bootstrap/app.php");
    }
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "<span style='color:green'>DONE</span></p>";

    echo "<p><strong>Step 4:</strong> Capturing Request... ";
    $request = Illuminate\Http\Request::capture();
    echo "<span style='color:green'>DONE</span></p>";

    echo "<p><strong>Step 5:</strong> Handling Request... (This is where it usually fails)</p>";
    $response = $app->handleRequest($request);
    
    echo "<p><strong>Step 6:</strong> Sending Response... </p>";
    $response->send();
    
    echo "<p><strong>Step 7:</strong> Terminating... </p>";
    $app->terminate($request, $response);

} catch (\Throwable $e) {
    echo "<div style='background: #f8d7da; padding: 10px; border: 1px solid red; color: #721c24;'>";
    echo "<h3>CRITICAL BOOT ERROR</h3>";
    echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . " on line " . $e->getLine() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

echo "</div>";

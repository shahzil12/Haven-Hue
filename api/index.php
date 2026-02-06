<?php
// Standard Vercel Entry Point with Error Trapping
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    // Return 200 to ensure the browser displays the output
    http_response_code(200);
    header('Content-Type: text/html');
    echo "<div style='font-family: monospace; background: #f8d7da; padding: 20px; border: 2px solid #f5c6cb;'>";
    echo "<h1 style='color: #721c24;'>Vercel Deployment Error</h1>";
    
    echo "<h3>Environment Check:</h3>";
    echo "<ul>";
    echo "<li>APP_KEY Set? " . (getenv('APP_KEY') ? '<span style="color:green">YES</span>' : '<span style="color:red">NO</span>') . "</li>";
    echo "<li>Vercel Detected? " . ((getenv('VERCEL') || isset($_ENV['VERCEL'])) ? '<span style="color:green">YES</span>' : '<span style="color:red">NO</span>') . "</li>";
    echo "</ul>";

    echo "<h3>Exception Details:</h3>";
    echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . " on line " . $e->getLine() . "</p>";
    echo "<h4>Stack Trace:</h4>";
    echo "<pre style='background: #fff; padding: 10px; overflow: auto;'>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
    exit;
}

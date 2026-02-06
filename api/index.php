<?php
// Force Debug Mode to see the error behind "500 | Server Error"
putenv('APP_DEBUG=true');
$_ENV['APP_DEBUG'] = 'true';
$_SERVER['APP_DEBUG'] = 'true';

require __DIR__ . '/../public/index.php';

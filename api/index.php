<?php
putenv('APP_DEBUG=true');

if (getenv('VERCEL')) {
    $dbPath = '/tmp/database.sqlite';
    $sourceDb = __DIR__ . '/../database/database.sqlite';
    if (!file_exists($dbPath) || @filesize($dbPath) < 10240) {
        if (file_exists($sourceDb) && @filesize($sourceDb) > 10240) {
            @copy($sourceDb, $dbPath);
        }
    }
}

require __DIR__ . '/../public/index.php';

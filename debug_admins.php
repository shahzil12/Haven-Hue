<?php

use App\Models\User;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$admins = User::where('role', 'admin')->get();

echo "Total Admins Found: " . $admins->count() . "\n";
foreach ($admins as $admin) {
    echo "Admin ID: " . $admin->id . ", Email: " . $admin->email . "\n";
}

$currentUser = User::latest()->first();
if ($currentUser) {
    echo "Latest User ID: " . $currentUser->id . ", Role: " . $currentUser->role . "\n";
} else {
    echo "No users found in database.\n";
}

<?php

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting synchronization of students...\n";

$users = User::where('role', 'siswa')->get();
$count = 0;

foreach ($users as $user) {
    $exists = Siswa::where('nis', $user->nis)->exists();
    if (!$exists) {
        Siswa::create([
            'nis' => $user->nis,
            'nama_siswa' => $user->name,
            'kelas' => '-',
            'jurusan' => '-',
            'password' => $user->password, // Already hashed
        ]);
        $count++;
        echo "Synced: {$user->name} ({$user->nis})\n";
    }
}

echo "Synchronization complete. Total synced: {$count}\n";

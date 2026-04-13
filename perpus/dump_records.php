<?php
use App\Models\User;
use App\Models\Siswa;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "USER TABLE (role=siswa):\n";
foreach (User::where('role', 'siswa')->get() as $u) {
    echo "ID: {$u->id}, Name: [{$u->name}], NIS: [{$u->nis}]\n";
}

echo "\nSISWA TABLE:\n";
foreach (Siswa::all() as $s) {
    echo "ID: {$s->id_siswa}, Name: [{$s->nama_siswa}], NIS: [{$s->nis}]\n";
}

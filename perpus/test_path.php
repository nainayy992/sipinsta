<?php
$file = 'C:\laragon\www\perpustakaan-sekolah\perpus\vendor\composer/../psy/psysh/src/functions.php';
echo "Checking problematic path: $file\n";
if (file_exists($file)) {
    echo "File exists according to file_exists.\n";
    try {
        require $file;
        echo "Require success.\n";
    } catch (Throwable $e) {
        echo "Require failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "File does not exist according to file_exists.\n";
}
echo "Realpath: " . realpath($file) . "\n";

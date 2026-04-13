<?php
$file = __DIR__ . '/vendor/psy/psysh/src/functions.php';
echo "Checking file: $file\n";
if (file_exists($file)) {
    echo "File exists.\n";
    try {
        require_once $file;
        echo "Require success.\n";
    } catch (Throwable $e) {
        echo "Require failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "File does not exist.\n";
}

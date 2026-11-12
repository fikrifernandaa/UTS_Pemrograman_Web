<?php
declare(strict_types=1);

// Autoloader PSR-4 sederhana: PustakaKu\Models\Media => src/Models/Media.php
spl_autoload_register(static function (string $class): void {
    $prefix = 'PustakaKu\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $file = __DIR__ . '/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($file)) {
        require $file;
    }
});
require_once __DIR__ . '/helpers.php';

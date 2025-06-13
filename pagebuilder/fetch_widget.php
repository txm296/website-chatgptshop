<?php
ob_start();
if (file_exists(__DIR__ . '/../inc/debug.php')) {
    require_once __DIR__ . '/../inc/debug.php';
}
require __DIR__ . '/builder.php';

$name = basename($_GET['name'] ?? '');
if (!preg_match('/^[a-zA-Z0-9_-]+$/', $name)) {
    http_response_code(400);
    exit;
}

$file = __DIR__ . '/widgets/' . $name . '.php';
if (!file_exists($file)) {
    http_response_code(404);
    exit;
}

$builder = new ModularPageBuilder();
header('Content-Type: text/html; charset=UTF-8');
echo $builder->renderWidget($file);
ob_end_flush();


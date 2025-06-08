<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($path !== '/' && file_exists(__DIR__ . $path)) {
    return false; // serve the requested resource as-is
}
if (preg_match('#^/([A-Za-z0-9-]+)/?$#', $path, $m)) {
    $_GET['slug'] = $m[1];
    include __DIR__ . '/page.php';
    return true;
}
return false;
?>

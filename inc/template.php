<?php
function render_template($name, $vars = []) {
    $file = __DIR__ . '/../templates/' . $name . '.php';
    if (!file_exists($file)) {
        return;
    }
    extract($vars, EXTR_SKIP);
    include $file;
}

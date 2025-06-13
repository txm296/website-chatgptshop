<?php
// Enable error reporting when APP_DEBUG environment variable is set
if (getenv('APP_DEBUG')) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

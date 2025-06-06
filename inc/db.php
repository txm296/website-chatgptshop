<?php
$host = 'database-5017987658.webspace-host.com';
$db   = 'dbs14303460';
$user = 'dbu1268189';
$pass = 'TiSch_2906_website';
$dsn = "mysql:host=$host;port=3306;dbname=$db;charset=utf8mb4";
$options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("DB-Verbindung fehlgeschlagen: " . $e->getMessage());
}


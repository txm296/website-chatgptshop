<?php
// Datenbank-Verbindung
// Diese Anwendung nutzt ausschließlich MySQL.
//
// Die Verbindung kann über folgende Umgebungsvariablen angepasst werden:
//   DB_HOST  - MySQL Hostname (Standard: 127.0.0.1)
//   DB_PORT  - MySQL Port (Standard: 3306)
//   DB_NAME  - Name der MySQL Datenbank (Standard: nezbi)
//   DB_USER  - MySQL Benutzer (Standard: root)
//   DB_PASS  - MySQL Passwort (Standard: leer)

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$db   = getenv('DB_NAME') ?: 'nezbi';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$dsn  = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

$options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('DB-Verbindung fehlgeschlagen: ' . $e->getMessage());
}

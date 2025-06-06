<?php
// Datenbank-Verbindung
// Standardmäßig wird eine lokale SQLite Datenbank verwendet.
// 
// Die Verbindung kann über Umgebungsvariablen angepasst werden:
//   DB_TYPE  - "mysql" oder "sqlite" (Standard: sqlite)
//   DB_HOST  - MySQL Hostname
//   DB_NAME  - Name der MySQL Datenbank bzw. Pfad zur SQLite Datei
//   DB_USER  - MySQL Benutzer
//   DB_PASS  - MySQL Passwort

$type = getenv('DB_TYPE') ?: 'sqlite';

if ($type === 'mysql') {
    $host = getenv('DB_HOST') ?: 'localhost';
    $db   = getenv('DB_NAME') ?: 'nezbi';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';
    $dsn = "mysql:host=$host;port=3306;dbname=$db;charset=utf8mb4";
    $userpass = [$user, $pass];
} else {
    $db = getenv('DB_NAME') ?: __DIR__ . '/../nezbi.sqlite';
    $dsn = "sqlite:$db";
    $userpass = [null, null];
}

$options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ];

try {
    $pdo = new PDO($dsn, $userpass[0], $userpass[1], $options);
} catch (PDOException $e) {
    die('DB-Verbindung fehlgeschlagen: ' . $e->getMessage());
}


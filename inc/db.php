<?php
// Datenbank-Verbindung
// Diese Anwendung nutzt ausschließlich MySQL.
//
// Die Verbindung kann über folgende Umgebungsvariablen angepasst werden:
//   DB_HOST  - MySQL Hostname (Standard: database-5017987658.webspace-host.com)
//   DB_PORT  - MySQL Port (Standard: 3306)
//   DB_NAME  - Name der MySQL Datenbank (Standard: dbs14303460)
//   DB_USER  - MySQL Benutzer (Standard: dbu1268189)
//   DB_PASS  - MySQL Passwort (Standard: TiSch_2906_website)

$host = getenv('DB_HOST') ?: 'database-5017987658.webspace-host.com';
$port = getenv('DB_PORT') ?: '3306';
$db   = getenv('DB_NAME') ?: 'dbs14303460';
$user = getenv('DB_USER') ?: 'dbu1268189';
$pass = getenv('DB_PASS') ?: 'TiSch_2906_website';
$dsn  = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

$options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Fallback auf lokale SQLite-Datenbank, falls MySQL nicht erreichbar ist
    $sqlitePath = __DIR__ . '/../data/shop.db';
    $needInit = !file_exists($sqlitePath);
    $pdo = new PDO('sqlite:' . $sqlitePath, null, null, $options);
    if ($needInit) {
        $initFile = __DIR__ . '/../sql/setup_sqlite.sql';
        if (file_exists($initFile)) {
            $pdo->exec(file_get_contents($initFile));
        }
    } else {
        // Ensure tables exist when using an already created SQLite database
        $pdo->exec("CREATE TABLE IF NOT EXISTS kategorien (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS produkte (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, beschreibung TEXT, preis REAL, rabatt REAL DEFAULT NULL, bild TEXT, menge INTEGER, aktiv INTEGER DEFAULT 1, kategorie_id INTEGER REFERENCES kategorien(id))");
        $pdo->exec("CREATE TABLE IF NOT EXISTS pages (id INTEGER PRIMARY KEY AUTOINCREMENT, slug TEXT UNIQUE, title TEXT, content TEXT, meta_title TEXT, meta_description TEXT, canonical_url TEXT, jsonld TEXT)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS builder_pages (id INTEGER PRIMARY KEY AUTOINCREMENT, slug TEXT UNIQUE, title TEXT, layout TEXT)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS builder_popups (id INTEGER PRIMARY KEY AUTOINCREMENT, slug TEXT UNIQUE, title TEXT, layout TEXT, triggers TEXT, pages TEXT)");
        // Kategorien nicht wieder erscheinen
    }
}

// Tabelle 'pages' sicherstellen, falls MySQL verwendet wird oder sie fehlt
try {
    $pdo->query("SELECT 1 FROM pages LIMIT 1");
} catch (PDOException $e) {
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    if ($driver === 'sqlite') {
        $pdo->exec("CREATE TABLE IF NOT EXISTS pages (id INTEGER PRIMARY KEY AUTOINCREMENT, slug TEXT UNIQUE, title TEXT, content TEXT, meta_title TEXT, meta_description TEXT, canonical_url TEXT, jsonld TEXT)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS builder_pages (id INTEGER PRIMARY KEY AUTOINCREMENT, slug TEXT UNIQUE, title TEXT, layout TEXT)");
    } else {
        $pdo->exec("CREATE TABLE IF NOT EXISTS pages (id INT AUTO_INCREMENT PRIMARY KEY, slug VARCHAR(200) UNIQUE, title VARCHAR(200), content TEXT, meta_title TEXT, meta_description TEXT, canonical_url TEXT, jsonld TEXT)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS builder_pages (id INT AUTO_INCREMENT PRIMARY KEY, slug VARCHAR(200) UNIQUE, title VARCHAR(200), layout TEXT)");
        $pdo->exec("CREATE TABLE IF NOT EXISTS builder_popups (id INT AUTO_INCREMENT PRIMARY KEY, slug VARCHAR(200) UNIQUE, title VARCHAR(200), layout TEXT, triggers TEXT, pages TEXT)");
    }
}

// Sicherstellen, dass neue SEO-Spalten vorhanden sind
function ensureColumn(PDO $pdo, $table, $column, $type) {
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    try {
        if ($driver === 'sqlite') {
            $cols = array_column($pdo->query("PRAGMA table_info($table)")->fetchAll(PDO::FETCH_ASSOC), 'name');
        } else {
            $cols = array_column($pdo->query("SHOW COLUMNS FROM $table")->fetchAll(PDO::FETCH_ASSOC), 'Field');
        }
    } catch (Exception $e) {
        $cols = [];
    }
    if (!in_array($column, $cols)) {
        $pdo->exec("ALTER TABLE $table ADD COLUMN $column $type");
    }
}

ensureColumn($pdo, 'pages', 'meta_title', 'TEXT');
ensureColumn($pdo, 'pages', 'meta_description', 'TEXT');
ensureColumn($pdo, 'pages', 'canonical_url', 'TEXT');
ensureColumn($pdo, 'pages', 'jsonld', 'TEXT');

// Tabelle 'builder_popups' sicherstellen
try {
    $pdo->query("SELECT 1 FROM builder_popups LIMIT 1");
} catch (PDOException $e) {
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    if ($driver === 'sqlite') {
        $pdo->exec("CREATE TABLE IF NOT EXISTS builder_popups (id INTEGER PRIMARY KEY AUTOINCREMENT, slug TEXT UNIQUE, title TEXT, layout TEXT, triggers TEXT, pages TEXT)");
    } else {
        $pdo->exec("CREATE TABLE IF NOT EXISTS builder_popups (id INT AUTO_INCREMENT PRIMARY KEY, slug VARCHAR(200) UNIQUE, title VARCHAR(200), layout TEXT, triggers TEXT, pages TEXT)");
    }
}

// Tabelle 'builder_templates' sicherstellen
try {
    $pdo->query("SELECT 1 FROM builder_templates LIMIT 1");
} catch (PDOException $e) {
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    if ($driver === 'sqlite') {
        $pdo->exec("CREATE TABLE IF NOT EXISTS builder_templates (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, html TEXT)");
    } else {
        $pdo->exec("CREATE TABLE IF NOT EXISTS builder_templates (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(200), html TEXT)");
    }
}

// Spalte fuer Mitarbeiterrechte nachruesten
ensureColumn($pdo, 'admins', 'rechte', 'TEXT');

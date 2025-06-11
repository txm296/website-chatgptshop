
CREATE TABLE kategorien (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

INSERT INTO kategorien (name) VALUES
  ('Smartphones'),
  ('Laptops'),
  ('Audio');

CREATE TABLE produkte (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    beschreibung TEXT,
    preis DECIMAL(10,2),
    rabatt DECIMAL(10,2) DEFAULT NULL,
    bild VARCHAR(255),
    menge INT DEFAULT NULL,
    aktiv TINYINT DEFAULT 1,
    kategorie_id INT DEFAULT NULL,
    FOREIGN KEY (kategorie_id) REFERENCES kategorien(id)
);

CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    passwort VARCHAR(255) NOT NULL
);

CREATE TABLE bestellungen (
    id INT PRIMARY KEY AUTO_INCREMENT,
    warenkorb TEXT,
    summe DECIMAL(10,2),
    zeitstempel DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rabattcodes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(32) UNIQUE,
    typ ENUM('betrag','prozent') DEFAULT 'betrag',
    wert DECIMAL(10,2),
    aktiv TINYINT DEFAULT 1
);

-- Beispieladmin, Passwort ist "nezbi" (bitte nach dem Login ändern!)
INSERT INTO admins (username, passwort) VALUES ('admin', '$2y$10$2m.bMtqb4s3jLS.7BgUVleppmSDZ6Dqf1hnbdWdPNs1naaQgGo0Sy');

CREATE TABLE pages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    slug VARCHAR(200) UNIQUE,
    title VARCHAR(200),
    content TEXT,
    meta_title VARCHAR(255),
    meta_description TEXT,
    canonical_url VARCHAR(255),
    jsonld TEXT
);

CREATE TABLE builder_pages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    slug VARCHAR(200) UNIQUE,
    title VARCHAR(200),
    layout TEXT
);

CREATE TABLE builder_popups (
    id INT PRIMARY KEY AUTO_INCREMENT,
    slug VARCHAR(200) UNIQUE,
    title VARCHAR(200),
    layout TEXT,
    triggers TEXT,
    pages TEXT
);

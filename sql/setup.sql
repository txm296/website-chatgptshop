
CREATE TABLE produkte (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    beschreibung TEXT,
    preis DECIMAL(10,2),
    bild VARCHAR(255),
    menge INT DEFAULT NULL,
    aktiv TINYINT DEFAULT 1
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
INSERT INTO admins (username, passwort) VALUES ('admin', '$2y$10$uOrlQ9bL/JzGQ/vjMVtRjeptD9kOcEvMuVgib1yJJXhwpiWY9tPja');

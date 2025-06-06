CREATE TABLE produkte (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    beschreibung TEXT,
    preis REAL,
    bild TEXT,
    menge INTEGER,
    aktiv INTEGER DEFAULT 1
);

CREATE TABLE admins (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    passwort TEXT NOT NULL
);

CREATE TABLE bestellungen (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    warenkorb TEXT,
    summe REAL,
    zeitstempel DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rabattcodes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code TEXT UNIQUE,
    typ TEXT DEFAULT 'betrag',
    wert REAL,
    aktiv INTEGER DEFAULT 1
);

-- Beispieladmin, Passwort ist "nezbi" (bitte nach dem Login ändern!)
INSERT INTO admins (username, passwort) VALUES ('admin', '$2y$10$uOrlQ9bL/JzGQ/vjMVtRjeptD9kOcEvMuVgib1yJJXhwpiWY9tPja');

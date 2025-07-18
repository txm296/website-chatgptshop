CREATE TABLE kategorien (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL
);

INSERT INTO kategorien (name) VALUES
  ('Smartphones'),
  ('Laptops'),
  ('Audio');

CREATE TABLE produkte (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    beschreibung TEXT,
    preis REAL,
    rabatt REAL DEFAULT NULL,
    bild TEXT,
    menge INTEGER,
    aktiv INTEGER DEFAULT 1,
    kategorie_id INTEGER REFERENCES kategorien(id)
);

CREATE TABLE admins (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    passwort TEXT NOT NULL,
    rechte TEXT
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
INSERT INTO admins (username, passwort, rechte) VALUES ('admin', '$2y$10$2m.bMtqb4s3jLS.7BgUVleppmSDZ6Dqf1hnbdWdPNs1naaQgGo0Sy', 'add_products,edit_prices,edit_products,manage_categories,manage_orders,edit_pages');

CREATE TABLE pages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slug TEXT UNIQUE,
    title TEXT,
    content TEXT,
    meta_title TEXT,
    meta_description TEXT,
    canonical_url TEXT,
    jsonld TEXT
);

CREATE TABLE builder_pages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slug TEXT UNIQUE,
    title TEXT,
    layout TEXT
);

CREATE TABLE builder_popups (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slug TEXT UNIQUE,
    title TEXT,
    layout TEXT,
    triggers TEXT,
    pages TEXT
);

-- Vorlagen für wiederverwendbare Layouts
CREATE TABLE builder_templates (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    html TEXT
);

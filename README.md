# nezbi Onlineshop

Dieses Repository enthält einen funktionsfähigen PHP Onlineshop. Zur Ausführung wird lediglich eine MySQL-Datenbank benötigt. Produktbilder werden im Verzeichnis `assets/products` abgelegt.

## Lokale Installation

1. Abhängigkeiten installieren (PHP mit MySQL-Unterstützung):
   ```bash
   sudo apt-get install php-cli php-mysql mysql-server
   ```
2. Datenbank einrichten:
   ```bash
   ./scripts/init_db.sh
   ```
3. Entwicklungsserver starten:
   ```bash
   php -S 127.0.0.1:8000 -t . router.php
   ```

Optional können die Verbindungsdaten zur Datenbank über folgende
Umgebungsvariablen angepasst werden:

```
DB_HOST - MySQL Hostname (Standard: database-5017987658.webspace-host.com)
DB_PORT - MySQL Port (Standard: 3306)
DB_NAME - Name der Datenbank (Standard: dbs14303460)
DB_USER - MySQL Benutzer (Standard: dbu1268189)
DB_PASS - Passwort des Benutzers
```

Beispiel zum Setzen der Variablen in der Shell:

```bash
export DB_HOST=database-5017987658.webspace-host.com
export DB_NAME=dbs14303460
export DB_USER=dbu1268189
export DB_PASS=<dein_passwort>
```

Der Shop ist anschließend unter <http://127.0.0.1:8000> erreichbar.

Für saubere URLs ohne `page.php?slug=` sorgt eine `.htaccess`-Datei. Beim
lokalen Entwicklungsserver übernimmt dies `router.php`, welches automatisch
Anfragen wie `/meineseite` an `page.php` weiterleitet.

## Website anpassen

Im Adminbereich lässt sich unter "Website bearbeiten" das Aussehen anpassen. Neben Farben und Texten stehen jetzt zehn stark unterschiedliche Templates zur Auswahl. Zusätzlich können eine Hintergrundfarbe und eigenes CSS festgelegt werden.
Im Header der Seite befindet sich außerdem ein Button, um zwischen Light- und Darkmode zu wechseln.
Unter "Seiten" steht nun ein eigener Page-Builder zur Verfügung, mit dem sich Überschriften, Textabsätze, Bilder und Buttons frei zusammensetzen lassen. Die Inhalte werden lokal im Browser bearbeitet und erscheinen automatisch in der Navigation.

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
Unter "Seiten" gibt es einen einfachen Drag‑and‑Drop Website‑Editor ohne Fremdbibliotheken. Damit lassen sich Text‑ und Bildelemente inline bearbeiten und frei anordnen. Die resultierenden HTML‑Strukturen werden in der Datenbank gespeichert und auf den jeweiligen Seiten ausgegeben.
Neu ist außerdem eine Seite "Templates" im Adminbereich. Dort finden sich CSS‑Vorlagen, etwa für Ladeanimationen, die man per Drag & Drop direkt in den Editor ziehen kann.

Ebenfalls neu ist ein modularer visueller Page Builder. Die zugehörigen Dateien befinden sich im Verzeichnis `pagebuilder` und können im Adminbereich über den Menüpunkt "Builder" aufgerufen werden. Jedes Widget besteht aus einer PHP-Datei sowie einem HTML-Template. Per Drag & Drop können aktuell zehn Beispiel-Widgets wie Text, Bild oder Video auf die Seite gezogen werden. Neu ist außerdem ein Breakpoint-System für Desktop, Tablet und Mobile. Für jeden Viewport lassen sich individuelle Einstellungen speichern und die Vorschau im Editor umschalten.

Der Builder kann nun auch für bestehende Shop-Seiten wie die Startseite, Kategorien oder CMS-Seiten verwendet werden. Liegt für eine Seite ein Eintrag in `builder_pages` vor, ersetzt das dort gespeicherte Layout den regulären Inhalt. Über den Parameter `?classic=1` lässt sich jederzeit zum Standardlayout zurückkehren.

Neu ist die Möglichkeit, einzelne Abschnitte oder Widgets zu kopieren und in andere Seiten einzufügen. Eigene Layouts können zudem als Vorlage gespeichert werden. Diese Vorlagen werden in einer Bibliothek verwaltet und lassen sich von dort in jede Seite einfügen.

## Globale Templates

Im Verzeichnis `templates` liegen wiederverwendbare Abschnitte wie `header.php`, `footer.php` oder `cta.php`. Mit der Funktion `render_template()` aus `inc/template.php` lassen sich diese Bereiche auf beliebigen Seiten einbinden:

```php
require_once 'inc/template.php';
render_template('cta');
```

Änderungen an einer Template-Datei wirken sich automatisch auf alle Seiten aus, auf denen sie verwendet wird.

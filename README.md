# nezbi Shop Demo

Dieses Repository enthält eine einfache PHP Shop-Demo. Die Anwendung setzt ausschließlich eine MySQL-Datenbank voraus.

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
   php -S 127.0.0.1:8000
   ```

Optional können die Verbindungsdaten zur Datenbank über folgende
Umgebungsvariablen angepasst werden:

```
DB_HOST - MySQL Hostname (Standard: 127.0.0.1)
DB_PORT - MySQL Port (Standard: 3306)
DB_NAME - Name der Datenbank (Standard: nezbi)
DB_USER - MySQL Benutzer (Standard: root)
DB_PASS - Passwort des Benutzers
```

Der Shop ist anschließend unter <http://127.0.0.1:8000> erreichbar.

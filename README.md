# nezbi Shop Demo

Dieses Repository enthält eine einfache PHP Shop-Demo. Für lokale Tests wird eine SQLite Datenbank verwendet.

## Lokale Installation

1. Abhängigkeiten installieren (PHP mit SQLite-Unterstützung):
   ```bash
   sudo apt-get install php-cli php-sqlite3
   ```
2. Datenbank erstellen:
   ```bash
   ./scripts/init_db.sh
   ```
3. Entwicklungsserver starten:
   ```bash
   php -S 127.0.0.1:8000
   ```

Der Shop ist anschließend unter <http://127.0.0.1:8000> erreichbar.

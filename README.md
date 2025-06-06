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

Der Shop ist anschließend unter <http://127.0.0.1:8000> erreichbar.

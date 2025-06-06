#!/bin/sh
set -e
DB_FILE="$(dirname "$0")/../nezbi.sqlite"
SQL_FILE="$(dirname "$0")/../sql/setup_sqlite.sql"
if [ ! -f "$DB_FILE" ]; then
    echo "Creating SQLite database at $DB_FILE"
    sqlite3 "$DB_FILE" < "$SQL_FILE"
    echo "Database created."
else
    echo "Database already exists at $DB_FILE"
fi

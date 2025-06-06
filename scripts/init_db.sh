#!/bin/sh
set -e
SQL_FILE="$(dirname "$0")/../sql/setup.sql"
DB_NAME="${DB_NAME:-nezbi}"
DB_HOST="${DB_HOST:-localhost}"
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASS:-}"

echo "Creating MySQL database $DB_NAME on $DB_HOST"
MYSQL_PWD="$DB_PASS" mysql -h "$DB_HOST" -u "$DB_USER" -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\`;"
MYSQL_PWD="$DB_PASS" mysql -h "$DB_HOST" -u "$DB_USER" "$DB_NAME" < "$SQL_FILE"

echo "Database setup completed."

#!/bin/bash
# ── Stock snapshot cron ───────────────────────────────────────────────────────
# Runs every 5 minutes from the server crontab. Keeps daily_stock_snapshots in
# sync with the live products table:
#   • today's rows are created once per product (opening stock = last known
#     closing stock, closing stock = live quantity)
#   • closing_stock is overwritten on every run — the script is idempotent and
#     never creates duplicates (rows are unique on snapshot_date + product_id)
#   • opening_stock is written once per day and then left alone
#
# WHY THE CRON WAS FAILING (from Sep 14 onwards):
#   ERROR 1045 (28000): Access denied for user 'lerulelv_salstech'@'localhost'
#   The MySQL password hardcoded in this file no longer matched the database —
#   most likely the cPanel/database password was rotated.
#
# FIX: the DB password is no longer stored in this script. It is read from the
# Laravel .env instead, so the cron and the app always share the same
# credentials. When the password changes, updating .env fixes both at once.
#
# If the app is not in the default directory, set APP_DIR in the crontab line:
#   */5 * * * * APP_DIR=/home/lerulelv/public_html /home/lerulelv/crons/run_stock_update.sh

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
# Laravel app root (the directory that contains .env) — override via APP_DIR env var
APP_DIR="${APP_DIR:-/home/lerulelv/public_html}"
DB_NAME=""
DB_USER=""
DB_PASS=""

SQL_FILE="$SCRIPT_DIR/daily_stock_update.sql"
LOG_FILE="$SCRIPT_DIR/mysql_stock_cron.log"

mkdir -p "$SCRIPT_DIR"

log() {
    echo "[$(date)] $*" >> "$LOG_FILE"
}

# Keep the log from growing without bound (single .old backup)
if [ -f "$LOG_FILE" ] && [ "$(stat -c%s "$LOG_FILE" 2>/dev/null || echo 0)" -gt 5242880 ]; then
    mv "$LOG_FILE" "$LOG_FILE.old"
fi

log "========================================"
log "Starting daily stock snapshot update..."

# ── DB credentials: taken from the app's .env ────────────────────────────────
if [ ! -f "$APP_DIR/.env" ]; then
    log "ERROR: .env not found at $APP_DIR/.env — set APP_DIR to the Laravel app root and retry."
    exit 1
fi

# Read one KEY=value from .env, stripping quotes, CR and surrounding spaces
env_value() {
    sed -n "s/^$1=//p" "$APP_DIR/.env" | tail -n 1 | tr -d "\"'\r "
}

DB_NAME="$(env_value DB_DATABASE)"
DB_USER="$(env_value DB_USERNAME)"
DB_PASS="$(env_value DB_PASSWORD)"
log "Credentials source: $APP_DIR/.env (database: ${DB_NAME:-unset}, user: ${DB_USER:-unset})"

if [ -z "$DB_NAME" ] || [ -z "$DB_USER" ]; then
    log "ERROR: DB_DATABASE / DB_USERNAME missing in $APP_DIR/.env"
    exit 1
fi

if [ -z "$DB_PASS" ]; then
    log "ERROR: DB_PASSWORD is empty in $APP_DIR/.env"
    exit 1
fi

if [ ! -f "$SQL_FILE" ]; then
    log "ERROR: SQL file not found at $SQL_FILE"
    exit 1
fi

# Pick the available client (MariaDB-only servers ship 'mariadb', not 'mysql')
MYSQL_BIN="$(command -v mysql || command -v mariadb)"
if [ -z "$MYSQL_BIN" ]; then
    log "ERROR: neither 'mysql' nor 'mariadb' found on PATH"
    exit 1
fi

# Hold the password in a 600-permission temp file so it never appears in the
# process list, in this script, or in the log
DEFAULTS_FILE="$(mktemp)"
chmod 600 "$DEFAULTS_FILE"
printf '[client]\nuser=%s\npassword=%s\n' "$DB_USER" "$DB_PASS" > "$DEFAULTS_FILE"
cleanup() { rm -f "$DEFAULTS_FILE"; }
trap cleanup EXIT

log "Running SQL update ($MYSQL_BIN)..."
if "$MYSQL_BIN" --defaults-extra-file="$DEFAULTS_FILE" "$DB_NAME" < "$SQL_FILE" >> "$LOG_FILE" 2>&1; then
    log "SQL executed successfully."

    # Report how many snapshot rows exist for today (same credentials)
    RECORD_COUNT="$("$MYSQL_BIN" --defaults-extra-file="$DEFAULTS_FILE" "$DB_NAME" \
        -e "SELECT COUNT(*) FROM daily_stock_snapshots WHERE snapshot_date = CURDATE();" -B -N 2>>"$LOG_FILE")"
    log "Records for today: ${RECORD_COUNT:-unknown}"
else
    EXIT_CODE=$?
    # 1045 = bad credentials: fix DB_PASSWORD in .env.
    # Anything else points at a SQL/schema problem in daily_stock_update.sql
    log "ERROR: SQL execution failed (exit $EXIT_CODE). If the output above says '1045 Access denied', update DB_PASSWORD in $APP_DIR/.env."
    exit 1
fi

log "Snapshot update completed."
log "========================================"
exit 0

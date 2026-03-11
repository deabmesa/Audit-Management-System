#!/usr/bin/env bash
set -euo pipefail

echo "[codespaces] Preparing Audit Management System workspace"

if ! command -v php >/dev/null 2>&1; then
  echo "[codespaces] php not found, attempting install"
  bash scripts/dev/install-php.sh
fi

if [ ! -f .env ]; then
  cp .env.example .env
  echo "[codespaces] .env created from .env.example"
fi

if command -v composer >/dev/null 2>&1; then
  echo "[codespaces] Installing Composer dependencies"
  composer install --no-interaction || echo "[codespaces] composer install skipped/failed (network constraints)"
else
  echo "[codespaces] composer not found; skip dependency install"
fi

echo "[codespaces] Starting PostgreSQL service via docker compose"
docker compose -f docker-compose.postgres.yml up -d

echo "[codespaces] Waiting for PostgreSQL readiness"
for i in {1..30}; do
  if docker compose -f docker-compose.postgres.yml exec -T postgres pg_isready -U audit_app -d audit_main >/dev/null 2>&1; then
    echo "[codespaces] PostgreSQL is ready"
    exit 0
  fi
  sleep 2
done

echo "[codespaces] PostgreSQL did not become ready in time"
exit 1

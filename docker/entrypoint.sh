#!/bin/bash
set -e

cd /app

if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
fi

: "${APP_ENV:=production}"
: "${APP_DEBUG:=false}"
: "${APP_URL:=http://localhost:8000}"
: "${DB_CONNECTION:=pgsql}"
: "${DB_HOST:=postgres}"
: "${DB_PORT:=5432}"
: "${DB_DATABASE:=audit_management}"
: "${DB_USERNAME:=postgres}"
: "${DB_PASSWORD:=postgres}"

python - <<'PY'
from pathlib import Path
path = Path('/app/.env')
if path.exists():
    lines = path.read_text().splitlines()
    env = {
        'APP_ENV': 'production',
        'APP_DEBUG': 'false',
        'APP_URL': 'http://localhost:8000',
        'DB_CONNECTION': 'pgsql',
        'DB_HOST': 'postgres',
        'DB_PORT': '5432',
        'DB_DATABASE': 'audit_management',
        'DB_USERNAME': 'postgres',
        'DB_PASSWORD': 'postgres',
    }
    import os
    env = {k: os.environ.get(k, v) for k, v in env.items()}
    updated = []
    seen = set()
    for line in lines:
        if '=' in line and not line.lstrip().startswith('#'):
            key = line.split('=', 1)[0]
            if key in env:
                updated.append(f'{key}={env[key]}')
                seen.add(key)
                continue
        updated.append(line)
    for key, value in env.items():
        if key not in seen:
            updated.append(f'{key}={value}')
    path.write_text('\n'.join(updated) + '\n')
PY

exec ./deploy.sh

#!/usr/bin/env bash
set -euo pipefail

echo "== PHP / OpenSSL Preflight =="

if ! command -v php >/dev/null 2>&1; then
  echo "ERROR: php binary not found in PATH"
  exit 1
fi

PHP_BIN=$(command -v php)
echo "php: $PHP_BIN"

if ! php -v >/dev/null 2>&1; then
  echo "ERROR: php cannot start. Common cause: OpenSSL runtime mismatch."
  echo "Hint: run 'ldd $PHP_BIN | grep -E "ssl|crypto"' and inspect linked libraries."
  exit 2
fi

echo "php version:"
php -v | head -n 1

echo "linked ssl/crypto libs:"
ldd "$PHP_BIN" | grep -E "ssl|crypto" || true

echo "openssl extension info:"
php -i | grep -E "^OpenSSL|^SSL Version|^openssl\.cafile|^openssl\.capath" || true

if [ -n "${LD_LIBRARY_PATH:-}" ]; then
  echo "WARN: LD_LIBRARY_PATH is set: $LD_LIBRARY_PATH"
  echo "      Ensure it does not force old/foreign libssl/libcrypto before system libs."
fi

echo "Preflight completed."

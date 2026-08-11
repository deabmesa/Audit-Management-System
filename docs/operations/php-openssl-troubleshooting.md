# PHP/OpenSSL Troubleshooting (`OPENSSL_1_1_1`)

## Symptom

```text
php: /lib/x86_64-linux-gnu/libcrypto.so.1.1: version `OPENSSL_1_1_1' not found (required by php)
```

## Root Cause

PHP is loading an incompatible OpenSSL runtime library at execution time (often due to mixed repositories or overridden library paths such as `LD_LIBRARY_PATH`).

## Steps

1. Run preflight:

```bash
./scripts/rhel8/php-preflight.sh
```

2. Inspect linked libraries:

```bash
ldd "$(command -v php)" | grep -E 'ssl|crypto'
```

3. Check for environment overrides:

```bash
env | grep -E '^LD_LIBRARY_PATH|^ORACLE_HOME|^TNS_ADMIN'
```

4. Remove conflicting overrides from shell profiles/systemd unit drop-ins for php-fpm.

5. Ensure PHP and OpenSSL come from consistent packages (same vendor stream), then restart:

```bash
sudo systemctl restart php-fpm nginx
```

## Safe Fallback

If deployment must continue while fixing runtime libraries, install source + vendor only:

```bash
APP_SKIP_ARTISAN=1 ./scripts/rhel8/install-offline.sh offline-bundle /var/www/audit-management-system
```

Then run artisan commands after runtime is healthy.

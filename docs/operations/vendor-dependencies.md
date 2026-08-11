# Vendor Dependencies Strategy

This repository does **not** commit `vendor/` to Git (standard practice).

## Online installation

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
```

## Offline installation

1. Build bundle on internet-enabled build host:

```bash
./scripts/rhel8/prepare-offline-bundle.sh offline-bundle
```

2. Transfer `offline-bundle/` to target server.
3. Install on offline target:

```bash
./scripts/rhel8/install-offline.sh offline-bundle /var/www/audit-management-system
```

## Validation

```bash
test -f vendor/autoload.php && echo "vendor restored"
```

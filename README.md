# Audit Management System (Laravel + PostgreSQL + OpenShift)

Production-ready starter implementation targeting **RHEL 8** and **OpenShift Container Platform**.

## Features
- Laravel authentication (login/logout), profile and password hashing
- RBAC with Administrator, Auditor, Manager roles
- Dashboard with summary cards and Chart.js
- Audit lifecycle modules: planning, fieldwork, reporting, management reporting
- REST API (`/api/audits`) secured by Sanctum
- Activity logging middleware
- Secure uploads with file validation and storage in `storage/app/public/uploads`

## Stack
- PHP 8.2, Laravel 10 LTS-compatible baseline
- Blade + Bootstrap 5 + Chart.js
- PostgreSQL + Redis
- Docker / Docker Compose
- Jenkins CI/CD pipeline
- OpenShift manifests under `k8s/`

## Local setup
```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve --host=0.0.0.0 --port=8080
```

## Docker setup
```bash
docker compose up --build -d
```

## OpenShift deployment
```bash
oc project <your-project>
oc apply -f k8s/configmap.yaml
oc apply -f k8s/secret.yaml
oc apply -f k8s/persistent-volume.yaml
oc apply -f k8s/deployment.yaml
oc apply -f k8s/service.yaml
oc apply -f k8s/route.yaml
```

## Jenkins pipeline
`Jenkinsfile` includes:
1. Checkout source from Git
2. Install dependencies
3. Run PHPUnit tests
4. Build Docker image
5. Push Docker image
6. Deploy to OpenShift

## RHEL 8 notes
- Use UBI8 PHP 8.2 image for compatibility.
- Ensure SELinux context for persistent volumes in OpenShift.
- Keep secrets in OpenShift Secret or external vault.

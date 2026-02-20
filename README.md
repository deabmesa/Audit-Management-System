# Audit Management System (Spring Boot 3, Multi-DB Oracle + PostgreSQL)

Production-ready enterprise starter for Audit Planning, Execution, Findings, Follow-up Tracking, and Role-based access control.

## Technology Stack
- Java 17+
- Spring Boot 3.3.x
- Spring Data JPA
- Spring Security + JWT
- Oracle XE (primary operational DB)
- PostgreSQL (audit/reporting DB)
- Maven
- OpenAPI/Swagger

## System Domains (2-Part Architecture)
- **Part 1: Staff Info**: user registry, roles, authentication/authorization, and staff directory endpoints under `/api/staff`.
- **Part 2: Audit Work**: planning, engagement execution, findings, action plans, and follow-up tracking under `/api/audit-work` and `/api/findings`.

## Project Structure
```
src/main/java/com/example/auditmanagement
├── config
├── controller
│   ├── auditwork
│   ├── report
│   └── staff
├── dto
│   ├── auditwork
│   ├── report
│   └── staff
├── entity
│   ├── common
│   ├── oracle
│   └── postgres
├── exception
├── mapper
├── repository
│   ├── oracle
│   └── postgres
├── security
├── service
│   ├── auditwork
│   │   └── impl
│   ├── report
│   │   └── impl
│   ├── staff
│   │   └── impl
│   └── impl
└── util
```

## Multi-Database Configuration
- `OracleConfig`: Oracle `DataSource`, `EntityManagerFactory`, `PlatformTransactionManager`, repository scan.
- `PostgresConfig`: PostgreSQL equivalents for reporting/logging entities.
- `@EnableJpaRepositories` is used with distinct `basePackages`, persistence units, and transaction managers.
- `@Transactional(transactionManager = "...")` controls database-specific transactions.

## Business Modules Delivered
1. Audit Planning entities (`AuditPlan`, `RiskRating`, auditor assignment via `User`).
2. Audit Execution entities (`AuditEngagement`, working paper URL).
3. Audit Findings (`Finding` with risk, root cause, recommendation, management response, target date, status).
4. Follow-up tracking (`ActionPlan` with remediation evidence URL).
5. User and role management (`User`, `Role`, `RoleName`).

## Cross-Database Coordination Example
`FindingServiceImpl#createFinding`:
1. Creates `Finding` in Oracle transaction manager.
2. Inserts `FindingHistory` + `SystemAuditLog` in PostgreSQL transaction manager.

> For strict distributed commit semantics across Oracle/PostgreSQL, integrate XA/JTA (e.g., Narayana). This starter demonstrates coordinated dual writes with explicit transaction managers.

## Oracle Read-Only Reporting Database Access
- Added dedicated `OracleReadOnlyConfig` with its own `DataSource`, `EntityManagerFactory`, and `TransactionManager`.
- Repository package `repository.oracle.readonly` is intentionally isolated from write repositories.
- Service methods use `@Transactional(readOnly = true, transactionManager = "oracleReadOnlyTransactionManager")` to enforce read-only reporting paths.
- Endpoint: `GET /api/reports/read-only/findings/summary` (roles: `AUDIT_MANAGER`, `ADMIN`).

## API Endpoints
### Auth
- `POST /api/auth/register`
- `POST /api/auth/login`

### Findings
- `POST /api/findings`
- `GET /api/findings?status=OPEN&page=0&size=20`
- `GET /api/reports/read-only/findings/summary`

### Staff Info
- `GET /api/staff`

### Audit Work
- `POST /api/audit-work/plans`
- `POST /api/audit-work/engagements`
- `PUT /api/audit-work/action-plans/{actionPlanId}/evidence`
- `GET /api/audit-work/action-plans/overdue?page=0&size=20`

## Sample Requests / Responses
### Register
Request:
```json
{
  "username": "audit.manager",
  "email": "manager@audit.local",
  "password": "Str0ngPass!",
  "role": "AUDIT_MANAGER"
}
```
Response: `201 Created`

### Login
Request:
```json
{
  "username": "audit.manager",
  "password": "Str0ngPass!"
}
```
Response:
```json
{
  "token": "eyJhbGciOiJIUzI1NiJ9...",
  "tokenType": "Bearer"
}
```

### Create Finding
Request:
```json
{
  "engagementId": 1001,
  "riskRating": "HIGH",
  "observation": "Lack of maker-checker control in payment approval.",
  "rootCause": "Process design gap",
  "recommendation": "Implement maker-checker workflow",
  "managementResponse": "Will implement in Q3",
  "targetCompletionDate": "2026-06-30"
}
```
Response:
```json
{
  "id": 2001,
  "engagementId": 1001,
  "riskRating": "HIGH",
  "observation": "Lack of maker-checker control in payment approval.",
  "rootCause": "Process design gap",
  "recommendation": "Implement maker-checker workflow",
  "managementResponse": "Will implement in Q3",
  "targetCompletionDate": "2026-06-30",
  "status": "OPEN",
  "createdDate": "2026-02-20T09:30:10"
}
```

## Run with Docker Compose
```bash
docker compose up --build
```

## Local Run
```bash
mvn clean spring-boot:run
```

## DDL Scripts
- Oracle: `src/main/resources/db/oracle-ddl.sql`
- PostgreSQL: `src/main/resources/db/postgres-ddl.sql`

## Swagger
- `http://localhost:8080/swagger-ui.html`


### Read-Only Finding Summary Report
Response:
```json
[
  {
    "riskRating": "HIGH",
    "status": "OPEN",
    "total": 12
  },
  {
    "riskRating": "MEDIUM",
    "status": "CLOSED",
    "total": 5
  }
]
```

## Oracle read-only user setup
Create a low-privilege report user (`audit_report_reader`) in Oracle and grant `SELECT` privileges only on reporting tables/views used by read-only repositories.


### Create Audit Plan
Request:
```json
{
  "auditYear": 2026,
  "auditUniverse": "Procurement & Payments",
  "riskRating": "HIGH",
  "assignedAuditorId": 1
}
```

### Create Audit Engagement
Request:
```json
{
  "title": "Procurement Process Audit",
  "auditPlanId": 10,
  "workingPaperUrl": "https://dms.local/wp/proc-2026-01"
}
```


## Red Hat Enterprise Linux 8 Deployment

### Prerequisites
```bash
sudo dnf install -y java-17-openjdk java-17-openjdk-devel
sudo dnf install -y git
```

### Build and package
```bash
mvn clean package -DskipTests
```

### Install as systemd service
```bash
./scripts/rhel8/install-service.sh
sudo cp target/audit-management-system-1.0.0.jar /opt/audit-management/audit-management-system.jar
sudo chown auditapp:auditapp /opt/audit-management/audit-management-system.jar
sudo vi /etc/audit-management/audit-management.env
sudo systemctl start audit-management
sudo systemctl status audit-management
```

### Logs and operations
```bash
sudo journalctl -u audit-management -f
sudo tail -f /var/log/audit-management/audit-management.log
```

Deployment files:
- `deployment/rhel8/audit-management.service`
- `deployment/rhel8/audit-management.env.example`
- `src/main/resources/application-rhel8.yml`
- `scripts/rhel8/install-service.sh`

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

## Project Structure
```
src/main/java/com/example/auditmanagement
├── config
├── controller
├── dto
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

## API Endpoints
### Auth
- `POST /api/auth/register`
- `POST /api/auth/login`

### Findings
- `POST /api/findings`
- `GET /api/findings?status=OPEN&page=0&size=20`

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

package com.example.auditmanagement.repository.postgres;

import com.example.auditmanagement.entity.postgres.SystemAuditLog;
import org.springframework.data.jpa.repository.JpaRepository;

public interface SystemAuditLogRepository extends JpaRepository<SystemAuditLog, Long> {
}

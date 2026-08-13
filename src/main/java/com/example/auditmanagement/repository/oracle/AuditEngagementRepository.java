package com.example.auditmanagement.repository.oracle;

import com.example.auditmanagement.entity.oracle.AuditEngagement;
import org.springframework.data.jpa.repository.JpaRepository;

public interface AuditEngagementRepository extends JpaRepository<AuditEngagement, Long> {
}

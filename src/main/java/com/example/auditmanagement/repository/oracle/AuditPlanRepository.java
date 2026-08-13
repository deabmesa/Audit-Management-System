package com.example.auditmanagement.repository.oracle;

import com.example.auditmanagement.entity.oracle.AuditPlan;
import org.springframework.data.jpa.repository.JpaRepository;

public interface AuditPlanRepository extends JpaRepository<AuditPlan, Long> {
}

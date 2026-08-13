package com.example.auditmanagement.repository.oracle;

import com.example.auditmanagement.entity.oracle.ActionPlan;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;

import java.time.LocalDate;

public interface ActionPlanRepository extends JpaRepository<ActionPlan, Long> {

    @Query("""
            select ap from ActionPlan ap
            where ap.finding.targetCompletionDate < :today
              and ap.finding.status <> com.example.auditmanagement.entity.oracle.FindingStatus.CLOSED
            """)
    Page<ActionPlan> findOverdue(LocalDate today, Pageable pageable);
}

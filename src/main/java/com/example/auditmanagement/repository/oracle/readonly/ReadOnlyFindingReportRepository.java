package com.example.auditmanagement.repository.oracle.readonly;

import com.example.auditmanagement.entity.oracle.Finding;
import com.example.auditmanagement.entity.oracle.FindingStatus;
import com.example.auditmanagement.entity.oracle.RiskRating;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;

import java.util.List;

public interface ReadOnlyFindingReportRepository extends JpaRepository<Finding, Long> {

    @Query("""
            select f.riskRating as riskRating,
                   f.status as status,
                   count(f.id) as total
            from Finding f
            group by f.riskRating, f.status
            order by f.riskRating, f.status
            """)
    List<FindingSummaryProjection> summarizeByRiskAndStatus();

    interface FindingSummaryProjection {
        RiskRating getRiskRating();
        FindingStatus getStatus();
        Long getTotal();
    }
}

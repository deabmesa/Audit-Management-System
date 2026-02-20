package com.example.auditmanagement.dto.auditwork;

import com.example.auditmanagement.entity.oracle.RiskRating;
import lombok.Builder;
import lombok.Data;

@Data
@Builder
public class AuditPlanResponse {
    private Long id;
    private Integer auditYear;
    private String auditUniverse;
    private RiskRating riskRating;
    private Long assignedAuditorId;
}

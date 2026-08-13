package com.example.auditmanagement.dto.auditwork;

import com.example.auditmanagement.entity.oracle.RiskRating;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import lombok.Data;

@Data
public class AuditPlanCreateRequest {
    @NotNull
    private Integer auditYear;
    @NotBlank
    private String auditUniverse;
    @NotNull
    private RiskRating riskRating;
    @NotNull
    private Long assignedAuditorId;
}

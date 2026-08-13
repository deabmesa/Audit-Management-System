package com.example.auditmanagement.dto;

import com.example.auditmanagement.entity.oracle.FindingStatus;
import com.example.auditmanagement.entity.oracle.RiskRating;
import lombok.Builder;
import lombok.Data;

import java.time.LocalDate;
import java.time.LocalDateTime;

@Data
@Builder
public class FindingResponse {
    private Long id;
    private Long engagementId;
    private RiskRating riskRating;
    private String observation;
    private String rootCause;
    private String recommendation;
    private String managementResponse;
    private LocalDate targetCompletionDate;
    private FindingStatus status;
    private LocalDateTime createdDate;
}

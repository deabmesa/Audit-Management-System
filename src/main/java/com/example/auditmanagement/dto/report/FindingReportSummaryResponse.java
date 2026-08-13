package com.example.auditmanagement.dto.report;

import com.example.auditmanagement.entity.oracle.FindingStatus;
import com.example.auditmanagement.entity.oracle.RiskRating;
import lombok.Builder;
import lombok.Data;

@Data
@Builder
public class FindingReportSummaryResponse {
    private RiskRating riskRating;
    private FindingStatus status;
    private Long total;
}

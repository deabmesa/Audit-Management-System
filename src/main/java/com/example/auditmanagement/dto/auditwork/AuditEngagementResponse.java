package com.example.auditmanagement.dto.auditwork;

import lombok.Builder;
import lombok.Data;

@Data
@Builder
public class AuditEngagementResponse {
    private Long id;
    private String title;
    private Long auditPlanId;
    private String workingPaperUrl;
}

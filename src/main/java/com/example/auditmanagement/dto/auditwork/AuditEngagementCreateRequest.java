package com.example.auditmanagement.dto.auditwork;

import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import lombok.Data;

@Data
public class AuditEngagementCreateRequest {
    @NotBlank
    private String title;
    @NotNull
    private Long auditPlanId;
    private String workingPaperUrl;
}

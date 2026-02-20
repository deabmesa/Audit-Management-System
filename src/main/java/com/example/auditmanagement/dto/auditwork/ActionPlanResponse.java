package com.example.auditmanagement.dto.auditwork;

import lombok.Builder;
import lombok.Data;

@Data
@Builder
public class ActionPlanResponse {
    private Long id;
    private Long findingId;
    private String remediationAction;
    private String evidenceUrl;
}

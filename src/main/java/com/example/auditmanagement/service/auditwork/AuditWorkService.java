package com.example.auditmanagement.service.auditwork;

import com.example.auditmanagement.dto.auditwork.*;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;

public interface AuditWorkService {
    AuditPlanResponse createAuditPlan(AuditPlanCreateRequest request);
    AuditEngagementResponse createEngagement(AuditEngagementCreateRequest request);
    ActionPlanResponse uploadRemediationEvidence(Long actionPlanId, ActionPlanEvidenceUpdateRequest request);
    Page<ActionPlanResponse> listOverdueActionPlans(Pageable pageable);
}

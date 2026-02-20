package com.example.auditmanagement.controller.auditwork;

import com.example.auditmanagement.dto.auditwork.*;
import com.example.auditmanagement.service.auditwork.AuditWorkService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/audit-work")
@RequiredArgsConstructor
public class AuditWorkController {

    private final AuditWorkService auditWorkService;

    @PostMapping("/plans")
    @PreAuthorize("hasAnyRole('ADMIN','AUDIT_MANAGER')")
    public AuditPlanResponse createPlan(@Valid @RequestBody AuditPlanCreateRequest request) {
        return auditWorkService.createAuditPlan(request);
    }

    @PostMapping("/engagements")
    @PreAuthorize("hasAnyRole('ADMIN','AUDIT_MANAGER','AUDITOR')")
    public AuditEngagementResponse createEngagement(@Valid @RequestBody AuditEngagementCreateRequest request) {
        return auditWorkService.createEngagement(request);
    }

    @PutMapping("/action-plans/{actionPlanId}/evidence")
    @PreAuthorize("hasAnyRole('ADMIN','AUDIT_MANAGER','BUSINESS_OWNER')")
    public ActionPlanResponse uploadEvidence(@PathVariable Long actionPlanId,
                                             @Valid @RequestBody ActionPlanEvidenceUpdateRequest request) {
        return auditWorkService.uploadRemediationEvidence(actionPlanId, request);
    }

    @GetMapping("/action-plans/overdue")
    @PreAuthorize("hasAnyRole('ADMIN','AUDIT_MANAGER','AUDITOR')")
    public Page<ActionPlanResponse> overdue(Pageable pageable) {
        return auditWorkService.listOverdueActionPlans(pageable);
    }
}

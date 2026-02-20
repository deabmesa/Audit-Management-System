package com.example.auditmanagement.service.auditwork.impl;

import com.example.auditmanagement.dto.auditwork.*;
import com.example.auditmanagement.entity.oracle.*;
import com.example.auditmanagement.exception.ResourceNotFoundException;
import com.example.auditmanagement.repository.oracle.*;
import com.example.auditmanagement.service.auditwork.AuditWorkService;
import lombok.RequiredArgsConstructor;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.time.LocalDate;

@Service
@RequiredArgsConstructor
public class AuditWorkServiceImpl implements AuditWorkService {

    private final AuditPlanRepository auditPlanRepository;
    private final AuditEngagementRepository auditEngagementRepository;
    private final ActionPlanRepository actionPlanRepository;
    private final UserRepository userRepository;

    @Override
    @Transactional(transactionManager = "oracleTransactionManager")
    public AuditPlanResponse createAuditPlan(AuditPlanCreateRequest request) {
        User auditor = userRepository.findById(request.getAssignedAuditorId())
                .orElseThrow(() -> new ResourceNotFoundException("Assigned auditor not found"));

        AuditPlan plan = new AuditPlan();
        plan.setAuditYear(request.getAuditYear());
        plan.setAuditUniverse(request.getAuditUniverse());
        plan.setRiskRating(request.getRiskRating());
        plan.setAssignedAuditor(auditor);

        AuditPlan saved = auditPlanRepository.save(plan);
        return AuditPlanResponse.builder()
                .id(saved.getId())
                .auditYear(saved.getAuditYear())
                .auditUniverse(saved.getAuditUniverse())
                .riskRating(saved.getRiskRating())
                .assignedAuditorId(saved.getAssignedAuditor().getId())
                .build();
    }

    @Override
    @Transactional(transactionManager = "oracleTransactionManager")
    public AuditEngagementResponse createEngagement(AuditEngagementCreateRequest request) {
        AuditPlan plan = auditPlanRepository.findById(request.getAuditPlanId())
                .orElseThrow(() -> new ResourceNotFoundException("Audit plan not found"));

        AuditEngagement engagement = new AuditEngagement();
        engagement.setTitle(request.getTitle());
        engagement.setAuditPlan(plan);
        engagement.setWorkingPaperUrl(request.getWorkingPaperUrl());

        AuditEngagement saved = auditEngagementRepository.save(engagement);
        return AuditEngagementResponse.builder()
                .id(saved.getId())
                .title(saved.getTitle())
                .auditPlanId(saved.getAuditPlan().getId())
                .workingPaperUrl(saved.getWorkingPaperUrl())
                .build();
    }

    @Override
    @Transactional(transactionManager = "oracleTransactionManager")
    public ActionPlanResponse uploadRemediationEvidence(Long actionPlanId, ActionPlanEvidenceUpdateRequest request) {
        ActionPlan actionPlan = actionPlanRepository.findById(actionPlanId)
                .orElseThrow(() -> new ResourceNotFoundException("Action plan not found"));

        actionPlan.setEvidenceUrl(request.getEvidenceUrl());
        ActionPlan saved = actionPlanRepository.save(actionPlan);

        return ActionPlanResponse.builder()
                .id(saved.getId())
                .findingId(saved.getFinding().getId())
                .remediationAction(saved.getRemediationAction())
                .evidenceUrl(saved.getEvidenceUrl())
                .build();
    }

    @Override
    @Transactional(readOnly = true, transactionManager = "oracleTransactionManager")
    public Page<ActionPlanResponse> listOverdueActionPlans(Pageable pageable) {
        return actionPlanRepository.findOverdue(LocalDate.now(), pageable)
                .map(ap -> ActionPlanResponse.builder()
                        .id(ap.getId())
                        .findingId(ap.getFinding().getId())
                        .remediationAction(ap.getRemediationAction())
                        .evidenceUrl(ap.getEvidenceUrl())
                        .build());
    }
}

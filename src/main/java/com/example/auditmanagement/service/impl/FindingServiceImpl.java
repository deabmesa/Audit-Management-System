package com.example.auditmanagement.service.impl;

import com.example.auditmanagement.dto.FindingCreateRequest;
import com.example.auditmanagement.dto.FindingResponse;
import com.example.auditmanagement.entity.oracle.AuditEngagement;
import com.example.auditmanagement.entity.oracle.Finding;
import com.example.auditmanagement.entity.oracle.FindingStatus;
import com.example.auditmanagement.entity.postgres.FindingHistory;
import com.example.auditmanagement.entity.postgres.SystemAuditLog;
import com.example.auditmanagement.exception.ResourceNotFoundException;
import com.example.auditmanagement.mapper.FindingMapper;
import com.example.auditmanagement.repository.oracle.AuditEngagementRepository;
import com.example.auditmanagement.repository.oracle.FindingRepository;
import com.example.auditmanagement.repository.postgres.FindingHistoryRepository;
import com.example.auditmanagement.repository.postgres.SystemAuditLogRepository;
import com.example.auditmanagement.service.FindingService;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.time.LocalDateTime;

@Service
@RequiredArgsConstructor
@Slf4j
public class FindingServiceImpl implements FindingService {

    private final FindingRepository findingRepository;
    private final AuditEngagementRepository engagementRepository;
    private final FindingHistoryRepository findingHistoryRepository;
    private final SystemAuditLogRepository systemAuditLogRepository;
    private final FindingMapper findingMapper;

    @Override
    @Transactional(transactionManager = "oracleTransactionManager")
    public FindingResponse createFinding(FindingCreateRequest request, String actor) {
        AuditEngagement engagement = engagementRepository.findById(request.getEngagementId())
                .orElseThrow(() -> new ResourceNotFoundException("Engagement not found"));

        Finding finding = new Finding();
        finding.setEngagement(engagement);
        finding.setRiskRating(request.getRiskRating());
        finding.setObservation(request.getObservation());
        finding.setRootCause(request.getRootCause());
        finding.setRecommendation(request.getRecommendation());
        finding.setManagementResponse(request.getManagementResponse());
        finding.setTargetCompletionDate(request.getTargetCompletionDate());

        Finding persisted = findingRepository.save(finding);
        persistToReportingDb(persisted.getId(), persisted.getStatus(), actor);

        log.info("Finding {} created by {}", persisted.getId(), actor);
        return findingMapper.toResponse(persisted);
    }

    @Transactional(transactionManager = "postgresTransactionManager")
    public void persistToReportingDb(Long findingId, FindingStatus status, String actor) {
        FindingHistory findingHistory = new FindingHistory();
        findingHistory.setFindingId(findingId);
        findingHistory.setStatus(status.name());
        findingHistory.setRemarks("Finding created");
        findingHistory.setChangedAt(LocalDateTime.now());
        findingHistoryRepository.save(findingHistory);

        SystemAuditLog auditLog = new SystemAuditLog();
        auditLog.setEventType("FINDING_CREATED");
        auditLog.setActor(actor);
        auditLog.setDetails("Created finding id=" + findingId + " with status=" + status.name());
        auditLog.setEventTime(LocalDateTime.now());
        systemAuditLogRepository.save(auditLog);
    }

    @Override
    @Transactional(readOnly = true, transactionManager = "oracleTransactionManager")
    public Page<FindingResponse> listFindings(FindingStatus status, Pageable pageable) {
        Page<Finding> page = status == null ? findingRepository.findAll(pageable) : findingRepository.findByStatus(status, pageable);
        return page.map(findingMapper::toResponse);
    }
}

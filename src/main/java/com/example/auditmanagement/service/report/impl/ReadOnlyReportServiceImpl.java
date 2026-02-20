package com.example.auditmanagement.service.report.impl;

import com.example.auditmanagement.dto.report.FindingReportSummaryResponse;
import com.example.auditmanagement.repository.oracle.readonly.ReadOnlyFindingReportRepository;
import com.example.auditmanagement.service.report.ReadOnlyReportService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

@Service
@RequiredArgsConstructor
public class ReadOnlyReportServiceImpl implements ReadOnlyReportService {

    private final ReadOnlyFindingReportRepository readOnlyFindingReportRepository;

    @Override
    @Transactional(readOnly = true, transactionManager = "oracleReadOnlyTransactionManager")
    public List<FindingReportSummaryResponse> getFindingSummary() {
        return readOnlyFindingReportRepository.summarizeByRiskAndStatus().stream()
                .map(row -> FindingReportSummaryResponse.builder()
                        .riskRating(row.getRiskRating())
                        .status(row.getStatus())
                        .total(row.getTotal())
                        .build())
                .toList();
    }
}

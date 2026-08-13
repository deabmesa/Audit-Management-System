package com.example.auditmanagement.service.report;

import com.example.auditmanagement.dto.report.FindingReportSummaryResponse;

import java.util.List;

public interface ReadOnlyReportService {
    List<FindingReportSummaryResponse> getFindingSummary();
}

package com.example.auditmanagement.controller.report;

import com.example.auditmanagement.dto.report.FindingReportSummaryResponse;
import com.example.auditmanagement.service.report.ReadOnlyReportService;
import lombok.RequiredArgsConstructor;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import java.util.List;

@RestController
@RequestMapping("/api/reports/read-only")
@RequiredArgsConstructor
public class ReadOnlyReportController {

    private final ReadOnlyReportService readOnlyReportService;

    @GetMapping("/findings/summary")
    @PreAuthorize("hasAnyRole('AUDIT_MANAGER','ADMIN')")
    public List<FindingReportSummaryResponse> findingSummary() {
        return readOnlyReportService.getFindingSummary();
    }
}

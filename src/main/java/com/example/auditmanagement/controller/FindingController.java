package com.example.auditmanagement.controller;

import com.example.auditmanagement.dto.FindingCreateRequest;
import com.example.auditmanagement.dto.FindingResponse;
import com.example.auditmanagement.entity.oracle.FindingStatus;
import com.example.auditmanagement.service.FindingService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.security.core.Authentication;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/findings")
@RequiredArgsConstructor
public class FindingController {

    private final FindingService findingService;

    @PostMapping
    @PreAuthorize("hasAnyRole('AUDITOR','AUDIT_MANAGER','ADMIN')")
    public FindingResponse createFinding(@Valid @RequestBody FindingCreateRequest request, Authentication authentication) {
        return findingService.createFinding(request, authentication.getName());
    }

    @GetMapping
    @PreAuthorize("hasAnyRole('AUDITOR','AUDIT_MANAGER','ADMIN','BUSINESS_OWNER')")
    public Page<FindingResponse> listFindings(@RequestParam(required = false) FindingStatus status, Pageable pageable) {
        return findingService.listFindings(status, pageable);
    }
}

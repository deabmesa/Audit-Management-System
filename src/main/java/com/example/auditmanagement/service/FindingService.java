package com.example.auditmanagement.service;

import com.example.auditmanagement.dto.FindingCreateRequest;
import com.example.auditmanagement.dto.FindingResponse;
import com.example.auditmanagement.entity.oracle.FindingStatus;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;

public interface FindingService {
    FindingResponse createFinding(FindingCreateRequest request, String actor);
    Page<FindingResponse> listFindings(FindingStatus status, Pageable pageable);
}

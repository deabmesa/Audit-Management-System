package com.example.auditmanagement.mapper;

import com.example.auditmanagement.dto.FindingResponse;
import com.example.auditmanagement.entity.oracle.Finding;
import org.springframework.stereotype.Component;

@Component
public class FindingMapper {

    public FindingResponse toResponse(Finding finding) {
        return FindingResponse.builder()
                .id(finding.getId())
                .engagementId(finding.getEngagement().getId())
                .riskRating(finding.getRiskRating())
                .observation(finding.getObservation())
                .rootCause(finding.getRootCause())
                .recommendation(finding.getRecommendation())
                .managementResponse(finding.getManagementResponse())
                .targetCompletionDate(finding.getTargetCompletionDate())
                .status(finding.getStatus())
                .createdDate(finding.getCreatedDate())
                .build();
    }
}

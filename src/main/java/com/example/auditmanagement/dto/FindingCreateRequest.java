package com.example.auditmanagement.dto;

import com.example.auditmanagement.entity.oracle.RiskRating;
import jakarta.validation.constraints.Future;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import lombok.Data;

import java.time.LocalDate;

@Data
public class FindingCreateRequest {

    @NotNull
    private Long engagementId;

    @NotNull
    private RiskRating riskRating;

    @NotBlank
    private String observation;

    private String rootCause;

    private String recommendation;

    private String managementResponse;

    @Future
    private LocalDate targetCompletionDate;
}

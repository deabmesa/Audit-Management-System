package com.example.auditmanagement.dto.auditwork;

import jakarta.validation.constraints.NotBlank;
import lombok.Data;

@Data
public class ActionPlanEvidenceUpdateRequest {
    @NotBlank
    private String evidenceUrl;
}

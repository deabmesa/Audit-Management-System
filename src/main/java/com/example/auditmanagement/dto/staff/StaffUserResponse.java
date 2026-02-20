package com.example.auditmanagement.dto.staff;

import lombok.Builder;
import lombok.Data;

import java.util.Set;

@Data
@Builder
public class StaffUserResponse {
    private Long id;
    private String username;
    private String email;
    private Set<String> roles;
}

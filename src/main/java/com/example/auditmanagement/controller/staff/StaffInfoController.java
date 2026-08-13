package com.example.auditmanagement.controller.staff;

import com.example.auditmanagement.dto.staff.StaffUserResponse;
import com.example.auditmanagement.service.staff.StaffInfoService;
import lombok.RequiredArgsConstructor;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import java.util.List;

@RestController
@RequestMapping("/api/staff")
@RequiredArgsConstructor
public class StaffInfoController {

    private final StaffInfoService staffInfoService;

    @GetMapping
    @PreAuthorize("hasAnyRole('ADMIN','AUDIT_MANAGER')")
    public List<StaffUserResponse> getStaffUsers() {
        return staffInfoService.getAllStaff();
    }
}

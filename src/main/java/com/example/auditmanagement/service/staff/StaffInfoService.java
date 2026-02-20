package com.example.auditmanagement.service.staff;

import com.example.auditmanagement.dto.staff.StaffUserResponse;

import java.util.List;

public interface StaffInfoService {
    List<StaffUserResponse> getAllStaff();
}

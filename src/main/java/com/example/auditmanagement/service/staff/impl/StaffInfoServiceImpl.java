package com.example.auditmanagement.service.staff.impl;

import com.example.auditmanagement.dto.staff.StaffUserResponse;
import com.example.auditmanagement.repository.oracle.UserRepository;
import com.example.auditmanagement.service.staff.StaffInfoService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

@Service
@RequiredArgsConstructor
public class StaffInfoServiceImpl implements StaffInfoService {

    private final UserRepository userRepository;

    @Override
    @Transactional(readOnly = true, transactionManager = "oracleTransactionManager")
    public List<StaffUserResponse> getAllStaff() {
        return userRepository.findAll().stream()
                .map(user -> StaffUserResponse.builder()
                        .id(user.getId())
                        .username(user.getUsername())
                        .email(user.getEmail())
                        .roles(user.getRoles().stream().map(r -> r.getName().name()).collect(java.util.stream.Collectors.toSet()))
                        .build())
                .toList();
    }
}

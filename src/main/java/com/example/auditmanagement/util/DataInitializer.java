package com.example.auditmanagement.util;

import com.example.auditmanagement.entity.oracle.Role;
import com.example.auditmanagement.entity.oracle.RoleName;
import com.example.auditmanagement.repository.oracle.RoleRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.boot.CommandLineRunner;
import org.springframework.stereotype.Component;
import org.springframework.transaction.annotation.Transactional;

import java.util.Arrays;

@Component
@RequiredArgsConstructor
public class DataInitializer implements CommandLineRunner {

    private final RoleRepository roleRepository;

    @Override
    @Transactional(transactionManager = "oracleTransactionManager")
    public void run(String... args) {
        Arrays.stream(RoleName.values()).forEach(roleName -> roleRepository.findByName(roleName).orElseGet(() -> {
            Role role = new Role();
            role.setName(roleName);
            return roleRepository.save(role);
        }));
    }
}

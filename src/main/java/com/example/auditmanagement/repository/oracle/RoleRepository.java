package com.example.auditmanagement.repository.oracle;

import com.example.auditmanagement.entity.oracle.Role;
import com.example.auditmanagement.entity.oracle.RoleName;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.Optional;

public interface RoleRepository extends JpaRepository<Role, Long> {
    Optional<Role> findByName(RoleName name);
}

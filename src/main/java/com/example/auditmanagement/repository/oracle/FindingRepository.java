package com.example.auditmanagement.repository.oracle;

import com.example.auditmanagement.entity.oracle.Finding;
import com.example.auditmanagement.entity.oracle.FindingStatus;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;

public interface FindingRepository extends JpaRepository<Finding, Long> {
    Page<Finding> findByStatus(FindingStatus status, Pageable pageable);
}

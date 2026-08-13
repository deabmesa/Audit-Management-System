package com.example.auditmanagement.repository.postgres;

import com.example.auditmanagement.entity.postgres.FindingHistory;
import org.springframework.data.jpa.repository.JpaRepository;

public interface FindingHistoryRepository extends JpaRepository<FindingHistory, Long> {
}

package com.example.auditmanagement.entity.postgres;

import jakarta.persistence.*;
import lombok.Getter;
import lombok.Setter;

import java.time.LocalDateTime;

@Entity
@Table(name = "finding_history")
@Getter
@Setter
public class FindingHistory {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false)
    private Long findingId;

    @Column(nullable = false)
    private String status;

    @Column(length = 2000)
    private String remarks;

    @Column(nullable = false)
    private LocalDateTime changedAt;
}

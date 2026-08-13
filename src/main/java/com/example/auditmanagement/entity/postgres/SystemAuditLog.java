package com.example.auditmanagement.entity.postgres;

import jakarta.persistence.*;
import lombok.Getter;
import lombok.Setter;

import java.time.LocalDateTime;

@Entity
@Table(name = "system_audit_log")
@Getter
@Setter
public class SystemAuditLog {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false)
    private String eventType;

    @Column(nullable = false, length = 3000)
    private String details;

    @Column(nullable = false)
    private String actor;

    @Column(nullable = false)
    private LocalDateTime eventTime;
}

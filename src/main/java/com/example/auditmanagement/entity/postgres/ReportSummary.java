package com.example.auditmanagement.entity.postgres;

import jakarta.persistence.*;
import lombok.Getter;
import lombok.Setter;

@Entity
@Table(name = "report_summary")
@Getter
@Setter
public class ReportSummary {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false)
    private Integer reportYear;

    @Column(nullable = false)
    private Long totalFindings;

    @Column(nullable = false)
    private Long closedFindings;
}

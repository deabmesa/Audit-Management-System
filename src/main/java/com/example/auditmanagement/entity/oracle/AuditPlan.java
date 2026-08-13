package com.example.auditmanagement.entity.oracle;

import com.example.auditmanagement.entity.common.AuditableEntity;
import jakarta.persistence.*;
import lombok.Getter;
import lombok.Setter;
import org.hibernate.annotations.SQLDelete;
import org.hibernate.annotations.Where;

@Entity
@Table(name = "audit_plan")
@Getter
@Setter
@SQLDelete(sql = "UPDATE audit_plan SET deleted = true WHERE id = ?")
@Where(clause = "deleted = false")
public class AuditPlan extends AuditableEntity {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false)
    private Integer auditYear;

    @Column(nullable = false)
    private String auditUniverse;

    @Enumerated(EnumType.STRING)
    @Column(nullable = false)
    private RiskRating riskRating;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "assigned_auditor_id", nullable = false)
    private User assignedAuditor;
}

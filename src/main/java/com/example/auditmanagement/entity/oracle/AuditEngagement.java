package com.example.auditmanagement.entity.oracle;

import com.example.auditmanagement.entity.common.AuditableEntity;
import jakarta.persistence.*;
import lombok.Getter;
import lombok.Setter;
import org.hibernate.annotations.SQLDelete;
import org.hibernate.annotations.Where;

@Entity
@Table(name = "audit_engagement")
@Getter
@Setter
@SQLDelete(sql = "UPDATE audit_engagement SET deleted = true WHERE id = ?")
@Where(clause = "deleted = false")
public class AuditEngagement extends AuditableEntity {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false)
    private String title;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "audit_plan_id", nullable = false)
    private AuditPlan auditPlan;

    @Column(name = "working_paper_url")
    private String workingPaperUrl;
}

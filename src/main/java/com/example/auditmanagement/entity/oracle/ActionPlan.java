package com.example.auditmanagement.entity.oracle;

import com.example.auditmanagement.entity.common.AuditableEntity;
import jakarta.persistence.*;
import lombok.Getter;
import lombok.Setter;
import org.hibernate.annotations.SQLDelete;
import org.hibernate.annotations.Where;

@Entity
@Table(name = "action_plan")
@Getter
@Setter
@SQLDelete(sql = "UPDATE action_plan SET deleted = true WHERE id = ?")
@Where(clause = "deleted = false")
public class ActionPlan extends AuditableEntity {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "finding_id", nullable = false)
    private Finding finding;

    @Column(nullable = false, length = 2000)
    private String remediationAction;

    @Column(name = "evidence_url")
    private String evidenceUrl;
}

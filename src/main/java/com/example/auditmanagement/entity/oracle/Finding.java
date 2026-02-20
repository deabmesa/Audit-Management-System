package com.example.auditmanagement.entity.oracle;

import com.example.auditmanagement.entity.common.AuditableEntity;
import jakarta.persistence.*;
import lombok.Getter;
import lombok.Setter;
import org.hibernate.annotations.SQLDelete;
import org.hibernate.annotations.Where;

import java.time.LocalDate;

@Entity
@Table(name = "finding")
@Getter
@Setter
@SQLDelete(sql = "UPDATE finding SET deleted = true WHERE id = ?")
@Where(clause = "deleted = false")
public class Finding extends AuditableEntity {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "engagement_id", nullable = false)
    private AuditEngagement engagement;

    @Enumerated(EnumType.STRING)
    @Column(nullable = false)
    private RiskRating riskRating;

    @Column(nullable = false, length = 2000)
    private String observation;

    @Column(name = "root_cause", length = 2000)
    private String rootCause;

    @Column(length = 2000)
    private String recommendation;

    @Column(name = "management_response", length = 2000)
    private String managementResponse;

    @Column(name = "target_completion_date")
    private LocalDate targetCompletionDate;

    @Enumerated(EnumType.STRING)
    @Column(nullable = false)
    private FindingStatus status = FindingStatus.OPEN;
}

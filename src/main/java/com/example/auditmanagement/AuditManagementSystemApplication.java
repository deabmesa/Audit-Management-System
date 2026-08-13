package com.example.auditmanagement;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.data.jpa.repository.config.EnableJpaAuditing;

@SpringBootApplication
@EnableJpaAuditing(auditorAwareRef = "auditorAware")
public class AuditManagementSystemApplication {

    public static void main(String[] args) {
        SpringApplication.run(AuditManagementSystemApplication.class, args);
    }
}

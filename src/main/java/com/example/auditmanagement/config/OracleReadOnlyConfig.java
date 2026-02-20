package com.example.auditmanagement.config;

import jakarta.persistence.EntityManagerFactory;
import org.springframework.beans.factory.annotation.Qualifier;
import org.springframework.boot.autoconfigure.orm.jpa.JpaProperties;
import org.springframework.boot.context.properties.ConfigurationProperties;
import org.springframework.boot.jdbc.DataSourceBuilder;
import org.springframework.boot.orm.jpa.EntityManagerFactoryBuilder;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.data.jpa.repository.config.EnableJpaRepositories;
import org.springframework.orm.jpa.JpaTransactionManager;
import org.springframework.orm.jpa.LocalContainerEntityManagerFactoryBean;
import org.springframework.transaction.PlatformTransactionManager;

import javax.sql.DataSource;
import java.util.HashMap;
import java.util.Map;

@Configuration
@EnableJpaRepositories(
        basePackages = "com.example.auditmanagement.repository.oracle.readonly",
        entityManagerFactoryRef = "oracleReadOnlyEntityManagerFactory",
        transactionManagerRef = "oracleReadOnlyTransactionManager"
)
public class OracleReadOnlyConfig {

    @Bean
    @ConfigurationProperties(prefix = "app.datasource.oracle-read-only")
    public DataSource oracleReadOnlyDataSource() {
        return DataSourceBuilder.create().build();
    }

    @Bean
    public LocalContainerEntityManagerFactoryBean oracleReadOnlyEntityManagerFactory(
            EntityManagerFactoryBuilder builder,
            @Qualifier("oracleReadOnlyDataSource") DataSource dataSource,
            JpaProperties jpaProperties
    ) {
        Map<String, Object> properties = new HashMap<>(jpaProperties.getProperties());
        properties.put("hibernate.hbm2ddl.auto", "none");
        properties.put("hibernate.dialect", "org.hibernate.dialect.OracleDialect");
        properties.put("hibernate.default_schema", "AUDIT_APP");

        return builder
                .dataSource(dataSource)
                .packages("com.example.auditmanagement.entity.oracle", "com.example.auditmanagement.entity.common")
                .properties(properties)
                .persistenceUnit("oracleReadOnlyPersistenceUnit")
                .build();
    }

    @Bean
    public PlatformTransactionManager oracleReadOnlyTransactionManager(
            @Qualifier("oracleReadOnlyEntityManagerFactory") EntityManagerFactory entityManagerFactory
    ) {
        JpaTransactionManager transactionManager = new JpaTransactionManager(entityManagerFactory);
        transactionManager.setDefaultTimeout(30);
        return transactionManager;
    }
}

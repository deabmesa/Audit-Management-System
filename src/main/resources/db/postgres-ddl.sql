CREATE TABLE system_audit_log (
    id BIGSERIAL PRIMARY KEY,
    event_type VARCHAR(100) NOT NULL,
    details TEXT NOT NULL,
    actor VARCHAR(100) NOT NULL,
    event_time TIMESTAMP NOT NULL
);

CREATE TABLE user_activity_log (
    id BIGSERIAL PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    action VARCHAR(200) NOT NULL,
    action_time TIMESTAMP NOT NULL
);

CREATE TABLE finding_history (
    id BIGSERIAL PRIMARY KEY,
    finding_id BIGINT NOT NULL,
    status VARCHAR(20) NOT NULL,
    remarks TEXT,
    changed_at TIMESTAMP NOT NULL
);

CREATE TABLE report_summary (
    id BIGSERIAL PRIMARY KEY,
    report_year INT NOT NULL,
    total_findings BIGINT NOT NULL,
    closed_findings BIGINT NOT NULL
);

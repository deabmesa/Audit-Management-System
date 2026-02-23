CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'viewer',
    remember_token VARCHAR(100),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE checkin_logs (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    username VARCHAR(255) NOT NULL,
    ip_address VARCHAR(64) NOT NULL,
    machine_name VARCHAR(255),
    checked_in_at TIMESTAMP NOT NULL,
    checked_out_at TIMESTAMP NULL,
    session_id VARCHAR(255) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE(user_id, session_id)
);

CREATE TABLE audit_engagements (
    id BIGSERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    entity_name VARCHAR(255) NOT NULL,
    activity TEXT NOT NULL,
    working_notes TEXT,
    evidence_path VARCHAR(255),
    status VARCHAR(20) NOT NULL CHECK (status IN ('open','in-progress','closed')),
    start_date DATE NOT NULL,
    end_date DATE,
    created_by BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

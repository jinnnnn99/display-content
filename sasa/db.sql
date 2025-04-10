-- PostgreSQL 쿼리로 실행
CREATE TABLE IF NOT EXISTS file_data (
    id SERIAL PRIMARY KEY,
    file_bundle_id VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    file_size INT NOT NULL,
    file_extension VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

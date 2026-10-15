CREATE TABLE IF NOT EXISTS rate_limits (
    id VARCHAR(64) PRIMARY KEY,
    attempts INT DEFAULT 1,
    reset_at INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

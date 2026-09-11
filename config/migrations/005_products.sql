CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    summary TEXT,
    description TEXT,
    price INT NOT NULL DEFAULT 0,
    type VARCHAR(50) DEFAULT 'plugin',
    version VARCHAR(50) DEFAULT '1.0.0',
    file_path VARCHAR(255),
    license_type VARCHAR(50) DEFAULT 'lifetime',
    max_domains INT DEFAULT 1,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

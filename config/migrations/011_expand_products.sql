CREATE TABLE IF NOT EXISTS product_versions (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id INT UNSIGNED NOT NULL,
    version VARCHAR(50) NOT NULL,
    original_file_path VARCHAR(255),
    protected_file_path VARCHAR(255),
    changelog TEXT,
    release_notes TEXT,
    is_current TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

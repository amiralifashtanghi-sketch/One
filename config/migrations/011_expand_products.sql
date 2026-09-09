ALTER TABLE products ADD COLUMN product_code VARCHAR(100);
ALTER TABLE products ADD COLUMN product_type VARCHAR(50) DEFAULT 'wordpress-plugin';
ALTER TABLE products ADD COLUMN short_description TEXT;
ALTER TABLE products ADD COLUMN sale_price INTEGER DEFAULT NULL;
ALTER TABLE products ADD COLUMN license_duration_days INTEGER DEFAULT 365;
ALTER TABLE products ADD COLUMN update_policy VARCHAR(50) DEFAULT 'all';
ALTER TABLE products ADD COLUMN max_activations INTEGER DEFAULT 1;
ALTER TABLE products ADD COLUMN allowed_domains TEXT;
ALTER TABLE products ADD COLUMN protected_file_path VARCHAR(255);
ALTER TABLE products ADD COLUMN release_notes TEXT;
ALTER TABLE products ADD COLUMN changelog TEXT;
ALTER TABLE products ADD COLUMN requirements TEXT;
ALTER TABLE products ADD COLUMN wp_version VARCHAR(20) DEFAULT '6.0';
ALTER TABLE products ADD COLUMN php_version VARCHAR(20) DEFAULT '8.2';
ALTER TABLE products ADD COLUMN author VARCHAR(100) DEFAULT 'EAFD';
ALTER TABLE products ADD COLUMN updated_at DATETIME;

CREATE TABLE IF NOT EXISTS product_versions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL,
    version VARCHAR(50) NOT NULL,
    original_file_path VARCHAR(255),
    protected_file_path VARCHAR(255),
    changelog TEXT,
    release_notes TEXT,
    is_current INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    summary TEXT,
    description TEXT,
    price INTEGER NOT NULL DEFAULT 0,
    type VARCHAR(50) DEFAULT 'plugin',
    version VARCHAR(50) DEFAULT '1.0.0',
    file_path VARCHAR(255),
    license_type VARCHAR(50) DEFAULT 'lifetime',
    max_domains INTEGER DEFAULT 1,
    is_active INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

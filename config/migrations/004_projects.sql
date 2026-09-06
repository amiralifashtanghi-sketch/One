CREATE TABLE IF NOT EXISTS projects (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    summary TEXT,
    client_name VARCHAR(255),
    technologies VARCHAR(255),
    challenge TEXT,
    solution TEXT,
    results TEXT,
    image_url VARCHAR(255),
    project_url VARCHAR(255),
    sort_order INTEGER DEFAULT 0,
    is_active INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

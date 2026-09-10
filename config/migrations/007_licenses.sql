CREATE TABLE IF NOT EXISTS licenses (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    order_id INT UNSIGNED NOT NULL,
    license_key VARCHAR(100) UNIQUE NOT NULL,
    license_type VARCHAR(50) DEFAULT 'lifetime',
    max_activations INT DEFAULT 1,
    max_domains INT DEFAULT 1,
    status VARCHAR(50) DEFAULT 'active',
    expires_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS license_activations (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    license_id INT UNSIGNED NOT NULL,
    domain VARCHAR(255) NOT NULL,
    ip_address VARCHAR(50),
    activated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_check_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (license_id) REFERENCES licenses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

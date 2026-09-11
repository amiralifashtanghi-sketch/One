<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model
{
    protected string $table = 'products';

    public static function normalizeSlug(string $title): string
    {
        $slug = trim($title);
        $slug = mb_strtolower($slug, 'UTF-8');
        // Replace spaces, underscores, and extra hyphens with a single hyphen
        $slug = preg_replace('/[\s\_]+/u', '-', $slug);
        $slug = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $slug);
        $slug = preg_replace('/\-+/u', '-', $slug);
        return trim($slug, '-');
    }

    public function findBySlug(string $slug): array|false
    {
        return $this->findBy('slug', $slug);
    }

    public function getActiveProducts(): array
    {
        return $this->where('is_active', 1);
    }

    public function addVersion(int $productId, string $version, ?string $originalFile, ?string $protectedFile, ?string $changelog = null, ?string $releaseNotes = null): int
    {
        // Unmark previous current versions
        \App\Core\Database::query("UPDATE product_versions SET is_current = 0 WHERE product_id = ?", [$productId]);

        $sql = "INSERT INTO product_versions (product_id, version, original_file_path, protected_file_path, changelog, release_notes, is_current, created_at) VALUES (?, ?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP)";
        \App\Core\Database::query($sql, [$productId, $version, $originalFile, $protectedFile, $changelog, $releaseNotes]);

        return (int)\App\Core\Database::lastInsertId();
    }

    public function getVersions(int $productId): array
    {
        return \App\Core\Database::fetchAll("SELECT * FROM product_versions WHERE product_id = ? ORDER BY id DESC", [$productId]);
    }

    public function getCurrentVersion(int $productId): array|false
    {
        return \App\Core\Database::fetch("SELECT * FROM product_versions WHERE product_id = ? AND is_current = 1 LIMIT 1", [$productId]);
    }
}

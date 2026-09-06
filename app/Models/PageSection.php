<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class PageSection extends Model
{
    protected string $table = 'page_sections';

    public function getSectionsForPage(int $pageId): array
    {
        return Database::fetchAll(
            "SELECT * FROM {$this->table} WHERE page_id = ? ORDER BY sort_order ASC",
            [$pageId]
        );
    }

    public function createRevision(int $pageId, array $sectionsData): void
    {
        Database::query(
            "INSERT INTO page_revisions (page_id, data, created_at) VALUES (?, ?, ?)",
            [$pageId, json_encode($sectionsData, JSON_UNESCAPED_UNICODE), date('Y-m-d H:i:s')]
        );
    }

    public function getRevisions(int $pageId): array
    {
        return Database::fetchAll(
            "SELECT * FROM page_revisions WHERE page_id = ? ORDER BY created_at DESC",
            [$pageId]
        );
    }
}

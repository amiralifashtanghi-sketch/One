<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Page extends Model
{
    protected string $table = 'pages';

    public function getBySlug(string $slug): array|false
    {
        return Database::fetch("SELECT * FROM {$this->table} WHERE slug = ? AND is_published = 1 LIMIT 1", [$slug]);
    }
}

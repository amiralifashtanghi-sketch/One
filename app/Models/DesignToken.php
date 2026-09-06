<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class DesignToken extends Model
{
    protected string $table = 'design_tokens';

    public function getAllTokens(): array
    {
        $rows = Database::fetchAll("SELECT * FROM {$this->table}");
        $tokens = [];
        foreach ($rows as $row) {
            $tokens[$row['token_key']] = $row['token_value'];
        }
        return $tokens;
    }

    public function setToken(string $key, string $value, string $category = 'general'): void
    {
        $existing = Database::fetch("SELECT * FROM {$this->table} WHERE token_key = ?", [$key]);
        if ($existing) {
            Database::query("UPDATE {$this->table} SET token_value = ?, updated_at = ? WHERE token_key = ?", [$value, date('Y-m-d H:i:s'), $key]);
        } else {
            Database::query("INSERT INTO {$this->table} (token_key, token_value, category, updated_at) VALUES (?, ?, ?, ?)", [$key, $value, $category, date('Y-m-d H:i:s')]);
        }
    }
}

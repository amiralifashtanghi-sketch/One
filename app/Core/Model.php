<?php

namespace App\Core;

abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';

    public function all(): array
    {
        return Database::fetchAll("SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} DESC");
    }

    public function find(mixed $id): array|false
    {
        return Database::fetch("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1", [$id]);
    }

    public function findBy(string $column, mixed $value): array|false
    {
        return Database::fetch("SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1", [$value]);
    }

    public function where(string $column, mixed $value): array
    {
        return Database::fetchAll("SELECT * FROM {$this->table} WHERE {$column} = ?", [$value]);
    }

    public function create(array $data): string|false
    {
        $fields = array_keys($data);
        $placeholders = implode(', ', array_fill(0, count($fields), '?'));
        $columns = implode(', ', $fields);

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        Database::query($sql, array_values($data));

        return Database::lastInsertId();
    }

    public function update(mixed $id, array $data): bool
    {
        $fields = [];
        $values = [];

        foreach ($data as $column => $value) {
            $fields[] = "{$column} = ?";
            $values[] = $value;
        }

        $values[] = $id;
        $setClause = implode(', ', $fields);
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?";

        return (bool)Database::query($sql, $values);
    }

    public function delete(mixed $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return (bool)Database::query($sql, [$id]);
    }
}

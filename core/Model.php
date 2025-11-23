<?php

namespace Core;

use Core\Databases\Database;
use PDO;
use PDOStatement;

abstract class Model
{
    protected string $table;

    protected string $primaryKey = 'id';

    protected PDO $connection;

    protected array $wheres = [];

    protected array $bindings = [];

    protected array $orders = [];

    protected ?int $limit = null;

    protected ?int $offset = null;

    protected array $fillable = [];

    protected array $attributes = [];

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->attributes[$key] = $value;
            }
        }
        return $this;
    }

    public static function query(): static
    {
        return new static();
    }

    public function where(string $column, string $operator = '=', mixed $value = null): self
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        $this->wheres[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;

        return $this; 
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orders[] = "{$column} {$direction}";

        return $this;
    }

    public function take(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        $stmt = $this->runQuery($sql);
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($result['total'] ?? 0);
    }

    public function get(): array
    {
        $sql = $this->compileSql();

        $stmt = $this->runQuery($sql);
        
        return $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public function skip(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function paginate(int $page = 1, ?int $perPage = null): array
    {
        $page = max(1, $page);
        $perPage = $perPage ?? env('PAGINATION_PER_PAGE', 10);

        $countQuery = clone $this;
        $total = $countQuery->count();

        $lastPage = (int) ceil($total / $perPage);
        
        if ($lastPage > 0 && $page > $lastPage) {
            $page = $lastPage;
        }

        $offset = ($page - 1) * $perPage;

        $this->take($perPage)->skip($offset);
        $items = $this->get();

        return [
            'data' => $items,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => $lastPage,
            'from' => $total === 0 ? 0 : $offset + 1,
            'to' => $total === 0 ? 0 : min($offset + $perPage, $total),
            'prev_page' => ($page > 1) ? $page - 1 : null,
            'next_page' => ($page < $lastPage) ? $page + 1 : null,
        ];
    }

    public function first(): static|null
    {
        $this->limit = 1;

        $sql = $this->compileSql();

        $stmt = $this->runQuery($sql);

        $result = $stmt->fetchObject(static::class);

        return $result ?: null;
    }

    public static function create(array $attributes): static|bool
    {
        $model = new static();
        $model->fill($attributes);

        $columns = implode(', ', array_keys($model->attributes));
        $placeholders = implode(', ', array_fill(0, count($model->attributes), '?'));

        $sql = "INSERT INTO " . $model->table . " ({$columns}) VALUES ({$placeholders})";

        $model->bindings = array_values($model->attributes);

        try {   
            $model->runQuery($sql);
            
            $id = $model->connection->lastInsertId();
            
            return $model->query()->where($model->primaryKey, $id)->first();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function update(array $attributes): bool
    {
        $this->fill($attributes);

        $setClauses = array_map(fn($key) => "{$key} = ?", array_keys($this->attributes));

        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClauses);

        $bindings = array_values($this->attributes);

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        $this->bindings = array_merge($bindings, $this->bindings);

        try {
            $this->runQuery($sql);
            
            return true;
        } catch (\PDOException $e) {
            if (function_exists('dd')) {
                error_log($e->getMessage());
            }
            return false;
        }
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        try {
            $this->runQuery($sql);
            
            return true;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    protected function compileSql(): string
    {
        $sql = "SELECT * FROM {$this->table}";

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        if (!empty($this->orders)) {
            $sql .= " ORDER BY " . implode(', ', $this->orders);
        }

        if (!empty($this->limit)) {
            $sql .= " LIMIT " . $this->limit;
        }

        if (!empty($this->offset)) {
            $sql .= " OFFSET " . $this->offset;
        }

        return $sql;
    }

    protected function runQuery($sql): PDOStatement
    {
        $stmt = $this->connection->prepare($sql);

        $stmt->execute($this->bindings);
        
        $this->resetQuery();

        return $stmt;
    }

    private function resetQuery(): void
    {
        $this->wheres = [];

        $this->bindings = [];

        $this->orders = [];

        $this->limit = null;
    }

    public function __sleep()
    {
        $props = array_keys(get_object_vars($this));

        $propsToRemove = ['db', 'pdo', 'connection'];
        
        return array_diff($props, $propsToRemove);
    }
}
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

    public function where(string $column, string $operator, mixed $value = null): self
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

    public function get(): array
    {
        $sql = $this->compileSql();

        $stmt = $this->runQuery($sql);
        
        return $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public function first(): static|null
    {
        $this->limit = 1;

        $sql = $this->compileSql();

        $stmt = $this->runQuery($sql);

        return $stmt->fetchObject(static::class);
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
            dd($e->getMessage());
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
}
<?php

declare(strict_types=1);

use Core\Databases\Migration;

class CreateProductsTable extends Migration
{
    public function up(): bool
    {
        $sql = "CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            price DECIMAL(10, 3) NOT NULL,
            quantity_available INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        return $this->execute($sql);
    }

    public function down(): bool
    {
        return $this->execute("DROP TABLE IF EXISTS products");
    }
}
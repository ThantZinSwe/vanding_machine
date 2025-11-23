<?php

declare(strict_types=1);

use Core\Databases\Database;

// Adjust path to autoload based on file location (databases/Seed.php)
require_once __DIR__ . '/../vendor/autoload.php';

class Seed
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function run(): void
    {
        try {
            $this->seedUsers();
            $this->seedProducts();
            $this->seedTransactions();

            echo "Seeding completed successfully.\n";
        } catch (\Exception $e) {
            echo "Seeding failed: " . $e->getMessage() . "\n";
        }
    }

    protected function seedUsers(): void
    {
        echo "Seeding Users...\n";

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute(['admin@example.com']);
        
        if ($stmt->fetchColumn() == 0) {
            $password = password_hash('password123', PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (name, email, password, role) VALUES 
                    ('Admin', 'admin@admin.com', '$password', 'admin')";
            $this->db->exec($sql);
        }

        $users = [
            ['User1', 'user1@user.com'],
            ['User2', 'user2@user.com'],
        ];

        $stmt = $this->db->prepare("INSERT IGNORE INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
        $defaultPass = password_hash('password123', PASSWORD_DEFAULT);

        foreach ($users as $user) {
            $stmt->execute([$user[0], $user[1], $defaultPass]);
        }
    }

    protected function seedProducts(): void
    {
        echo "Seeding Products...\n";

        $products = [
            ['Cola', 1.50, 20],
            ['Pepsi', 2.00, 15],
            ['Water', 1.00, 50],
            ['Sprite', 1.25, 30],
            ['Coke', 3.00, 10],
        ];

        $stmt = $this->db->prepare("INSERT INTO products (name, price, quantity_available) VALUES (?, ?, ?)");

        foreach ($products as $product) {
            $check = $this->db->prepare("SELECT id FROM products WHERE name = ?");
            $check->execute([$product[0]]);
            
            if (!$check->fetch()) {
                $stmt->execute($product);
            }
        }
    }

    protected function seedTransactions(): void
    {
        echo "Seeding Transactions...\n";

        $userIds = $this->db->query("SELECT id FROM users WHERE role = 'user'")->fetchAll(PDO::FETCH_COLUMN);
        $products = $this->db->query("SELECT id, price FROM products")->fetchAll(PDO::FETCH_ASSOC);

        if (empty($userIds) || empty($products)) {
            echo " - Skipping transactions: Users or Products missing.\n";
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO transactions (user_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");

        for ($i = 0; $i < 10; $i++) {
            $userId = $userIds[array_rand($userIds)];
            $product = $products[array_rand($products)];
            
            $qty = rand(1, 5);
            $totalPrice = $qty * $product['price'];

            $stmt->execute([
                $userId,
                $product['id'],
                $qty,
                $totalPrice
            ]);
        }
    }
}

(new Seed())->run();
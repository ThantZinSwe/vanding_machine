<?php

declare(strict_types=1);

use Core\Databases\Database;
use Core\Databases\Migration;

require_once __DIR__ . '/../vendor/autoload.php';

class Migrator
{
    protected PDO $connection;

    protected string $migrationsPath;

    protected string $migrationsTable;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
        
        Migration::setConnectionResolver($this->connection);

        $this->migrationsPath = base_path('databases/migrations');
    }

    public function migrate()
    {
        try {
            $files = $this->getFiles();

            foreach ($files as $file) {
                echo "Running migration: $file\n";

                $this->run($file);

                echo "Migrated: $file\n";
            }

            echo "Migration completed successfully.\n";
        } catch (\Exception $e) {
            echo "Migration failed: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    protected function getFiles()
    {
        $files = [];
        $dir = new DirectoryIterator($this->migrationsPath);

        foreach ($dir as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getBasename('.php');
            }
        }

        sort($files);
        
        return $files;
    }

    protected function run($migration)
    {
        $path = $this->migrationsPath . DIRECTORY_SEPARATOR . $migration . '.php';

        if (!file_exists($path)) {
            throw new \Exception("Migration file not found: " . $path);
        }

        $contents = file_get_contents($path);

        if (preg_match('/class\s+([^\s]+)/', $contents, $matches)) {
            $className = $matches[1];

            require_once $path;

            if (class_exists($className)) {
                $instance = new $className();
                $instance->up();
            } else {
                throw new \Exception("Migration class not found: " . $className);
            }
        } else {
            throw new \Exception("Could not find migration class in file: " . $path);
        }
    }
}

(new Migrator())->migrate();
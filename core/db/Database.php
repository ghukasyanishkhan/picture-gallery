<?php

namespace app\core\db;

use app\core\Application;

class Database
{

    public \PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];

        $files = scandir(Application::$ROOT_DIR . '/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach ($toApplyMigrations as $migration) {
            if ($migration === '..' || $migration === '.') {
                continue;
            }

            require_once Application::$ROOT_DIR . '/migrations/' . $migration;

            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $this->log("applying migration $migration") . PHP_EOL;
            $instance->up();
            echo "applied migration $migration" . PHP_EOL;
            $newMigrations[] = $migration;
        }
        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log('All migrations are applied') . PHP_EOL;
        }
    }


    public function createMigrationsTable()
    {
        $query = "CREATE TABLE IF NOT EXISTS migrations (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    migration VARCHAR(255),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                  )ENGINE=INNODB;";
        $this->pdo->exec($query);
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $newMigrations)
    {
        $str = implode(",", array_map(fn($m) => "('$m')", $newMigrations));

        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
        $statement->execute();
    }

    public function log($message)
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message;
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

}
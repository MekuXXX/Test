<?php

declare(strict_types=1);

class Database
{
  public \PDO $pdo;
  private string $dns;
  private string $user;
  private string $password;
  private string | null $migrationPath;

  public function __construct(protected array $db)
  {
    $this->dns = "{$db['driver']}:host={$db['host']};port={$db['port']};dbname={$db['database']}";
    $this->user = $db['user'];
    $this->password = $db['password'];
    $this->migrationPath = $db['migration'] ?? null;

    $this->pdo = new \PDO($this->dns, $this->user, $this->password);
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  }

  public function applyMigrations()
  {
    if (!$this->migrationPath) {
      throw new Exception("Must specify the migration path");
    }

    echo "Starting migrations" . PHP_EOL;
    $this->createMigrationsTable();
    $appliedMigrations = $this->getAppliedMigrations();
    $newMigrations = [];
    $files = scandir($this->migrationPath);
    $toApplyMigrations = array_diff($files, $appliedMigrations);

    foreach ($toApplyMigrations as $migrationFile) {
      if ($migrationFile === '.' || $migrationFile === '..') {
        continue;
      }

      require_once "{$this->migrationPath}/{$migrationFile}";
      $className = pathinfo($migrationFile, PATHINFO_FILENAME);

      /** @var object $instance */
      $instance = new $className();

      if (method_exists($instance, 'up')) {
        $instance->up();
        array_push($newMigrations, $migrationFile);
      } else {
        echo "Method 'up' not found in class $className" . PHP_EOL;
      }
    }

    if (!empty($newMigrations)) {
      $this->saveMigrations($newMigrations);
    }

    echo "Ending migrations" . PHP_EOL;
  }

  public function dropMigrations(int $depth = 0): void
  {
    if (!$this->migrationPath) {
      throw new Exception("Must specify the migration path");
    }

    echo "Starting to delete migrations" . PHP_EOL;

    $appliedMigrations = $this->getAppliedMigrations();
    $deletedMigrations = [];

    if ($depth === 0) {
      $migrationsToDelete = $appliedMigrations;  // Delete all migrations
    } else {
      $migrationsToDelete = array_slice($appliedMigrations, -$depth);
    }

    foreach (array_reverse($migrationsToDelete) as $migrationFile) {
      require_once "{$this->migrationPath}/{$migrationFile}";
      $className = pathinfo($migrationFile, PATHINFO_FILENAME);

      /** @var object $instance */
      $instance = new $className();

      if (method_exists($instance, 'down')) {
        $instance->down();
        echo "Rolled back migration: $migrationFile" . PHP_EOL;
      } else {
        echo "Method 'down' not found in class $className" . PHP_EOL;
      }

      $deletedMigrations[] = $migrationFile;
    }

    if (!empty($deletedMigrations)) {
      $this->removeMigrationsFromDatabase($deletedMigrations);
    }

    echo "Finished deleting migrations" . PHP_EOL;
  }

  protected function removeMigrationsFromDatabase(array $migrations): void
  {
    $placeholders = implode(",", array_fill(0, count($migrations), "?"));
    $sql = "DELETE FROM migrations WHERE migration IN ($placeholders)";

    $statement = $this->pdo->prepare($sql);
    $statement->execute($migrations);

    echo "Deleted migrations from database: " . implode(", ", $migrations) . PHP_EOL;
  }

  protected function getAppliedMigrations(): array
  {
    $appliedMigrations = $this->pdo->prepare('SELECT migration FROM migrations ORDER BY created_at ASC');
    $appliedMigrations->execute();

    return $appliedMigrations->fetchAll(\PDO::FETCH_COLUMN);
  }

  protected function saveMigrations(array $migrations): void
  {
    $dbMigrations = implode(",", array_map(fn($m) => "('$m')", $migrations));
    $pdoStatement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $dbMigrations");
    $pdoStatement->execute();
  }

  protected function createMigrationsTable()
  {
    $driver = explode(":", $this->dns)[0];
    if ($driver === "mysql") {
      $this->pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  
        ENGINE=INNODB;");
    } else {
      throw new Exception("Unknown driver");
    }
  }
}

<?php

declare(strict_types=1);

class Database
{
  public \PDO $pdo;
  private string $dns;
  private string $user;
  private string $password;

  public function __construct(protected array $db)
  {
    $this->dns = "{$db['driver']}:host={$db['host']};port={$db['port']};dbname={$db['database']}";
    $this->user = $db['user'];
    $this->password = $db['password'];

    $this->pdo = new \PDO($this->dns, $this->user, $this->password);
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  }

  public function applyMigrations(string $folderPath)
  {
    echo "Starting migrations" . PHP_EOL;
    $this->createMigrationsTable();
    $appliedMigrations = $this->getAppliedMigrations();
    $newMigrations = [];
    $files = scandir($folderPath);
    $toApplyMigrations = array_diff($files, $appliedMigrations);

    foreach ($toApplyMigrations as $migrationFile) {
      if ($migrationFile === '.' || $migrationFile === '..') {
        continue;
      }

      require_once "{$folderPath}/{$migrationFile}";
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

  protected function getAppliedMigrations(): array
  {
    $appliedMigrations = $this->pdo->prepare('SELECT migration FROM migrations');
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
          migration VARCHAR(255),
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  
        ENGINE=INNODB;");
    } else {
      throw new Exception("Unknown driver");
    }
  }
}

<?php

declare(strict_types=1);
require_once __DIR__ . '/Core/Env.php';
require_once __DIR__ . '/Core/Database.php';

new Env(__DIR__ . '/.env');

$db = new Database([
  'user' => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASSWORD'],
  'driver' => $_ENV['DB_DRIVER'],
  'host' => $_ENV['DB_HOST'],
  'port' => $_ENV['DB_PORT'],
  'database' => $_ENV['DB_DATABASE'],
]);

function tableExists(string $tableName): bool
{
  global $db;

  try {
    $stmt = $db->pdo->query("SHOW TABLES LIKE '$tableName'");
    return $stmt->fetch() !== false;
  } catch (\PDOException $e) {
    throw new Exception("Error checking if table exists: " . $e->getMessage() . "\n");
  }
}

function insertData(string $tableName, string $filePath)
{
  global $db;

  if (!file_exists($filePath)) {
    throw new Exception("Error: No file exist in this path ('{$filePath}')\n");
  }

  if (pathinfo($filePath, PATHINFO_EXTENSION) !== 'json') {
    throw new Exception("Error: The file '{$filePath}' is not a JSON file.\n");
  }

  $jsonData = file_get_contents($filePath);
  $data = json_decode($jsonData, true);

  if (json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception("Error: The file '{$filePath}' contains invalid JSON data.\n");
  }

  if (!tableExists($tableName)) {
    throw new Exception("Error: Table name ('$tableName') doesn't exist in database ('{$_ENV['DB_DATABASE']}').\n");
  }

  if (is_object($data[0])) {
    $data = array_map(function ($item) {
      return (array) $item;
    }, $data);
  }

  $columns = implode("`, `", array_keys($data[0]));

  $values = [];
  foreach ($data as $row) {
    $escapedRow = array_map(function ($value) {
      return is_string($value) ? addslashes($value) : $value; // Escape strings
    }, $row);

    $values[] = "('" . implode("', '", $escapedRow) . "')";
  }

  $sql = "INSERT INTO `$tableName` (`$columns`) VALUES " . implode(", ", $values) . ";";

  try {
    $db->pdo->exec($sql);
    echo "Data inserted successfully into the $tableName table.\n";
  } catch (\PDOException $e) {
    throw new Exception("Error inserting data: " . $e->getMessage() . "\n");
  }
}

insertData("categories", __DIR__ . "../data/categories.json");
insertData("courses", __DIR__ . "../data/course_list.json");

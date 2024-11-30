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

function readJson(string $filePath): array
{
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
  return $data;
}

function insertData(string $tableName, array $data)
{
  global $db;


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

// Note: Got error when trying to add parent id as foriegn key even if categories sorted and inserted one by one
// function sortCategories(array $data): array
// {
//   $map = [];
//   foreach ($data as $category) {
//     $parentId = $category['parent'];
//     if (!isset($map[$parentId])) {
//       $map[$parentId] = [];
//     }
//     $map[$parentId][] = $category;
//   }

//   $sorted = [];
//   $buildList = function ($parentId) use (&$map, &$buildList, &$sorted) {
//     if (isset($map[$parentId])) {
//       foreach ($map[$parentId] as $category) {
//         $category['parent_id'] = $parentId;
//         unset($category['parent']);
//         $sorted[] = $category;
//         $buildList($category['id']);
//       }
//     }
//   };

//   $buildList(null);

//   return $sorted;
// }


// function insertData(string $tableName, array $data)
// {
//   global $db;

//   if (!tableExists($tableName)) {
//     throw new Exception("Error: Table name ('$tableName') doesn't exist in database ('{$_ENV['DB_DATABASE']}').\n");
//   }

//   if (is_object($data[0])) {
//     $data = array_map(function ($item) {
//       return (array) $item;
//     }, $data);
//   }

//   foreach ($data as $row) {
//     $columns = implode("`, `", array_keys($row));
//     $escapedRow = array_map(function ($value) {
//       return is_string($value) ? addslashes($value) : $value; // Escape strings
//     }, $row);

//     $values = "'" . implode("', '", $escapedRow) . "'";
//     $sql = "INSERT INTO `$tableName` (`$columns`) VALUES ($values);";

//     try {
//       $db->pdo->exec($sql);
//       echo "Row inserted successfully into the $tableName table.\n";
//     } catch (\PDOException $e) {
//       throw new Exception("Error inserting data: " . $e->getMessage() . "\n");
//     }
//   }
// }

$categoriesData = readJson(__DIR__ . "/../data/categories.json");
$categoriesData = array_map(function ($category) {
  $category['parent_id'] = $category['parent'];
  unset($category['parent']);
  return $category;
}, $categoriesData);
insertData("categories", $categoriesData);

$coursesData = readJson(__DIR__ . "/../data/course_list.json");
$coursesData = array_map(function ($course) {
  $course['id'] = $course['course_id'];
  unset($course['course_id']);

  $course['name'] = $course['title'];
  unset($course['title']);

  $course['preview'] = $course['image_preview'];
  unset($course['image_preview']);

  return $course;
}, $coursesData);
insertData("courses", $coursesData);
<?php

declare(strict_types=1);
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Application.php';

class Categories
{

  public function getAll(Request $req, Response $res)
  {
    $statement = Application::$app->db->pdo->query("SELECT * FROM categories;");
    $categories = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($categories as &$category) {
      $categoryId = $category['id'];

      $courseCountStatement = Application::$app->db->pdo->prepare("
            WITH RECURSIVE CategoryTree AS (
                SELECT id
                FROM categories
                WHERE id = :id
                UNION ALL
                SELECT c.id
                FROM categories c
                INNER JOIN CategoryTree ct ON c.parent_id = ct.id
            )
            SELECT COUNT(*) AS course_count
            FROM courses
            WHERE category_id IN (SELECT id FROM CategoryTree);
        ");
      $courseCountStatement->bindValue(':id', $categoryId, PDO::PARAM_STR);
      $courseCountStatement->execute();
      $category['count_of_courses'] = $courseCountStatement->fetchColumn();
    }

    return $res->setJsonBody([
      'success' => true,
      'message' => "All Categories have been successfully retrieved",
      'data' => [
        'categories' => $categories
      ]
    ])->send();
  }

  public function get(Request $req, Response $res, array $params)
  {
    $categoryId = $params['id'];

    $categoryStatement = Application::$app->db->pdo->prepare("SELECT * FROM categories WHERE id = :id");
    $categoryStatement->bindValue(':id', $categoryId, PDO::PARAM_STR);
    $categoryStatement->execute();
    $category = $categoryStatement->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
      throw new NotFoundException("Course is not found");
    }

    $courseCountStatement = Application::$app->db->pdo->prepare("
        WITH RECURSIVE CategoryTree AS (
            SELECT id
            FROM categories
            WHERE id = :id
            UNION ALL
            SELECT c.id
            FROM categories c
            INNER JOIN CategoryTree ct ON c.parent_id = ct.id
        )
        SELECT COUNT(*) AS course_count
        FROM courses
        WHERE category_id IN (SELECT id FROM CategoryTree);
    ");
    $courseCountStatement->bindValue(':id', $categoryId, PDO::PARAM_STR);
    $courseCountStatement->execute();
    $courseCount = $courseCountStatement->fetchColumn();


    $category['count_of_courses'] = $courseCount;

    return $res->setJsonBody([
      'success' => true,
      'message' => "Category and course count retrieved successfully.",
      'data' => [
        'category' => $category,
      ]
    ])->send();
  }
}

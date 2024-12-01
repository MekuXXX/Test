<?php

declare(strict_types=1);
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';

class Courses
{
  public function getAll(Request $req, Response $res)
  {
    $categoryId = $req->getQueryParam('category_id');

    if ($categoryId) {
      $categoryCheckStmt = Application::$app->db->pdo->prepare("SELECT COUNT(*) FROM categories WHERE id = :id");
      $categoryCheckStmt->bindValue(':id', $categoryId, PDO::PARAM_STR);
      $categoryCheckStmt->execute();
      $categoryExists = $categoryCheckStmt->fetchColumn();

      if (!$categoryExists) {
        throw new NotFoundException("Category with ID {$categoryId} is not found.");
      }

      $statement = Application::$app->db->pdo->prepare("
            WITH RECURSIVE CategoryTree AS (
                SELECT id
                FROM categories
                WHERE id = :id
                UNION ALL
                SELECT c.id
                FROM categories c
                INNER JOIN CategoryTree ct ON c.parent_id = ct.id
            )
            SELECT * 
            FROM courses
            WHERE category_id IN (SELECT id FROM CategoryTree);
        ");
      $statement->bindValue(':id', $categoryId, PDO::PARAM_STR);
      $statement->execute();
      $courses = $statement->fetchAll(PDO::FETCH_ASSOC);
    } else {
      $statement = Application::$app->db->pdo->query("SELECT * FROM courses;");
      $courses = $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    return $res->setJsonBody([
      'success' => true,
      'message' => "Courses have been successfully retrieved",
      'data' => [
        'courses' => $courses
      ]
    ])->send();
  }

  public function get(Request $req, Response $res, array $params)
  {
    $courseId = $params['id'];

    $courseStatement = Application::$app->db->pdo->prepare("SELECT * FROM courses WHERE id = :id");
    $courseStatement->bindValue(':id', $courseId, PDO::PARAM_STR);
    $courseStatement->execute();
    $course = $courseStatement->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
      throw new NotFoundException("Course is not found");
    }

    return $res->setJsonBody([
      'success' => true,
      'message' => "Course have been successfully retrieved",
      'data' => [
        'course' => $course
      ]
    ])->send();
  }
}

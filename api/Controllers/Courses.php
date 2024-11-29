<?php

declare(strict_types=1);
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';

class Courses
{
  public function getAll(Request $req, Response $res)
  {

    return $res->setJsonBody([
      'success' => true,
      'message' => "All courses have been successfully retrieved",
      'data' => [
        'courses' => []
      ]
    ])->send();
  }

  public function get(Request $req, Response $res, array $params)
  {

    return $res->setJsonBody([
      'success' => true,
      'message' => "Course have been successfully retrieved",
      'data' => [
        'course' => []
      ]
    ])->send();
  }
}

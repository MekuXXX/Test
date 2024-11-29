<?php

declare(strict_types=1);
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';

class Categories
{
  public function getAll(Request $req, Response $res)
  {
    return $res->setJsonBody([
      'success' => true,
      'message' => "All Categories have been successfully retrieved",
      'data' => [
        'categories' => []
      ]
    ])->send();
  }

  public function get(Request $req, Response $res, array $params)
  {

    return $res->setJsonBody([
      'success' => true,
      'message' => "Category have been successfully retrieved",
      'data' => [
        'course' => []
      ]
    ])->send();
  }
}

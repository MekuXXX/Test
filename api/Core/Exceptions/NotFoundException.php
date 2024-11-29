<?php

declare(strict_types=1);
require_once __DIR__ . '/HttpException.php';

class NotFoundException extends HTTPException
{
  public function __construct(string $message = "Resource not found", ?Throwable $previous = null)
  {
    // Set the HTTP status code to 404 (Not Found)
    parent::__construct($message, 404, $previous);
  }
}

<?php

declare(strict_types=1);
require_once __DIR__ . '/HttpException.php';

class NotFoundException extends HttpException
{
  public function __construct(string $message = "Resource is not found", ?Throwable $previous = null)
  {
    parent::__construct($message, 404, $previous);
  }
}

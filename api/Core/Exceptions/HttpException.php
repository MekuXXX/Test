<?php

declare(strict_types=1);

class HttpException extends Exception
{
  protected int $statusCode;

  public function __construct(string $message, int $statusCode = 500, ?Throwable $previous = null)
  {
    $this->statusCode = $statusCode;
    parent::__construct($message, $statusCode, $previous);
  }

  public function getStatusCode(): int
  {
    return $this->statusCode;
  }

  public function sendJsonResponse()
  {
    http_response_code($this->statusCode);

    header('Content-Type: application/json');

    echo json_encode([
      'success' => false,
      'message' => $this->getMessage(),
      'data' => []
    ]);

    exit;
  }
}

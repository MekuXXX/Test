<?php

declare(strict_types=1);

class Response
{
  private int $statusCode = 200;
  private array $headers = [];
  private ?string $body = null;
  private ?string $bodyType = null;

  public function setStatusCode(int $statusCode): self
  {
    $this->statusCode = $statusCode;
    return $this;
  }

  public function addHeader(string $key, string $value): self
  {
    $this->headers[] = "{$key}: {$value}";
    return $this;
  }

  public function setJsonBody(array $data): self
  {
    if ($this->bodyType && $this->bodyType !== null) {
      throw new LogicException("Cannot set JSON body when a plain body is already set.");
    }
    $this->bodyType = 'json';
    $this->body = json_encode($data);
    $this->addHeader('Content-Type', 'application/json');
    return $this;
  }

  public function setPlainBody(string $body): self
  {
    if ($this->bodyType && $this->bodyType !== null) {
      throw new LogicException("Cannot set plain body when a JSON body is already set.");
    }
    $this->bodyType = 'plain';
    $this->body = $body;
    return $this;
  }

  public function send(): string
  {
    http_response_code($this->statusCode);

    foreach ($this->headers as $header) {
      header($header);
    }

    return $this->body;
  }
}

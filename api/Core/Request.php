<?php

class Request
{
  public function getQueryParam(string $key, $default = null)
  {
    return $_GET[$key] ?? $default;
  }

  public function getPostParam(string $key, $default = null)
  {
    return $_POST[$key] ?? $default;
  }

  public function getPostData(): array
  {
    return $_POST;
  }

  public function getQueryParams(): array
  {
    return $_GET;
  }

  public function getHeader(string $key, $default = null)
  {
    $headers = getallheaders();
    return $headers[$key] ?? $default;
  }

  public function getMethod(): string
  {
    return strtolower($_SERVER['REQUEST_METHOD']);
  }

  public function getPath(): string
  {
    $url = $_SERVER['REQUEST_URI'];
    $path = parse_url($url, PHP_URL_PATH);
    return $path;
  }

  public function getBody(): string
  {
    return file_get_contents('php://input');
  }

  public function getJsonBody(): ?array
  {
    $body = $this->getBody();
    return json_decode($body, true);
  }

  public function isGet(): bool
  {
    return $this->getMethod() === 'get';
  }

  public function isPost(): bool
  {
    return $this->getMethod() === 'post';
  }

  public function isPut(): bool
  {
    return $this->getMethod() === 'put';
  }

  public function isPatch(): bool
  {
    return $this->getMethod() === 'patch';
  }

  public function isDelete(): bool
  {
    return $this->getMethod() === 'delete';
  }
}

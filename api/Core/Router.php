<?php

declare(strict_types=1);

require_once __DIR__ .  '/Request.php';
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/Exceptions/NotFoundException.php';

class Router
{
  protected array $routes = [];
  protected Request $request;
  protected Response $response;

  public function __construct()
  {
    $this->request = new Request();
    $this->response = new Response();
  }

  public function resolve()
  {
    $method = $this->request->getMethod();
    $path = $this->request->getPath();
    $callback = $this->routes[$method][$path] ?? null;

    if (!$callback) {
      throw new NotFoundException();
    }

    return call_user_func($callback, $this->request, $this->response);
  }

  public function get(string $path, callable $callback)
  {
    $this->routes['get'][$path] = $callback;
  }

  public function post(string $path, callable $callback)
  {
    $this->routes['post'][$path] = $callback;
  }
}

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

    foreach ($this->routes[$method] ?? [] as $route => $callback) {
      $pattern = preg_replace('#:([\w]+)#', '(?P<$1>[\w-]+)', $route);
      $pattern = "#^" . $pattern . "$#";

      // Note: (?P<name>pattern) is called a named capture group, which allows you to match and refer to a value with a specific name.
      if (preg_match($pattern, $path, $matches)) {

        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

        if (is_array($callback)) {
          $class = $callback[0];
          $function = $callback[1];

          if (!class_exists($class)) {
            throw new NotFoundException("Class '$class' does not exist.");
          }

          if (!method_exists($class, $function)) {
            throw new NotFoundException("Method '$function' does not exist in class '$class'.");
          }

          $callback[0] = new $class();
        }

        return call_user_func($callback, $this->request, $this->response, $params);
      }
    }

    throw new NotFoundException("Route is not found for " . strtoupper($method) . " $path");
  }

  public function get(string $path, callable | array $callback)
  {
    $this->routes['get'][$path] = $callback;
  }

  public function post(string $path, callable | array $callback)
  {
    $this->routes['post'][$path] = $callback;
  }

  public function put(string $path, callable | array $callback)
  {
    $this->routes['put'][$path] = $callback;
  }

  public function patch(string $path, callable | array $callback)
  {
    $this->routes['patch'][$path] = $callback;
  }

  public function delete(string $path, callable | array $callback)
  {
    $this->routes['delete'][$path] = $callback;
  }
}

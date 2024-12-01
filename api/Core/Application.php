<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/Exceptions/HttpException.php';

class Application
{
  public static Application $app;
  public Database $db;
  public Router $router;
  protected Request $request;
  public Response $response;

  public function __construct(array $dbCrendentials)
  {
    self::$app = $this;
    $this->router = new Router();
    $this->request = new Request();
    $this->response = new Response();
    $this->db = new Database($dbCrendentials);
  }

  public function run()
  {
    header("Access-Control-Allow-Origin: http://cc.localhost");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    if ($this->request->getMethod() == 'OPTIONS') {
      http_response_code(200);
      exit();
    }
    try {
      echo $this->router->resolve();
    } catch (HttpException $e) {
      $e->sendJsonResponse();
    } catch (\Exception $e) {
      $this->response->setStatusCode(500)->setJsonBody([
        'success' => false,
        'message' => 'Something went wrong',
        'data' => []
      ])->send();
    }
  }
}

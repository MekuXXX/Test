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

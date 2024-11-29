<?php

class Database
{
  public \PDO $pdo;
  private string $dns;
  private string $user;
  private string $password;

  public function __construct(protected array $db)
  {
    $this->dns = "{$db['driver']}:host={$db['host']};port={$db['port']};dbname={$db['database']}";
    $this->user = $db['user'];
    $this->password = $db['password'];

    $this->pdo = new \PDO($this->dns, $this->user, $this->password);
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  }
}

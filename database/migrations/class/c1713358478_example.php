<?php
require_once __DIR__ . '/../Core/Migration.php';
require_once __DIR__ . '/../Core/Application.php';

class c1713358478_example implements IMigration
{
  public  function up()
  {
    Application::$app->db->pdo->exec("
      CREATE TABLE IF NOT EXISTS `skills` (
        `id` VARCHAR(255),
        `name` VARCHAR(255) NOT NULL,
        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      );");
  }
  public  function down()
  {
    Application::$app->db->pdo->exec("DROP TABLE `skills`");
  }
}

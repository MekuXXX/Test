<?php
require_once __DIR__ . '/../Core/Migration.php';
require_once __DIR__ . '/../Core/Application.php';

class c1713358478_courses implements IMigration
{
  public  function up()
  {
    Application::$app->db->pdo->exec("
      CREATE TABLE IF NOT EXISTS `courses` (
        `id` CHAR(36) NOT NULL,
        `name` VARCHAR(255) NOT NULL,
        `description` TEXT NULL,
        `preview` VARCHAR(2083) NULL,
        `category_id` CHAR(36) NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
      );");
  }
  public  function down()
  {
    Application::$app->db->pdo->exec("DROP TABLE `courses`");
  }
}

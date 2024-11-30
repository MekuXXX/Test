<?php
require_once __DIR__ . '/../../../api/Core/Migration.php';
require_once __DIR__ . '/../../../api/Core/Application.php';

class c1713358478_categories implements IMigration
{
  public  function up()
  {
    Application::$app->db->pdo->exec("
      CREATE TABLE IF NOT EXISTS `categories` (
          `id` CHAR(36) NOT NULL,
          `name` VARCHAR(255) NOT NULL,
          `description` TEXT NULL,
          `parent_id` CHAR(36) NULL,
          `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        );");

    // Note: Got error when trying to add parent id as foriegn key even if categories sorted and inserted one by one
    // FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
  }
  public  function down()
  {
    Application::$app->db->pdo->exec("DROP TABLE `categories`");
  }
}
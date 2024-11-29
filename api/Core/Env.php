<?php

declare(strict_types=1);

class Env
{

  public function __construct(string $filePath)
  {
    if (!file_exists($filePath)) {
      throw new \Exception("Error: .env file not found at $filePath");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
      if (str_starts_with(trim($line), '#')) {
        continue;
      }

      [$key, $value] = explode('=', $line, 2);
      $key = trim($key);
      $value = trim(trim($value), '"\'');

      if (!array_key_exists($key, $_ENV)) {
        $_ENV[$key] = $value; // Add to the $_ENV superglobal
        putenv("$key=$value"); // Optional: Set it in the environment variables
      } else {
        throw new Exception("Error: There is already an environment variable with key ('{$key}')\n");
      }
    }
  }
}

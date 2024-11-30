<?php

interface IMigration
{
  public function up();
  public function down();
}

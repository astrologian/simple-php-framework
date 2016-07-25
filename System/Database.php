<?php

namespace System;

class Database
{
  private static $db;
  public static function res() {
    return self::$db;
  }
  public static function connect($profile_name = 'deploy')
  {
    $cfg = include 'App/db.php'; // Читаем конфиг
    $profile = $cfg[$profile_name]; // Выбираем профиль
    self::$db = mysqli_connect($profile['server'], $profile['user'], $profile['password'], $profile['database']);
    mysqli_query(self::$db, 'set names utf8 collate utf8_unicode_ci');
  }
}

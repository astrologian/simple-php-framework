<?php

namespace System;

class Query {
  private $sql;
  
  // Читаем запрос из файла
  public static function file($file, $bindings = array()) {
    return new Query(file_get_contents('app/' . $file . '.sql'), $bindings);
  }
  
  // Берем запрос из переданной строки
  public static function text($text, $bindings = array()) {
    return new Query($text, $bindings);
  }
  
  // Создаем, попутно заменяя шаблоны значениями
  public function __construct($sql, $bindings) {
    $this->sql = $sql;
    foreach ($bindings as $key => $value) {
      $this->bind($key, $value);
    }
  }
  
  // Заменяем шаблон значением
  public function bind($name, $value, $toBase64 = false) {
    if (!is_numeric($value)) {
      if (is_string($value)) {
        $value = addslashes($value); // Экранируем
      } elseif (is_object($value)) { // Объекты сериализуем в JSON
        $value = json_encode($value);
      } elseif (is_array($value)) { // Я не знаю, зачем мне это понадобилось
        $value = str_replace(array('[', ']'), array('(', ')'), json_encode($value));
      }
      if ($toBase64) { // На случай. если есть опасные элементы
        $value = base64_encode($value);
      }
      $value = "'$value'"; // Обрамляем строки кавычками
    }
    $this->sql = str_replace(':' . $name, $value, $this->sql);
    return $this;
  }
  public function exec($sql) {
    $this->log();
    $r = mysqli_query(Database::res(), $sql);
    $r2 = array();
    if (false === $r) {
      echo Database::res()->error;
      return false;
    } else if (true !== $r) {
      while ($row = mysqli_fetch_object($r)) {
        $r2[] = $row;
      }
      return $r2;
    }
    return true;
  }
  private function log() {
    if (isset($_GET['sql'])) { // Если имеется GET-параметр sql, показываем запрос. В продакшне следует отключать. По идее, надо глобальные настройки для этого держать, в одном месте, но мне лень было
      echo "\n";
      echo $this->sql . "\n";
      echo "====================================================================\n";
    }
  }
  public function insert() {
    $this->exec($this->sql);
    return Database::res()->insert_id;
  }
  public function select() {
    return $this->exec($this->sql);
  }
  public function get() {
    $r = $this->exec($this->sql);
    if (true !== $r and false !== $r and null != $r) {
      return $r[0];
    }
    return false;
  }
  public function update() {
    $this->exec($this->sql);
  }
  public function delete() {
    $this->exec($this->sql);
  }
}

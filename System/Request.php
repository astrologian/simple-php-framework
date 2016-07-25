<?php

namespace System;

/**
 * Единственное, что делает этот класс - хранит параметры, переданные в URL
*/

class Request
{
  private $params = [];
  public function get($key)
  {
    return $this->params[$key];
  }
  public function set($key, $value) // Можно менять параметры на лету :)
  {
    $this->params[$key] = $value;
  }
  public function setArray($params)
  {
    $this->params = $params;
  }
}

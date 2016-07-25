<?php

namespace System;

class Collection implements Interfaces\ICollection
{
  protected $position = 0;
  protected $array = [];

  public function __construct() {
    $this->position = 0;
  }

  public function add($item)
  {
    $this->array[] = $item;
  }
  
  function rewind() {
    $this->position = 0;
  }

  function current() {
    return $this->array[$this->position];
  }

  function key() {
    return $this->position;
  }

  function next() {
    ++$this->position;
  }

  function valid() {
    return isset($this->array[$this->position]);
  }

  public function offsetSet($offset, $value) {
    if (is_null($offset)) {
      $this->array[] = $value;
    } else {
      $this->array[$offset] = $value;
    }
  }

  public function offsetExists($offset) {
    return isset($this->array[$offset]);
  }

  public function offsetUnset($offset) {
    unset($this->array[$offset]);
  }

  public function offsetGet($offset) {
    return isset($this->array[$offset]) ? $this->array[$offset] : null;
  }
}

<?php

namespace System;

class Container
{

  public static $containers = [];
  public static $mapConfig = null;

  public static function factory($containerName)
  {
    if (is_null(self::$mapConfig)) {
      self::$mapConfig = include('App/service.php');
    }
    if (! array_key_exists($containerName, self::$containers)) {
      self::$containers[$containerName] = new Container();
      $nulls = array_fill(0, count(self::$mapConfig), null);
      self::$containers[$containerName]->map = array_combine(self::$mapConfig, $nulls);
    }
    return self::$containers[$containerName];
  }

  public $map = []; // Своеобразный кэш объектов или простых данных

  public function get($key)
  {
    if (array_key_exists($key, $this->map)) {
      return $this->map[$key];
    } else {
      if (1 === preg_match('/(\w+(?:\\\\\w+)*)::(\w+)/', $key, $matches)) {
        return $this->resolve($matches[1], $matches[2]);
      }
      return $this->resolve($key);
    }
  }

  public function has($key)
  {
    return array_key_exists($key, $this->map);
  }

  public function set($key, $value)
  {
    $this->map[$key] = $value;
  }

  public function resolve($className, $methodName = '')
  {
    if (array_key_exists($className, $this->map)) {
      if (! is_null($this->map[$className])) {
        return $this->map[$className];
      }
    }
    $instance = null;
    try {
      $class = new \ReflectionClass($className);
      if ('' === $methodName) {
        $cotr = $class->getConstructor();
        if (is_null($cotr)) {
          $instance = new $className();
        } else {
          $instance = $class->newInstanceArgs($this->getDependencies($cotr));
        }
      } else {
        $method = $class->getMethod($methodName);
        if (is_null($method)) {
          throw new \Exception("В классе [{$class->name}] нет метода [$methodName]");
        } else {
          $instance = $class->newInstance();
          return $method->invokeArgs($instance, $this->getDependencies($method));
        }
      }
    } catch (\ReflectionException $e) {
      print_r(__FILE__ . __LINE__ . $e->getMessage() . "\n");
    }
    if ('' === $methodName) {
      $key = $className;
    } else {
      $key = $className . '::' . $methodName;
    }
    if (array_key_exists($key, $this->map)) {
      if (is_null($this->map[$key])) {
        $this->map[$key] = $instance;
      }
    }
    return $instance;
  }

  public function getDependencies($method)
  {
    $ps = $method->getParameters();
    $deps = [];
    foreach ($ps as $p) {
      $deps[] = $this->getDependency($p);
    }
    return $deps;
  }

  public function getDependency($p)
  {
    $class = $p->getClass();
    if (is_null($class)) {
      if ($p->isDefaultValueAvailable()) {
        return $p->getDefaultValue();
      } else {
        throw new \Exception("Что за хрень с параметром [$p->name] в конструкторе класса [{$p->getDeclaringClass()->name}]?");
      }
    }
    return $this->resolve($class->name);
  }
}

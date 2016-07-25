<?php

namespace Test;

class App
{
  public function __construct(Engine $engine, Driver $driver = NULL)
  {
    print_r($engine->volume . "\n");
    print_r($driver->engine->volume . "\n");
  }
}

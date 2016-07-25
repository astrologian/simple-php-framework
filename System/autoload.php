<?php

function autoload($class)
{
  $file = ROOT . str_replace('\\', '/', $class) . '.php';
  if (file_exists($file)) {
    require $file;
  } else {
    return;
  }
}

spl_autoload_register('autoload');

<?php

namespace System;

class Controller
{
  public function __construct()
  {
    // Костыль, по-моему
    $container = Container::factory('services');
    $this->rootDirectory = $container->get('root_dir');
  }
  protected function render($view, array $vars)
  {
    return $this->capture($view, $vars);
  }
  protected function capture($_view, $vars)
  {
    extract($vars, EXTR_SKIP);       // Элементы массива становятся переменными
    ob_start();                      // Буферизуем вывод
    require $this->rootDirectory . 'App/View/' . $_view . '.html'; // Подключаем файл с HTML
    return ob_get_clean();           // Закрываем и возвращаем буфер в виде строки
  }
}

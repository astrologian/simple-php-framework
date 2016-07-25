<?php

namespace System;

class Router
{

  public function __construct(Request $request)
  {
    $this->_routes = include 'App/routes.php';
    $this->_request = $request;
  }

  public function dispatch($uri)
  {
    // Делал в спешке
    $container = Container::factory('services');
    foreach ($this->_routes as $uri_template => $spec) {
      $u = $this->compile($uri_template);
      if (1 === preg_match($u[0], $uri, $matches)) {
      
        // Собираем массив параметров из их имен (из шаблона URL) и значений, полученных из реального URL
        $this->_request->setArray(array_combine($u[1], array_slice($matches, 1)));
        
        if (is_array($spec) and isset($spec['controller'])) {
          $class = 'App\\Controller\\' . str_replace('.', '\\', $spec['controller']) . 'Controller';
          if (isset($spec['action'])) {
            $method = $spec['action'] . 'Action';
          } else {
            $method = 'indexAction';
          }
          echo $container->get($class . '::' . $method);
        } elseif (is_array($spec) and isset($spec['service'])) {
          $class = 'App\\Service\\' . str_replace('.', '\\', $spec['service']);
          $method = $spec['action'];
          echo $container->get($class . '::' . $method);
        }
        return;
      }
    }
    echo '404';
  }

  private function compile($uri_template)
  {
    // Компиляция шаблона в регулярное выражение
    // Производим замену некоторых символов
    $uri_regex = str_replace(
      array('/', '.', '[', ']'),
      array('\/', '\.', '(?:', ')?'),
      $uri_template
    );

    // Ищем описания параметров в шаблоне
    preg_match_all('/(?:\{(\w+)\:(\w+)\})/', $uri_regex, $matches);

    // Извлекаем имена параметров
    $names = $matches[1];

    // Заменяем параметры в шаблоне соответствующими регулярками
    $uri_regex = preg_replace(
      array('/(?:\{\w+\:str\})/', '/(?:\{\w+\:int\})/'),
      array('(\w+(?:-\w+)*)', '(\d+)'),
      $uri_regex
    );
    return ["/^{$uri_regex}$/", $names]; // Конечный вариант + имена параметров
  }
  
}

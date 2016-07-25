<?php

namespace App\Controller;

use System\Controller;

class HelloController extends Controller
{
  public function indexAction()
  {
    return $this->render('Index',[
      'pageTitle'  => 'Главная',
      'view' => '<a href="/1-example-article">Example article</a>',
    ]);
  }
}

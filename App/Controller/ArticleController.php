<?php

namespace App\Controller;

use System\Controller;
use App\Model\Article;
use App\Model\ArticleCollection;

class ArticleController extends Controller
{
  public function indexAction(Article $article, ArticleCollection $relatedArticles)
  {
    $sidebar = $this->render('Article/Sidebar', [
      'articles' => $relatedArticles
    ]);
    $content = $this->render('Article/Content', [
      'article' => $article
    ]);
    return $this->render('Index', [
      'pageTitle'  => $article->getTitle(),
      'view'  => $this->render('Column2', [
        'sidebar' => $sidebar,
        'content' => $content,
      ]),
    ]);
  }
}

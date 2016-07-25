<?php

namespace App\Model;

use System\Request;
use System\Query;

class Article
{
  public function __construct($obj = null, Request $request = null)
  {
    if (is_null($obj)) {
      $obj = Query::text("
        SELECT
          a.article_id AS id,
          a.article_name AS name,
          a.article_title AS title,
          a.article_content AS content,
          0 AS isCurrent
        FROM article AS a
        WHERE a.article_id = :articleId;
      ", ['articleId' => $request->get('articleId')])->get();
    }
    $this->id = $obj->id;
    $this->name = $obj->name;
    $this->title = $obj->title;
    $this->content = $obj->content;
    $this->isCurrent = $obj->isCurrent;
  }
  public function getId()
  {
    return $this->id;
  }
  public function getName()
  {
    return $this->name;
  }
  public function getTitle()
  {
    return $this->title;
  }
  public function getContent()
  {
    return $this->content;
  }
  public function isCurrent()
  {
    return $this->isCurrent;
  }
}

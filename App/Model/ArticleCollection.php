<?php

namespace App\Model;

use System;

class ArticleCollection extends System\Collection
{
  public function __construct(Article $article)
  {
    $result = System\Query::text("
      SELECT
        a.article_id AS id,
        a.article_name AS name,
        a.article_title AS title,
        '' AS content,
        IF(a.article_id = {$article->getId()}, 1, 0) AS isCurrent
      FROM article AS a
      WHERE 1;
    ")->select();
    foreach ($result as $row) {
        $this->add(new Article($row));
    }
  }
}

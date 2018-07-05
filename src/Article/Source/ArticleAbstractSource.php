<?php

namespace App\Article\Source;


use App\Article\ArticleCatalogue;
use App\Article\ArticleRepositoryInterface;
use App\Entity\Article;

abstract class ArticleAbstractSource implements ArticleRepositoryInterface
{
    protected $catalogue;

    /**
     * @param ArticleCatalogue $catalogue
     */
    public function setCatalogue(ArticleCatalogue $catalogue): void
    {
        $this->catalogue = $catalogue;
    }

    /**
     * Permet de convertir un tableau en Article.
     * @param iterable $article Un article sous forme de tableau
     * @return Article|null
     */
    abstract protected function createFromArray(iterable $article): ?Article;

}

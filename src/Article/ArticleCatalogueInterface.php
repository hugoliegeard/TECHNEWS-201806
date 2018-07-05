<?php

namespace App\Article;


use App\Article\Source\ArticleAbstractSource;

interface ArticleCatalogueInterface extends ArticleRepositoryInterface
{
    public function addSource(ArticleAbstractSource $source): void;
    public function setSources(iterable $sources): void;
    public function getSources(): iterable;
}
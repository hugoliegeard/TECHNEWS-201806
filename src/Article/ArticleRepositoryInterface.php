<?php

namespace App\Article;


use App\Entity\Article;

interface ArticleRepositoryInterface
{

    /**
     * Permet de retourner un article sur la
     * base de son identifiant unique.
     * @param $id
     * @return Article|null
     */
    public function find($id): ?Article;

    /**
     * Retourne la liste de tous les articles
     * @return iterable|null
     */
    public function findAll(): ?iterable;

    /**
     * Retourne les 5 derniers articles depuis
     * l'ensemble de nos sources...
     * @return iterable|null
     */
    public function findLastFiveArticles(): ?iterable;

    /**
     * Retourne le nombre d'éléments de chaque source.
     * @return int
     */
    public function count(): int;

    # public function findBy();
    # public function findOneBy();

}
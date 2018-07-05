<?php

namespace App\Article\Source;


use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;

class DoctrineSource extends ArticleAbstractSource
{

    private $repository;
    private $entity = Article::class;

    /**
     * DoctrineSource constructor.
     * @param $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->repository = $manager->getRepository($this->entity);
    }

    /**
     * Permet de retourner un article sur la
     * base de son identifiant unique.
     * @param $id
     * @return Article|null
     */
    public function find($id): ?Article
    {
        return $this->repository->find($id);
    }

    /**
     * Retourne la liste de tous les articles
     * @return iterable|null
     */
    public function findAll(): ?iterable
    {
        return $this->repository->findAll();
    }

    /**
     * Retourne les 5 derniers articles depuis
     * l'ensemble de nos sources...
     * @return iterable|null
     */
    public function findLastFiveArticles(): ?iterable
    {
        return $this->repository->findLastFiveArticles();
    }

    /**
     * Retourne le nombre d'éléments de chaque source.
     * @return int
     */
    public function count(): int
    {
        return $this->repository->findTotalArticles();
    }

    /**
     * Permet de convertir un tableau en Article.
     * @param iterable $article Un article sous forme de tableau
     * @return Article|null
     */
    protected function createFromArray(iterable $article): ?Article
    {
        return null;
    }
}
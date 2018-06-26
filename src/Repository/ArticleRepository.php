<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Récupère les 5 derniers articles
     * @return mixed
     */
    public function findLastFiveArticles()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les suggestions d'articles
     */
    public function findArticlesSuggestions($idarticle, $idcategorie)
    {
        return $this->createQueryBuilder('a')

            # Where pour la catégorie
            ->where('a.category = :category_id')
            ->setParameter('category_id', $idcategorie)

            # Where pour l'article
            ->andWhere('a.id != :article_id')
            ->setParameter('article_id', $idarticle)

            # Par ordre décroissant
            ->orderBy('a.id', 'DESC')

            # Maximum 3
            ->setMaxResults(3)

            # On finalise
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupère les articles en Spotlight
     * @return mixed
     */
    public function findSpotlightArticles() {
        return $this->createQueryBuilder('a')
            ->where('a.spotlight = 1')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Récupère les articles "special" de la sidebar
     * @return mixed
     */
    public function findSpecialArticles() {
        return $this->createQueryBuilder('a')
            ->where('a.special = 1')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

}

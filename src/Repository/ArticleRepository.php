<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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
            ->getResult();
    }

    /**
     * Récupère les articles en Spotlight
     * @return mixed
     */
    public function findSpotlightArticles()
    {
        return $this->createQueryBuilder('a')
            ->where('a.spotlight = 1')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les articles "special" de la sidebar
     * @return mixed
     */
    public function findSpecialArticles()
    {
        return $this->createQueryBuilder('a')
            ->where('a.special = 1')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte le nombre d'articles dans la BDD
     * @return int|mixed
     */
    public function findTotalArticles()
    {
        try {
            return $this->createQueryBuilder('a')
                ->select('COUNT(a)')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return 0;
        }
    }

    /**
     * Récupérer tous les articles d'un Auteur par Statut.
     * @param int $idAuthor
     * @param string $status Status de l'Article
     * @return \Doctrine\ORM\QueryBuilder
     * @internal param int $idauteur ID de l'Auteur
     */
    public function findAuthorArticlesByStatus(int $idAuthor, string $status)
    {
        return $this->createQueryBuilder('a')
            ->where('a.user = :author_id')
            ->setParameter('author_id', $idAuthor)
            ->andWhere('a.status LIKE :status')
            ->setParameter('status', "%$status%")
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupérer les articles par Statut
     * @param string $status
     * @return mixed
     */
    public function findArticlesByStatus(string $status)
    {
        return $this->createQueryBuilder('a')
            ->where('a.status LIKE :status')
            ->setParameter('status', "%$status%")
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compter le nombre d'articles d'un Auteur par Statut
     * @param int $idAuthor
     * @param string $status
     * @return mixed
     */
    public function countAuthorArticlesByStatus(int $idAuthor, string $status)
    {
        try {
            return $this->createQueryBuilder('a')
                ->select('COUNT(a)')
                ->where('a.user = :author_id')
                ->setParameter('author_id', $idAuthor)
                ->andWhere('a.status LIKE :status')
                ->setParameter('status', "%$status%")
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return 0;
        }
    }

    /** Compter les articles par statut
     * @param string $status
     * @return int|mixed
     */
    public function countArticlesByStatus(string $status)
    {
        try {
            return $this->createQueryBuilder('a')
                ->select('COUNT(a)')
                ->where('a.status LIKE :status')
                ->setParameter('status', "%$status%")
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NonUniqueResultException $e) {
            return 0;
        }
    }

}

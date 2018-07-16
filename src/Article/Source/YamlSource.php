<?php

namespace App\Article\Source;


use App\Controller\HelperTrait;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use App\Service\Article\YamlProvider;
use Symfony\Component\Validator\Constraints\DateTime;
use Tightenco\Collect\Support\Collection;

class YamlSource extends ArticleAbstractSource
{

    use HelperTrait;

    private $articles;

    /**
     * YamlSource constructor.
     * @param $yamlProvider
     */
    public function __construct(YamlProvider $yamlProvider)
    {
        $this->articles = new Collection($yamlProvider->getArticles());

        /*
         * Autre possibilitée :
         */
        # $this->articles = new Collection($yamlProvider->getArticles());
        # foreach ($this->articles as &$article) {
        #    $article = $this->createFromArray($article);
        # }
    }

    /**
     * Permet de retourner un article sur la
     * base de son identifiant unique.
     * @param $id
     * @return Article|null
     */
    public function find($id): ?Article
    {
        $article = $this->articles->firstWhere('id', $id);
        return $article == null ? null : $this->createFromArray($article);
    }

    /**
     * Retourne la liste de tous les articles
     * @return iterable|null
     */
    public function findAll(): ?iterable
    {
        $articles = new Collection();

        foreach ($this->articles as $article) {
            $articles[] = $this->createFromArray($article);
        }

        return $articles;
    }

    /**
     * Retourne les 5 derniers articles depuis
     * l'ensemble de nos sources...
     * @return iterable|null
     */
    public function findLastFiveArticles(): ?iterable
    {
        /* @var $articles Collection */
        $articles = $this->findAll();
        return $articles->sortBy('datecreation')->slice(-5);
    }

    /**
     * Retourne le nombre d'éléments de chaque source.
     * @return int
     */
    public function count(): int
    {
        return $this->articles->count();
    }

    /**
     * Permet de convertir un tableau en Article.
     * @param iterable $article Un article sous forme de tableau
     * @return Article|null
     */
    protected function createFromArray(iterable $article): ?Article
    {
        $tmp = (object)$article;

        # Construire l'objet Category
        $category = new Category();
        $category->setId($tmp->categorie['id']);
        $category->setName($tmp->categorie['libelle']);
        $category->setSlug($this->slugify($tmp->categorie['libelle']));

        # Construire l'objet Auteur
        $user = new User();
        $user->setId($tmp->auteur['id']);
        $user->setFirstname($tmp->auteur['prenom']);
        $user->setLastname($tmp->auteur['nom']);

        $date = new \DateTime();

        # Construire l'objet Article
        return new Article(
            $tmp->id,
            $tmp->titre,
            $this->slugify($tmp->titre),
            $tmp->contenu,
            $tmp->featuredimage,
            $tmp->special,
            $tmp->spotlight,
            $date->setTimestamp($tmp->datecreation),
            $category,
            $user,
            'published'
        );

    }
}
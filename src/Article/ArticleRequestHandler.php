<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 29/06/2018
 * Time: 10:42
 */

namespace App\Article;


use App\Controller\HelperTrait;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleRequestHandler
{

    use HelperTrait;

    private $em, $assetsDirectory, $articleFactory, $packages;

    /**
     * ArticleRequestHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param ArticleFactory $articleFactory
     * @param string $assetsDirectory
     * @param Packages $packages
     * @internal param Package $package
     * @internal param $em
     */
    public function __construct(EntityManagerInterface $entityManager,
                                ArticleFactory $articleFactory,
                                string $assetsDirectory,
                                Packages $packages)
    {
        $this->em = $entityManager;
        $this->articleFactory = $articleFactory;
        $this->assetsDirectory = $assetsDirectory;
        $this->packages = $packages;
    }

    public function handle(ArticleRequest $request): Article
    {

        # Traitement de l'upload de mon image
        /** @var UploadedFile $image */
        $image = $request->getFeaturedImage();

        # Nom du Fichier
        $fileName = rand(0, 100).$this->slugify($request->getTitle()) . '.'
            . $image->guessExtension();

        $image->move(
            $this->assetsDirectory,
            $fileName
        );

        # Mise à jour de l'image
        $request->setFeaturedImage($fileName);

        # Mise à jour du slug
        $request->setSlug($this->slugify($request->getTitle()));

        # Appel à notre Factory
        $article = $this->articleFactory->createFromArticleRequest($request);

        # Insertion en BDD
        $this->em->persist($article);
        $this->em->flush();

        return $article;
    }

//    public function prepareArticleFromRequest(Article $article): ArticleRequest
//    {
//        return ArticleRequest::createFromArticle($article, $this->packages, $this->assetsDirectory);
//    }
}
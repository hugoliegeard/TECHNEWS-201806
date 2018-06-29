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
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleRequestHandler
{

    use HelperTrait;

    private $em, $assetsDirectory, $articleFactory;

    /**
     * ArticleRequestHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param ArticleFactory $articleFactory
     * @param $assetsDirectory
     * @internal param $em
     */
    public function __construct(EntityManagerInterface $entityManager,
                                ArticleFactory $articleFactory ,
                                $assetsDirectory)
    {
        $this->em = $entityManager;
        $this->articleFactory = $articleFactory;
        $this->assetsDirectory = $assetsDirectory;
    }

    public function handle(ArticleRequest $request): Article
    {

        # Traitement de l'upload de mon image
        /** @var UploadedFile $image */
        $image = $request->getFeaturedImage();

        # Nom du Fichier
        $fileName = $this->slugify($request->getTitle()) . '.'
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
}
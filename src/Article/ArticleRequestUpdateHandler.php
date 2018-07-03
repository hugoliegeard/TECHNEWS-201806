<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 02/07/2018
 * Time: 18:05
 */

namespace App\Article;


use App\Controller\HelperTrait;
use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleRequestUpdateHandler
{

    use HelperTrait;

    private $em, $assetsDirectory;

    public function __construct(ObjectManager $manager,
                                string $assetsDirectory)
    {
        $this->em = $manager;
        $this->assetsDirectory = $assetsDirectory;
    }

    public function handle(ArticleRequest $articleRequest, Article $article)
    {
        # Traitement de l'upload de mon image
        /** @var UploadedFile $image */
        $image = $articleRequest->getFeaturedImage();

        /*
         * Todo : Pensez a supprimer l'ancienne image sur le FTP.
         */
        if (null !== $image) {
            # Nom du Fichier
            $fileName = rand(0, 100).$this->slugify($articleRequest->getTitle()) . '.'
                . $image->guessExtension();

            $image->move(
                $this->assetsDirectory,
                $fileName
            );

            # Mise à jour de l'image
            $articleRequest->setFeaturedImage($fileName);
        } else {
            $articleRequest->setFeaturedImage($article->getFeaturedImage());
        }

        # Mise à jour du contenu
        $article->update(
            $articleRequest->getTitle(),
            $this->slugify($articleRequest->getTitle()),
            $articleRequest->getContent(),
            $articleRequest->getFeaturedImage(),
            $articleRequest->getSpecial(),
            $articleRequest->getSpotlight(),
            $articleRequest->getCreatedDate(),
            $articleRequest->getCategory()
        );

        # Sauvegarde en BDD
        $this->em->flush();

        # On retourne notre Article
        return $article;
    }

}
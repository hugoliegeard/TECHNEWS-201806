<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 02/07/2018
 * Time: 18:05
 */

namespace App\Article;


use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleRequestUpdateHandler
{

    private $em;

    public function __construct(ObjectManager $manager)
    {
        $this->em = $manager;
    }

    public function handle(ArticleRequest $articleRequest)
    {
        $article = $this->em->getRepository(Article::class)
            ->find($articleRequest->getId());

        $article->update(
            $articleRequest->getTitle(),
            $articleRequest->getSlug(),
            $articleRequest->getContent()
        );

        dump($article);

        $this->em->persist($article);
        $this->em->flush();

    }

}
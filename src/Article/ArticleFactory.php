<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 29/06/2018
 * Time: 11:23
 */

namespace App\Article;


use App\Entity\Article;

class ArticleFactory
{
    public function createFromArticleRequest(ArticleRequest $request): Article
    {
        return new Article(
            $request->getId(),
            $request->getTitle(),
            $request->getSlug(),
            $request->getContent(),
            $request->getFeaturedImage(),
            $request->getSpecial(),
            $request->getSpotlight(),
            $request->getCreatedDate(),
            $request->getCategory(),
            $request->getUser()
        );
    }
}
<?php

namespace App\Controller\TechNews;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * Page d'Accueil de notre Site Internet
     * @return Response
     */
    public function index()
    {
        # return new Response("<html><body><h1>PAGE D'ACCUEIL</h1></body></html>");
        return $this->render('index/index.html.twig');
    }

    /**
     * Afficher les Articles d'une Catégorie
     * @Route("/categorie/{category}",
     *  name="index_category",
     *     methods={"GET"},
     *     defaults={"category":"computing"},
     *     requirements={"category":"\w+"})
     * @param $category
     * @return Response
     */
    public function category($category)
    {
        # return new Response("<html><body><h1>PAGE CATEGORIE : $category</h1></body></html>");
        return $this->render('index/category.html.twig');
    }

    /**
     * Affiche un Article
     * @Route("/{category}/{slug}_{id}.html",
     *     name="index_article",
     *     requirements={"id":"\d+"})
     */
    public function article()
    {
        # /business/une-formation-symfony-a-paris_8796456.html
        return $this->render('index/article.html.twig');
    }
}

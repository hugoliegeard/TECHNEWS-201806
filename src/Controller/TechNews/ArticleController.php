<?php

namespace App\Controller\TechNews;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{
    /**
     * Démonstration de l'ajout d'un Article
     * avec Doctrine. (Data Mapper)
     * @Route("/article", name="article")
     */
    public function index()
    {
        # Création de la Catégorie
        $category = new Category();
        $category->setName('Engineering');
        $category->setSlug('engineering');

        # Création de notre Utilisateur (Auteur)
        $user = new User();
        $user->setFirstname('Hugo');
        $user->setLastname('LIEGEARD');
        $user->setEmail('test@hl-media.fr');
        $user->setPassword('MonSuperPassword');
        $user->setRoles(['ROLE_AUTEUR']);

        # Création de l'Article
        $article = new Article();
        $article->setTitle('La Pepiniere du 06/18 est formidable !');
        $article->setContent('<p>Mais, je me pose des questions...</p>');
        $article->setFeaturedImage('3.jpg');
        $article->setSpecial(0);
        $article->setSpotlight(1);

        # On associe une catégorie et un auteur à notre article
        $article->setUser($user);
        $article->setCategory($category);

        # On sauvegarde le tout en BDD avec Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->persist($user);
        $em->persist($article);
        $em->flush();

        return new Response('Nouvel Article ajouté avec pour ID : ' . $article->getId() . ' et la nouvelle catégorie ' . $category->getName() . ' de notre Auteur ' . $user->getFirstname());
    }
}

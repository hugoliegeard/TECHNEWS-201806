<?php

namespace App\Controller\TechNews;

use App\Article\ArticleRequest;
use App\Article\ArticleRequestHandler;
use App\Article\ArticleType;
use App\Controller\HelperTrait;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{

    use HelperTrait;

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

    /**
     * Formulaire pour créer un Article
     * @Route("/creer-un-article", name="article_add")
     * @param Request $request
     * @param ArticleRequestHandler $articleRequestHandler
     * @return Response
     */
    public function addArticle(Request $request, ArticleRequestHandler $articleRequestHandler)
    {

        # Récupération de l'auteur | ou en session.
        $auteur = $this->getDoctrine()
            ->getRepository(User::class)
            ->find(1);
        # $article->setUser($auteur);

        # Création d'un nouvel article
        # $article = new Article();
        $article = new ArticleRequest($auteur);

        # Créer un Formulaire permettant l'ajout d'un Article
        $form = $this->createForm(ArticleType::class, $article)
            ->handleRequest($request);

        # Traitement des données POST
        # $form->handleRequest($request);

        # Vérification des données du Formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            # Récupération des données
            # $article = $form->getData();
            # dump($article);

            /**
             * Une fois le formulaire soumit et valide,
             * on passe nos données directement au service
             * qui se chargera du traitement.
             */
            $article = $articleRequestHandler->handle($article);

            # Flash Messages
            $this->addFlash('notice','Félicitation, votre article est en ligne !');

            # Redirection sur l'Article qui vient d'être créé.
            return $this->redirectToRoute('index_article', [
                'category' => $article->getCategory()->getSlug(),
                'slug'     => $article->getSlug(),
                'id'       => $article->getId()
            ]);
        }

        # Affichage du Formulaire dans la vue
        return $this->render('article/addarticle.html.twig', [
            'form' => $form->createView()
        ]);

    }

}

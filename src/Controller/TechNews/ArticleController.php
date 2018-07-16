<?php

namespace App\Controller\TechNews;

use App\Article\ArticleRequest;
use App\Article\ArticleRequestHandler;
use App\Article\ArticleRequestUpdateHandler;
use App\Article\ArticleType;
use App\Article\ArticleWorkflowHandler;
use App\Controller\HelperTrait;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Registry;

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
     * @Route({
     *     "fr": "/creer-un-article",
     *     "en": "/new-article"
     * }, name="article_add")
     * @Security("has_role('ROLE_AUTHOR')")
     * @param Request $request
     * @param ArticleRequestHandler $articleRequestHandler
     * @return Response
     */
    public function addArticle(Request $request, ArticleRequestHandler $articleRequestHandler)
    {

        # Récupération de l'auteur | ou en session.
        # $auteur = $this->getDoctrine()
        #     ->getRepository(User::class)
        #     ->find(1);
        # $article->setUser($auteur);

        # Création d'un nouvel article
        # $article = new Article();
        $article = new ArticleRequest($this->getUser());

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
            $this->addFlash('notice', 'Félicitation, votre article est en ligne !');

            # Redirection sur l'Article qui vient d'être créé.
            return $this->redirectToRoute('index_article', [
                'category' => $article->getCategory()->getSlug(),
                'slug' => $article->getSlug(),
                'id' => $article->getId()
            ]);
        }

        # Affichage du Formulaire dans la vue
        return $this->render('article/addarticle.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * Editer / Modifier un Article
     * @Route("/editer-un-article/{id<\d+>}", name="article_edit")
     * @Security("has_role('ROLE_AUTHOR')")
     * @param Article $article
     * @param Request $request
     * @param Packages $packages
     * @param ArticleRequestUpdateHandler $updateHandler
     * @return Response
     * @internal param ArticleRequestHandler $articleRequestHandler
     * @internal param ArticleRequest $articleRequest
     */
    public function editArticle(Article $article, Request $request, Packages $packages, ArticleRequestUpdateHandler $updateHandler)
    {

        # Récupération de notre ArticleRequest depuis Article
        # $articleRequestHandler->prepareArticleFromRequest($article);
        $ar = ArticleRequest::createFromArticle($article, $packages, $this->getParameter('articles_assets_directory'));

        # Création du Formulaire
        $options = [
            'image_url' => $ar->getImageUrl(),
            'slug' => $ar->getSlug()
        ];

        $form = $this->createForm(ArticleType::class, $ar, $options)
            ->handleRequest($request);

        # Quand le formulaire est soumis
        if ($form->isSubmitted() && $form->isValid()) {

            # On sauvegarde les données
            $article = $updateHandler->handle($ar, $article);

            # Flash Message
            $this->addFlash('notice', 'Modification Effectuée !');

            return $this->redirectToRoute('article_edit', [
                'id' => $article->getId()
            ]);
        }

        # Affichage du Formulaire dans la vue
        return $this->render('article/addarticle.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Afficher les articles publiés d'un Auteur
     * @Route({
     *     "fr": "/mes-articles",
     *     "en": "/my-articles"
     * }, name="article_published")
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function publishedArticles()
    {
        # Récupération de l'Auteur
        $author = $this->getUser();

        # Récupération des articles
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAuthorArticlesByStatus($author->getId(), 'published');

        # Affichage dans la vue
        return $this->render('article/articles.html.twig', [
            'articles' => $articles,
            'title' => 'Mes Articles Publiés'
        ]);
    }

    /**
     * Afficher les articles en attente de soumission
     * @Route({
     *     "fr": "/mes-articles/en-attente",
     *     "en": "/my-articles/pending"
     * }, name="article_pending")
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function pendingArticles()
    {
        # Récupération de l'Auteur
        $author = $this->getUser();

        # Récupération des articles
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAuthorArticlesByStatus($author->getId(), 'review');

        # Affichage dans la vue
        return $this->render('article/articles.html.twig', [
            'articles' => $articles,
            'title' => 'Mes Articles en Attente'
        ]);
    }

    /**
     * Afficher les articles en attente de validation
     * @Route({
     *     "fr": "/les-articles/en-attente-de-validation",
     *     "en": "/articles/pending-approval"
     * }, name="article_approval")
     * @Security("has_role('ROLE_EDITOR')")
     */
    public function approvalArticles()
    {
        # Récupération des articles
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findArticlesByStatus('editor');

        # Affichage dans la vue
        return $this->render('article/articles.html.twig', [
            'articles' => $articles,
            'title' => 'En Attente de Validation'
        ]);
    }

    /**
     * Afficher les articles en attente de correction
     * @Route({
     *     "fr": "/les-articles/en-attente-de-correction",
     *     "en": "/articles/pending-correction"
     * }, name="article_corrector")
     * @Security("has_role('ROLE_CORRECTOR')")
     */
    public function correctorArticles()
    {
        # Récupération des articles
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findArticlesByStatus('corrector');

        # Affichage dans la vue
        return $this->render('article/articles.html.twig', [
            'articles' => $articles,
            'title' => 'En Attente de Correction'
        ]);
    }

    /**
     * Afficher les articles en attente de publication
     * @Route({
     *     "fr": "/les-articles/en-attente-de-publication",
     *     "en": "/articles/pending-publication"
     * }, name="article_publisher")
     * @Security("has_role('ROLE_PUBLISHER')")
     */
    public function publisherArticles()
    {
        # Récupération des articles
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findArticlesByStatus('publisher');

        # Affichage dans la vue
        return $this->render('article/articles.html.twig', [
            'articles' => $articles,
            'title' => 'En Attente de Publication'
        ]);
    }

    /**
     * Permet de changer le status d'un Article
     * @Route("workflow/{status}/{id}", name="article_workflow")
     * @Security("has_role('ROLE_AUTHOR')")
     * @param $status
     * @param Article $article
     * @param ArticleWorkflowHandler $awh
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @internal param Registry $workflows
     */
    public function workflow($status,
                             Article $article,
                             ArticleWorkflowHandler $awh,
                             Request $request)
    {

        # Traitement du Workflow
        try {
            $awh->handle($article, $status);

            # Notification
            $this->addFlash('notice',
                'Votre article à bien été transmis. Merci.');

        } catch (LogicException $e) {

            # Notification
            $this->addFlash('error',
                'Changement de statut impossible.');

        }

        # Récupération du Redirect
        $redirect = $request->get('redirect') ?? 'index';

        # On redirige l'utilisateur sur la bonne page
        return $this->redirectToRoute($redirect);

    }

}

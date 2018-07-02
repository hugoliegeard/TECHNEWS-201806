<?php

namespace App\Controller\TechNews;


use App\User\UserRequest;
use App\User\UserRequestHandler;
use App\User\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{

    /**
     * Inscription d'un Utilisateur
     * @Route("/inscription",
     *     name="user_register",
     *     methods={"GET", "POST"})
     * @param Request $request
     * @param UserRequestHandler $userRequestHandler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, UserRequestHandler $userRequestHandler)
    {
        # Création d'un nouvel utilisateur
        $user = new UserRequest();

        # Création du Formulaire
        $form = $this->createForm(UserType::class, $user)
            ->handleRequest($request);

        # Vérification et Traitement du Formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            # Enregistrement de l'utilisateur
            $user = $userRequestHandler->registerAsUser($user);

            # Flash Messages
            $this->addFlash('notice','Félicitation, vous pouvez vous connecter !');

            # Redirection
            return $this->redirectToRoute('security_login');

        }

        # Affichage du Formulaire dans la vue
        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

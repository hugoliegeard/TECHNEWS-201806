<?php

namespace App\Controller\TechNews\Security;


use App\Form\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * Connexion d'un Utilisateur
     * @Route("/connexion", name="security_login")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {

        /*
         * Si notre utilisateur est déjà authentifié,
         * on le redirige sur la page d'accueil.
         */
        if ($this->getUser()) {
            return  $this->redirectToRoute('index');
        }

        # Récupération du Formulaire
        $form = $this->createForm(LoginType::class, [
            'email' => $authenticationUtils->getLastUsername()
        ]);

        # Récupération du message d'erreur s'il y en a un.
        $error = $authenticationUtils->getLastAuthenticationError();

        # Dernier email saisie par l'utilisateur
        # $lastEmail = $authenticationUtils->getLastUsername();

        # Transmission à la vue
        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
    }

    /**
     * Vous pourriez définir aussi ici,
     * votre logique, mot de passe oublié,
     * réinitialisation du mot de passe et
     * Email de Validation.
     */
}
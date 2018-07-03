<?php

namespace App\Controller\TechNews;


use App\Newsletter\NewsletterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewsletterController extends Controller
{
    /**
     * Affichage d'une Modal Newsletter
     */
    public function newsletter()
    {
        # Récupération du Formulaire
        $form = $this->createForm(NewsletterType::class);

        # Affichage de la Newsletter
        return $this->render('newsletter/_modal.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
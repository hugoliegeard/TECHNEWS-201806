<?php

namespace App\User\EventListener;


use App\Entity\Newsletter;
use App\Entity\User;
use App\User\UserEvent;
use App\User\UserEvents;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class UserSubscriber implements EventSubscriberInterface
{

    private $manager;

    /**
     * UserSubscriber constructor.
     * @param $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }


    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        # Récupération de l'utilisateur
        $user = $event->getAuthenticationToken()->getUser();

        # Mettre à jour la date de dernière connexion
        if ($user instanceof User) {

            # Mise à jour du Timestamp
            $user->setLastConnectionDate();

            # Sauvegarde en BDD
            $this->manager->flush();
        }
    }

    /**
     * Lorsqu'un utilisateur s'inscrit, on le
     * rajoute automatiquement à la newsletter.
     * @param UserEvent $event
     */
    public function onCreatedUser(UserEvent $event)
    {
        $newsletter = new Newsletter();
        $newsletter->setEmail($event->getUser()->getEmail());
        $this->manager->persist($newsletter);
        $this->manager->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
            UserEvents::USER_CREATED => 'onCreatedUser'
        ];
    }

}
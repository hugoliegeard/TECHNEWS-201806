<?php

namespace App\User;


use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserRequestHandler
{

    private $manager, $userFactory, $dispatcher;

    /**
     * UserRequestHandler constructor.
     * @param ObjectManager $manager
     * @param UserFactory $userFactory
     * @param EventDispatcherInterface $dispatcher
     * @internal param UserFactory $user
     */
    public function __construct(ObjectManager $manager, UserFactory $userFactory, EventDispatcherInterface $dispatcher)
    {
        $this->manager = $manager;
        $this->userFactory = $userFactory;
        $this->dispatcher = $dispatcher;
    }

    public function registerAsUser(UserRequest $userRequest): User
    {
        # On appel notre Factory pour créer notre Objet User
        $user = $this->userFactory->createFromUserRequest($userRequest);

        # On sauvegarde en BDD notre User
        $this->manager->persist($user);
        $this->manager->flush();

        # On emet notre évènement
        $this->dispatcher->dispatch(UserEvents::USER_CREATED, new UserEvent($user));

        # On retourne le nouvel utilisateur.
        return $user;
    }

    public function registerAsAuthor()
    {
    }

    public function registerAsAdmin()
    {
    }
}
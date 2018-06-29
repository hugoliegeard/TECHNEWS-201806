<?php

namespace App\User;


use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class UserRequestHandler
{

    private $manager, $userFactory;

    /**
     * UserRequestHandler constructor.
     * @param ObjectManager $manager
     * @param UserFactory $userFactory
     * @internal param UserFactory $user
     */
    public function __construct(ObjectManager $manager, UserFactory $userFactory)
    {
        $this->manager = $manager;
        $this->userFactory = $userFactory;
    }

    public function registerAsUser(UserRequest $userRequest): User
    {
        # On appel notre Factory pour créer notre Objet User
        $user = $this->userFactory->createFromUserRequest($userRequest);

        # On sauvegarde en BDD notre User
        $this->manager->persist($user);
        $this->manager->flush();

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
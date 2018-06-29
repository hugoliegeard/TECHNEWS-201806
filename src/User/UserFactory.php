<?php
namespace App\User;


use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactory
{

    private $encoder;

    /**
     * UserFactory constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param UserRequest $request
     * @return User
     */
    public function createFromUserRequest(UserRequest $request): User
    {
        $user = new User();
        $user->setFirstname($request->getFirstname());
        $user->setLastname($request->getLastname());
        $user->setEmail($request->getEmail());
        $user->setRoles($request->getRoles());
        $user->setPassword($this->encoder->encodePassword($user, $request->getPassword()));

        return $user;
    }
}
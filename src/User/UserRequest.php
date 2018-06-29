<?php

namespace App\User;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserRequest
 * @package App\User
 */
class UserRequest
{
    /**
     * @Assert\NotBlank(message="Saisissez votre Prénom")
     * @Assert\Length(max="50", maxMessage="Votre prénom est trop long. {{ limit }} caractères max.")
     */
    private $firstname;

    /**
     * @Assert\NotBlank(message="Saisissez votre Nom")
     * @Assert\Length(max="50", maxMessage="Votre nom est trop long. {{ limit }} caractères max.")
     */
    private $lastname;

    /**
     * @Assert\NotBlank(message="Saisissez votre Email")
     * @Assert\Length(max="80", maxMessage="Votre email est trop long. {{ limit }} caractères max.")
     * @Assert\Email(message="Vérifiez votre email.")
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Saisissez votre Prénom")
     * @Assert\Length(
     *     min="8",
     *     minMessage="Votre mot de passe est trop court. {{ limit }} caractères min.",
     *     max="20",
     *     maxMessage="Votre mot de passe est trop long. {{ limit }} caractères max."
     * )
     */
    private $password;

    /**
     * @Assert\IsTrue(message="Vous devez valider nos CGU.")
     */
    private $conditions;
    private $registrationDate;
    private $roles;

    /**
     * UserRequest constructor.
     * @param string $role
     * @internal param $roles
     */
    public function __construct(string $role = 'ROLE_USER')
    {
        $this->registrationDate = new \DateTime();
        $this->roles[] = $role;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @return \DateTime
     */
    public function getRegistrationDate(): \DateTime
    {
        return $this->registrationDate;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param mixed $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * @param \DateTime $registrationDate
     */
    public function setRegistrationDate(\DateTime $registrationDate)
    {
        $this->registrationDate = $registrationDate;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles[] = $roles;
    }
}

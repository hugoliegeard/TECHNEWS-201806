<?php

namespace App\Command;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserManagerCommand extends Command
{

    private $io, $em, $users;

    public function __construct($name = null, EntityManagerInterface $manager)
    {
        parent::__construct($name);
        $this->em = $manager;
        $this->users = $manager->getRepository(User::class)->findAll();
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:user-manager')
            // the short description shown while running "php bin/console list"
            ->setDescription('Gestion de nos utilisateurs')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Cette commande permet de voir la liste des utilisateurs et leurs attribuer des rôles.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        # https://symfony.com/doc/current/console/style.html#helper-methods
        $this->io = new SymfonyStyle($input, $output);

        # Affiche un Titre
        $this->io->title('Bienvenu dans le gestionnaire des utilisateurs');

        # $output->writeln("<error>ID Utilisateur introuvable !</error>");
        # $this->io->error('ID Utilisateur introuvable !');
        # $this->io->section('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        # $this->io->note('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        # $this->io->caution('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        # $this->io->success('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        # $this->io->warning('Lorem ipsum dolor sit amet, consectetur adipiscing elit.');

        # $this->io->table(
        #     array('Header 1', 'Header 2'),
        #     array(
        #         array('Cell 1-1', 'Cell 1-2'),
        #         array('Cell 2-1', 'Cell 2-2'),
        #         array('Cell 3-1', 'Cell 3-2'),
        #     )
        # );

        # $this->io->choice('Quelle est la couleur du cheval blanc de Napoléon ?',
        #     array('Bleu', 'Vert', 'Blanc'), 'Bleu');

        # CONSIGNE : Afficher la liste des utilisateurs
        # BONUS : Ajouter un ROLE à un Utilisateur.

        $this->displayMenu();

    }

    private function displayMenu()
    {

        while (true) {

            $input = $this->io->choice(
                'Sélectionnez une action',
                [
                    'Afficher la liste des utilisateurs',
                    'Ajouter un rôle (Nécessite un ID Utilisateur)',
                    'Quitter'
                ]
            );

            switch ($input) {
                case 'Afficher la liste des utilisateurs':
                    $this->displayUserList();
                    break;

                case 'Ajouter un rôle (Nécessite un ID Utilisateur)':
                    $this->addUserRole();
                    break;

                case 'Quitter':
                    exit();
                    break;
            }

        }

    }

    private function displayUserList()
    {
        $display = [];

        # Récupération des users dans la BDD
//        $users = $this->em->getRepository(User::class)->findAll();

        foreach ($this->users as &$user) {
            $display[] = [
                $user->getId(),
                $user->getFirstname() . ' ' . $user->getLastname(),
                $user->getEmail(),
                join(' + ', $user->getRoles())
            ];
        }

        $this->io->table(['ID', 'FULLNAME', 'EMAIL', 'ROLES'], $display);
    }

    private function addUserRole()
    {
        # Demande de l'ID
        $id = $this->io->ask('Saisissez un ID Utilisateur');

        # Récupération du User
        $user = $this->em->getRepository(User::class)->find($id);

        # On vérifie qu'on a bien eu un utilisateur
        # Sinon on stop la fonction.
        if (!$user) {
            $this->io->error("L'ID $id n'existe pas !");
            return;
        }

        $roles = [
            'ROLE_USER',
            'ROLE_AUTHOR',
            'ROLE_EDITOR',
            'ROLE_CORRECTOR',
            'ROLE_PUBLISHER',
            'ROLE_ADMIN'
        ];

        $role = $this->io->choice(
          'Quel rôle souhaitez-vous ajouter ?',
          array_diff($roles, $user->getRoles())
            #$roles
        );

        # On vérifie que l'utilisateur n'a pas déjà ce rôle.
        # if (!$user->addRole($role)) {
        #     $this->io->caution('Cet utilisateur à déjà ce rôle.');
        # } else {
        #     # On sauvegarde en base
            $user->addRole($role);
            $this->em->flush();

            # Envoyer un message de confirmation
            $this->io->success("Le rôle $role a bien été attribué à ". $user->getEmail());
        # }

    }

}
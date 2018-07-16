<?php

namespace App\Article;


use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Registry;

class ArticleWorkflowHandler
{

    private $workflows, $manager;

    public function __construct(Registry $workflows, ObjectManager $manager)
    {
        $this->workflows = $workflows;
        $this->manager = $manager;
    }

    public function handle(Article $article, string $status): void
    {
        # Récupération du Workflow
        $workflow = $this->workflows->get($article);

        # Récupération de Doctrine
        $em = $this->manager;

        # Changement du Workflow
        $workflow->apply($article, $status);

        # Insertion en BDD
        $em->flush();

        # Publication de l'article si possible
        if ($workflow->can($article, 'to_be_published')) {
            $workflow->apply($article, 'to_be_published');
            $em->flush();
        }
    }
}
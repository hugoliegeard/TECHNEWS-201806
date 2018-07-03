<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 27/06/2018
 * Time: 12:33
 */

namespace App\Service\Twig;


use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{

    private $em;
    private $session;
    public const NB_SUMMARY_CHAR = 170;

    /**
     * AppExtension constructor.
     * @param EntityManagerInterface $manager
     * @param SessionInterface $session
     * @internal param $em
     */
    public function __construct(EntityManagerInterface $manager, SessionInterface $session)
    {
        $this->em = $manager;
        $this->session = $session;
    }

    public function getFilters()
    {
        return [
            new \Twig_Filter('summary', function ($text) {

                # Supprimer les balises HTML
                $string = strip_tags($text);

                # Si ma chaine est supérieur à 170...
                # Je poursuis, sinon c'est inutile
                if (strlen($string) > self::NB_SUMMARY_CHAR) {

                    # Je coupe ma chaine à 170
                    $stringCut = substr($string, 0, self::NB_SUMMARY_CHAR);

                    # Je m'assure de ne pas couper de mot
                    $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';

                }

                # On retourne l'accroche
                return $string;

            }, array('is_safe' => array('html')))
        ];
    }


    public function getFunctions()
    {
        return [
            new \Twig_Function('getCategories', function () {
                return $this->em->getRepository(Category::class)
                    ->findCategoriesHavingArticles();
            }),
            new \Twig_Function('isUserInvited', function() {
                return $this->session->get('inviteUserModal');
            })
        ];
    }

}
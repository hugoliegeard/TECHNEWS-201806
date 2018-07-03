<?php

namespace App\Newsletter;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class NewsletterSubscriber implements EventSubscriberInterface
{

    private $session;

    /**
     * NewsletterSubscriber constructor.
     * @param $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        # On s'assure que loa requête viens de l'utilisateur et non de Symfony !
        if(!$event->isMasterRequest() || $event->getRequest()->isXmlHttpRequest()) {
            return ;
        }

        /*
         * On propose à l'utilisateur de s'inscrire
         * à la newsletter à partir de la troisième
         * page visitée.
         */
        # $session = $event->getRequest()->getSession();
        $this->session->set('countUserPages',
            $this->session->get('countUserPages', 0) + 1);

        # Inviter l'utilisateur au bout de la 3ème page consulté
        if($this->session->get('countUserPages') === 3) {
            $this->session->set('inviteUserModal', true);
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        # On s'assure que loa requête viens de l'utilisateur et non de Symfony !
        if(!$event->isMasterRequest() || $event->getRequest()->isXmlHttpRequest()) {
            return ;
        }

        # On passe à false l'inviteUserModal
        if($this->session->get('countUserPages') >= 3) {
            $this->session->set('inviteUserModal', false);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::RESPONSE => 'onKernelResponse'
        ];
    }
}
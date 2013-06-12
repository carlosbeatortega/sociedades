<?php
namespace Sociedad\SociosBundle\Listener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Routing\Router;
class LoginListener
{
    private $contexto, $router, $sociedad = null;
    public function __construct(SecurityContext $context,Router $router)
    {
        $this->router = $router;
        $this->contexto= $context;
    }
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
            $token = $event->getAuthenticationToken();
            $this->sociedad = $token->getUser()->getSociedadesId();
    }
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (null != $this->sociedad) {
            if($this->contexto->isGranted('ROLE_SUPERADMIN')) {
                $portada = $this->router->generate('sociedades_edit', array(
                'id' => $this->sociedad
                ));
            }
            else {
            
                $portada = $this->router->generate('sociedades_show', array(
                'id' => $this->sociedad
                ));
            }
            $event->setResponse(new RedirectResponse($portada));
        }
    }
}?>

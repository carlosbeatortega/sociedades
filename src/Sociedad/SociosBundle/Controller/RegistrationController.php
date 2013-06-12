<?php

namespace Sociedad\SociosBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

class RegistrationController extends BaseController
{
    public function registerAction()
    {
        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
        $em = $this->container->get('doctrine.orm.entity_manager');
        $sociedades =$em->getRepository('SociedadSociedadesBundle:Sociedades')->find($this->container->getParameter('sociedad.defecto'));
        $formHandler->SetSociedades($sociedades);
        $formHandler->SetDirImagenes($this->container->getParameter('sociedad.directorio.imagenes'));
        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();

            /*****************************************************
             * Add new functionality (e.g. log the registration) *
             *****************************************************/
            $this->container->get('logger')->info(
                sprintf('New user registration: %s', $user)
            );

            if ($confirmationEnabled) {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $route = 'fos_user_registration_check_email';
            } else {
                $response=new Response("");
                $this->authenticateUser($user,$response);
                $route = 'fos_user_registration_confirmed';
            }

            $this->setFlash('fos_user_success', 'registration.flash.user_created');
            $url = $this->container->get('router')->generate($route);

            return new RedirectResponse($url);
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register2.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
        ));
    }
}
?>

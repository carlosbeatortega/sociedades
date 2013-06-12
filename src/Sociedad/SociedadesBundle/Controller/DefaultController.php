<?php

namespace Sociedad\SociedadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Sociedad\SociedadesBundle\Entity\Sociedades;
use Sociedad\GridBundle\Entity\Cabeceras;
use Sociedad\GridBundle\Entity\Propiedades;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function portadaAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $Sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->sociedadesActivas($this->container->getParameter('sociedad.defecto'));
        $Columnas = array();
//                $em->getRepository('SociedadGridBundle:Propiedades')->PropiedadesPorUsuario(
//                $this->get('security.context')->getToken()->getUser()->getId(),
//                $this->get('security.context')->getToken()->getUser()->getIdUsuarios(),
//                "Portada");

        
        return $this->render(
                'SociedadSociedadesBundle:Default:portada.html.twig',
                array('entities' => $Sociedades,
                      'columnas' => $Columnas));        
    }
    function mapaGoogleAction($id){

        $em = $this->getDoctrine()->getEntityManager();
        $Sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($id);
        return $this->render(
                'SociedadGridBundle:Default:plano.html.twig',
                array('entities' => $Sociedades));        
    }    
    function friendsFacebookAction($id){

        return $this->render('SociedadSociosBundle:Default:friendsFacebook.html.twig');        
    }    
    
}

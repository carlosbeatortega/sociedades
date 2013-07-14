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
     * @Route("/{_locale}/idiomaportada", name="idiomaportada")
     * @Template()
     */
    public function idiomaportadaAction($_locale='es')
    {
        $session = $this->get('request')->getSession();
        $session->set('locale',$_locale);
        return $this->redirect($this->generateUrl('portada'));
    }
    public function portadaAction()
    { 
        $session = $this->get('request')->getSession();
        $locale = $session->get('locale');
        if(empty($locale)){
            $locale = $this->getRequest()->getLocale();
            $session->set('locale',$locale);
        }
        $this->getRequest()->setLocale($locale);
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

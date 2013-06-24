<?php

namespace Sociedad\ReservasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Sociedad\ReservasBundle\Entity\MesasPlantas;
use Sociedad\ReservasBundle\Entity\Reservas;
use Sociedad\ReservasBundle\Entity\MesasReservadas;
class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }
    public function grabamesaAction(){
        $request = $this->get('request');
        $mesa_id = $request->query->get('idmesa');
        $planta_id = $request->query->get('idplanta');
        $posx= $request->query->get('top');
        $posy= $request->query->get('left');

        $em = $this->getDoctrine()->getEntityManager();
        $mesas = $em->getRepository('SociedadReservasBundle:Mesas')->find($mesa_id);
        $plantas = $em->getRepository('SociedadReservasBundle:Plantas')->find($planta_id);
        
        $entity  = new MesasPlantas();
        $entity->setSociedadesId($this->get('security.context')->getToken()->getUser()->getSociedadesId());
        $entity->setMesas($mesas);
        $entity->setPlantas($plantas);
        $entity->setPosx($posx);
        $entity->setPosy($posy);
        $em->persist($entity);
        $em->flush();
        $response = new Response(json_encode($entity->getId()));
        
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        
        return $response;
        

        
        
        
    }
    public function borramesaAction(){
        $request = $this->get('request');
        $mesa_id = $request->query->get('idmesa');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SociedadReservasBundle:MesasPlantas')->find($mesa_id);
        
        
        if ($entity) {
            $em->remove($entity);
            $em->flush();
            $response = new Response(json_encode("si"));
        }else{
            $response = new Response(json_encode("no"));
            
        }
        

        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
        
        
    }

    public function reservarmesaAction(){
        $request = $this->get('request');
        $session = $this->get('request')->getSession();
        $hoy = $session->get('hoy');
        //$valor= substr($hoy,strrpos($hoy,"/")+1,4).'-'.substr($hoy,strrpos($hoy,"/")-2,2).'-'.substr($hoy,0,2);
        $hoy=str_replace( "/", "-", $hoy );
        $valor=date("Y-m-d",strtotime($hoy));
        
        $turno = $session->get('turno');
        $sociedades_id=$this->get('security.context')->getToken()->getUser()->getSociedadesId();
        $socio_id=$this->get('security.context')->getToken()->getUser()->getId();
        $mesa_id = $request->query->get('idmesa');

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SociedadReservasBundle:Reservas')->reservaSocioFecha($socio_id,$valor,$turno);
        $sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($sociedades_id);
        $mesa = $em->getRepository('SociedadReservasBundle:MesasPlantas')->find($mesa_id);
        
        if (!$entity) {
            $entity  = new Reservas();
            $entity->setSociedadesId($this->get('security.context')->getToken()->getUser()->getSociedadesId());
            $entity->setSocio($this->get('security.context')->getToken()->getUser());
            $entity->setSociosId($socio_id);
            $entity->setFechadesde(new \DateTime($valor));
            $entity->setFechahasta(new \DateTime($valor));
            $entity->setComida($turno);
            $entity->setComensales(1);
            $entity->setCalendarid('');
            $entity->setCalendario($sociedades->getCalendario());
            $em->persist($entity);
            $em->flush();
            $response = new Response(json_encode("si"));
            $id=$entity->getId();
            $reserva=$entity;
        }else{
            $response = new Response(json_encode("no"));
            $id=$entity[0]->getId();
            $reserva=$entity[0];
        }
        $mesasreservadas = $em->getRepository('SociedadReservasBundle:MesasReservadas')->findBy(array('reservas_id'=>$id,'sociedades_id'=>$sociedades_id,'mesasplantas_id'=>$mesa_id));
        if (!$mesasreservadas) {
            $mesasreservadas  = new MesasReservadas();
            $mesasreservadas->setSociedadesId($this->get('security.context')->getToken()->getUser()->getSociedadesId());
            $mesasreservadas->setReserva($reserva);
            $mesasreservadas->setMesaPlanta($mesa);
            $em->persist($mesasreservadas);
            $em->flush();
            $response = new Response(json_encode("si"));
            
        }else{
            $response = new Response(json_encode("no"));
            
        }
        

        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
        
        
    }
    
    public function borrareservamesaAction(){
        $request = $this->get('request');
        $mesa_id = $request->query->get('idmesa');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SociedadReservasBundle:MesasReservadas')->find($mesa_id);
        
        
        if ($entity) {
            $em->remove($entity);
            $em->flush();
            $response = new Response(json_encode("si"));
        }else{
            $response = new Response(json_encode("no"));
            
        }
        

        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
        
        
    }
    
}

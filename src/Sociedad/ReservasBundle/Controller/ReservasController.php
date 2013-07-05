<?php

namespace Sociedad\ReservasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sociedad\ReservasBundle\Entity\Reservas;
use Sociedad\ReservasBundle\Form\ReservasType;

/**
 * Reservas controller.
 *
 * @Route("/reservas")
 */
class ReservasController extends Controller
{
    /**
     * Lists all Reservas entities.
     *
     * @Route("/", name="reservas")
     * @Template()
     */
    public function indexAction()
    {
        $request = $this->get('request');
        $session = $this->get('request')->getSession();
        $plantaid = $session->get('plantaid');
        if (is_null($plantaid)) {
            $plantaid=0;
        }
        $hoy = $request->query->get('hoy');
        $turno = $request->query->get('turno');
        if (is_null($hoy)) {
            $hoy = $session->get('hoy');
        }else{
            $session->set('hoy', $hoy);
        }
        if (is_null($turno)) {
            $turno = $session->get('turno');
            if (is_null($turno)){
                $turno="Comida";
            }
            $session->set('turno', $turno);
        }else{
            $session->set('turno', $turno);
        }
        
        if (is_null($hoy)) {
            $hoy=date("Y-m-d");
            $diahoy=$hoy;
        }else{
            $hoy=str_replace( "/", "-", $hoy );
            $diahoy=date("Y-m-d",strtotime($hoy));
        }
        $request->query->set('hoy',$diahoy);
        $request->query->set('plantaid',$plantaid);
        $request->query->set('turno',$turno);
        $session->set('hoy', $diahoy);
        $session->set('plantaid',$plantaid);
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('security.context')->getToken()->getUser();
        $sociedades_id=$userManager->getSociedadesId();
        $socio_id=$userManager->getId();
        

        $entities = $em->getRepository('SociedadReservasBundle:Reservas')->reservaSociedadFecha($sociedades_id,$diahoy,$turno);
        $sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($sociedades_id);
        $plantas = $em->getRepository('SociedadReservasBundle:Plantas')->findBy(array('sociedades_id'=>$sociedades_id));
        $planta = $em->getRepository('SociedadReservasBundle:Plantas')->findBy(array('id'=>$plantaid));
        $mesasplantas = $em->getRepository('SociedadReservasBundle:MesasPlantas')->findBy(array('plantas_id'=>$plantaid,'sociedades_id'=>$sociedades_id));

        $misreservas = $em->getRepository('SociedadReservasBundle:Reservas')->reservaMesasPlantaSocioFecha($socio_id,$diahoy,$turno,$plantaid);
        $sinreservar = $em->getRepository('SociedadReservasBundle:MesasPlantas')->mesasNoReservadasFecha($sociedades_id,$diahoy,$turno,$plantaid);
        
        return array(
            'entities' => $entities,
            'sociedades'=>$sociedades,
            'plantas'=>$plantas,
            'plantafoto'=>$planta,
            'mesasplantas'=>$mesasplantas,
            'misreservas'=>$misreservas,
            'sinreservar'=>$sinreservar
        );
    }

    /**
     * Finds and displays a Reservas entity.
     *
     * @Route("/{id}/show", name="reservas_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadReservasBundle:Reservas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Reservas entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Reservas entity.
     *
     * @Route("/new", name="reservas_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Reservas();
        $request = $this->get('request');
        $session = $this->get('request')->getSession();
        $plantaid = $session->get('plantaid');
        $hoy=$session->get('hoy');
        if (is_null($hoy)) {
            $hoy=new \DateTime();
            $diahoy=$hoy;
        }else{
            $hoy=str_replace( "/", "-", $hoy );
            $diahoy=new \Datetime($hoy.' 00:00:00');
        }
        $turno=$session->get('turno');
        $request->query->set('plantaid',$plantaid);
        $userManager = $this->get('security.context')->getToken()->getUser();
        $sociedades_id=$userManager->getSociedadesId();
        $socio_id=$userManager->getId();
        $em = $this->getDoctrine()->getManager();
        $entity->setFechadesde($diahoy);
        $entity->setFechahasta($diahoy);
        $entity->setComida($turno);
        $entity->setSocio($userManager);
        $entity->setSociedadesId($sociedades_id);
        $form   = $this->createForm(new ReservasType(), $entity);
        

        $sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($sociedades_id);
        $plantas = $em->getRepository('SociedadReservasBundle:Plantas')->findBy(array('sociedades_id'=>$sociedades_id));
        $planta = $em->getRepository('SociedadReservasBundle:Plantas')->findBy(array('id'=>$plantaid));

        $misreservas = $em->getRepository('SociedadReservasBundle:Reservas')->reservaMesasPlantaSocioFecha($socio_id,$hoy,$turno,$plantaid);
        $sinreservar = $em->getRepository('SociedadReservasBundle:MesasPlantas')->mesasNoReservadasFecha($sociedades_id,$hoy,$turno,$plantaid);
        
        
        return array(
            'entity' => $entity,
            'sociedades' => $sociedades,
            'plantas'=>$plantas,
            'plantafoto'=>$planta,
            'misreservas'=>$misreservas,
            'sinreservar'=>$sinreservar,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Reservas entity.
     *
     * @Route("/create", name="reservas_create")
     * @Method("POST")
     * @Template("SociedadReservasBundle:Reservas:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Reservas();
        $form = $this->createForm(new ReservasType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setFechahasta($entity->getFechadesde());
            $sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($entity->getSocio()->getSociedadesId());
            $entity->setCalendarid('');
            $entity->setCalendario($sociedades->getCalendario());
            
            $em->persist($entity);
            $em->flush();

            $session = $this->get('request')->getSession();
            $plantaid = $session->get('plantaid');

            return $this->redirect($this->generateUrl('reservasplanta',array('plantaid' => $plantaid)));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Reservas entity.
     *
     * @Route("/{id}/edit", name="reservas_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadReservasBundle:Reservas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Reservas entity.');
        }
        $request = $this->get('request');
        $session = $this->get('request')->getSession();
        $plantaid = $session->get('plantaid');
        if($this->get('security.context')->getToken()->getUser()->getId()!=$entity->getSociosId()){
            print '<script language="JavaScript">';
            print 'alert("Operación no Permitida");';
            print '</script>';
            return $this->redirect($this->generateUrl('reservasplanta',array('plantaid' => $plantaid)));
            
        }
        $request->query->set('plantaid',$plantaid);
        $session->set('reservaid',$id);

        $sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($entity->getSociedadesId());
        $plantas = $em->getRepository('SociedadReservasBundle:Plantas')->findBy(array('sociedades_id'=>$entity->getSociedadesId()));
        $planta = $em->getRepository('SociedadReservasBundle:Plantas')->findBy(array('id'=>$plantaid));

        $misreservas = $em->getRepository('SociedadReservasBundle:Reservas')->reservaMesasPlantaSocioFecha($entity->getSociosId(),$entity->getFechadesde(),$entity->getComida(),$plantaid);
        $sinreservar = $em->getRepository('SociedadReservasBundle:MesasPlantas')->mesasNoReservadasFecha($entity->getSociedadesId(),$entity->getFechadesde(),$entity->getComida(),$plantaid);

        $invitados = $em->getRepository('SociedadReservasBundle:Invitados')->InvitadosEmail($id);
        
        $editForm = $this->createForm(new ReservasType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'sociedades' => $sociedades,
            'plantas'=>$plantas,
            'plantafoto'=>$planta,
            'invitados'=>$invitados,
            'misreservas'=>$misreservas,
            'sinreservar'=>$sinreservar,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Reservas entity.
     *
     * @Route("/{id}/update", name="reservas_update")
     * @Method("POST")
     * @Template("SociedadReservasBundle:Reservas:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadReservasBundle:Reservas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Reservas entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ReservasType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $request = $this->get('request');
            $session = $this->get('request')->getSession();
            $plantaid = $session->get('plantaid');

            return $this->redirect($this->generateUrl('reservasplanta',array('plantaid' => $plantaid)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Reservas entity.
     *
     * @Route("/{id}/delete", name="reservas_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);
        $request = $this->get('request');
        $session = $this->get('request')->getSession();
        $plantaid = $session->get('plantaid');

        //if ($form->isValid()) {
          if(true){  
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SociedadReservasBundle:Reservas')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Reservas entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('reservasplanta',array('plantaid' => $plantaid)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    /**
     * Lists all Mesas entities de una planta.
     *
     * @Route("/{plantaid}/", name="reservasplanta")
     * @Template()
     */
    public function reservasplantaAction($plantaid)
    {
        $session = $this->get('request')->getSession();
        $session->set('plantaid',$plantaid);
        return $this->redirect($this->generateUrl('reservas'));
    }    
}

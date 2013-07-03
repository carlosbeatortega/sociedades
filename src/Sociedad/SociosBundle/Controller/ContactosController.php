<?php

namespace Sociedad\SociosBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sociedad\SociosBundle\Entity\Contactos;
use Sociedad\ReservasBundle\Entity\Invitados;
use Sociedad\SociosBundle\Form\ContactosType;

/**
 * Contactos controller.
 *
 * @Route("/contactos")
 */
class ContactosController extends Controller
{
    /**
     * Lists all Contactos entities.
     *
     * @Route("/", name="contactos")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('request')->getSession();
        $reservaid=$session->get('reservaid');
        $invitados2=array();
        $userManager = $this->get('security.context')->getToken()->getUser();
        $socios_id=$userManager->getId();

        $entities = $em->getRepository('SociedadSociosBundle:Contactos')->findBy(array('socios_id'=>$socios_id));
        $sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->findBy(array('id'=>$userManager->getSociedadesId()));
        if($reservaid){
            $invitados = $em->getRepository('SociedadReservasBundle:Invitados')->findBy(array('reservas_id'=>$reservaid));
            foreach ($invitados as $invi) {
                $invitados2[$invi->getContactosId()]=$invi->getId();                
            }
        }

        return array(
            'entities' => $entities,
            'sociedades' => $sociedades,
            'invitados'  => $invitados2
        );
    }

    /**
     * Finds and displays a Contactos entity.
     *
     * @Route("/{id}/show", name="contactos_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadSociosBundle:Contactos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contactos entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Contactos entity.
     *
     * @Route("/new", name="contactos_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Contactos();
        $userManager = $this->get('security.context')->getToken()->getUser();
        $sociedades_id=$userManager->getSociedadesId();
        $em = $this->getDoctrine()->getManager();
        $entity->setSocios($userManager);
        $entity->setSociedadesId($sociedades_id);
        $entity->setSociosId($userManager->getId());

        
        $form   = $this->createForm(new ContactosType(), $entity);

        return array(
            'entity' => $entity,
            'socio' => $userManager,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Contactos entity.
     *
     * @Route("/create", name="contactos_create")
     * @Method("POST")
     * @Template("SociedadSociosBundle:Contactos:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Contactos();
        $form = $this->createForm(new ContactosType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $userManager = $this->get('security.context')->getToken()->getUser();
            $entity->setSocios($userManager);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('contactos'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Contactos entity.
     *
     * @Route("/{id}/edit", name="contactos_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('security.context')->getToken()->getUser();

        $entity = $em->getRepository('SociedadSociosBundle:Contactos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contactos entity.');
        }

        $editForm = $this->createForm(new ContactosType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'socio'       => $userManager,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Contactos entity.
     *
     * @Route("/{id}/update", name="contactos_update")
     * @Method("POST")
     * @Template("SociedadSociosBundle:Contactos:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadSociosBundle:Contactos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contactos entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ContactosType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('contactos_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Contactos entity.
     *
     * @Route("/{id}/delete", name="contactos_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

//        if ($form->isValid()) {
          if(true){  
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SociedadSociosBundle:Contactos')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Contactos entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('contactos'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    /**
     * Creates a new activacion Socios entity.
     *
     * @Route("/activa", name="contactos_activa")
     * @Method("GET")
     * @Template()
     */
    public function activaAction(Request $request)
    {
        $apo1=$request->query->all();
        if(empty($apo1)){
//            return $this->indexAction();
            return $this->redirect($this->generateUrl('contactos'));
        }
        $em = $this->getDoctrine()->getEntityManager();
        $session = $this->get('request')->getSession();
        $userManager = $this->get('security.context')->getToken()->getUser();        
        $apo=serialize($apo1);
        $entities = $em->getRepository('SociedadSociedadesBundle:Sociedades')->sociedadesActivas($this->container->getParameter('sociedad.defecto'));
        $reservas = $em->getRepository('SociedadReservasBundle:Reservas')->find($session->get('reservaid'));
        $hemodificado=false;
        foreach($apo1 as $idcontacto){
          $existe = $em->getRepository('SociedadReservasBundle:Invitados')->findby(array('reservas_id'=>$session->get('reservaid'),'contactos_id'=>$idcontacto));  
          if(!$existe){
            $dato = $em->getRepository('SociedadSociosBundle:Contactos')->find($idcontacto);  
            $entity  = new Invitados();
            $entity->setContacto($dato);
            $entity->setReserva($reservas);
            $entity->setSociosId($userManager->getId());
            $entity->setSociedadesId($userManager->getSociedadesId());
            $em->persist($entity);
            $em->flush();
            $hemodificado=true;
          }
          if($hemodificado){
            $reservas->setFechamodiValue();
            $em->persist($reservas);
            $em->flush();              
          }
        }
        return $this->redirect($this->generateUrl('reservas_edit',array('id' => $session->get('reservaid'))));
    }
}

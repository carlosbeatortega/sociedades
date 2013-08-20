<?php

namespace Sociedad\ReservasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sociedad\ReservasBundle\Entity\Plantas;
use Sociedad\ReservasBundle\Form\PlantasType;

/**
 * Plantas controller.
 *
 * @Route("/plantas")
 */
class PlantasController extends Controller
{
    /**
     * Lists all Plantas entities.
     *
     * @Route("/", name="plantas")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('request')->getSession();
        $userManager = $this->get('security.context')->getToken()->getUser();
        $sociedades_id=$userManager->getSociedadesId();
        $entities = $em->getRepository('SociedadReservasBundle:Plantas')->plantas($sociedades_id);
        $sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($sociedades_id);
        $request = $this->get('request');        
        $request->query->set('plantaid',0);
        $this->getRequest()->setLocale($this->get('request')->getSession()->get('locale'));
        if($entities){
            $request->query->set('plantaid',$entities[0]->getId());
        }

        return array(
            'entities' => $entities,
            'sociedades' => $sociedades
        );

    }

    /**
     * Finds and displays a Plantas entity.
     *
     * @Route("/{id}/show", name="plantas_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadReservasBundle:Plantas')->find($id);
        $this->getRequest()->setLocale($this->get('request')->getSession()->get('locale'));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plantas entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Plantas entity.
     *
     * @Route("/new", name="plantas_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Plantas();
        $form   = $this->createForm(new PlantasType(), $entity);
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('request')->getSession();
        $userManager = $this->get('security.context')->getToken()->getUser();
        $sociedades_id=$userManager->getSociedadesId();
        $sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($sociedades_id);
        $plantas = $em->getRepository('SociedadReservasBundle:Plantas')->plantas($sociedades_id,1);
        $request = $this->get('request');        
        $request->query->set('plantaid',0);
        if($plantas){
            $request->query->set('plantaid',$plantas[0]->getId());
        }
        $this->getRequest()->setLocale($this->get('request')->getSession()->get('locale'));

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'sociedades'=>$sociedades
        );

    }

    /**
     * Creates a new Plantas entity.
     *
     * @Route("/create", name="plantas_create")
     * @Method("POST")
     * @Template("SociedadReservasBundle:Plantas:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Plantas();
        $form = $this->createForm(new PlantasType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $session = $this->get('request')->getSession();
            $userManager = $this->get('security.context')->getToken()->getUser();
            $entity->setSociedadesId($userManager->getSociedadesId());
            $entity->subirFoto($this->container->getParameter('sociedad.directorio.imagenes'),$this->container->getParameter('sociedad.nofotoplanta'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

//            return $this->redirect($this->generateUrl('plantas'));
        }
        return $this->redirect($this->generateUrl('plantas'));
        
//        $em = $this->getDoctrine()->getManager();
//        $userManager = $this->get('security.context')->getToken()->getUser();
//        $sociedades_id=$userManager->getSociedadesId();
//        $sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($sociedades_id);
//
//        return array(
//            'entity' => $entity,
//            'form'   => $form->createView(),
//            'sociedades'=>$sociedades
//        );
    }

    /**
     * Displays a form to edit an existing Plantas entity.
     *
     * @Route("/{id}/edit", name="plantas_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadReservasBundle:Plantas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plantas entity.');
        }
        $session = $this->get('request')->getSession();
        $session->set('foto',$entity->getFoto());
        $userManager = $this->get('security.context')->getToken()->getUser();
        $sociedades_id=$userManager->getSociedadesId();
        $sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($sociedades_id);

        $editForm = $this->createForm(new PlantasType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        $this->getRequest()->setLocale($this->get('request')->getSession()->get('locale'));

        return array(
            'entity'      => $entity,
            'sociedades'   => $sociedades,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Plantas entity.
     *
     * @Route("/{id}/update", name="plantas_update")
     * @Method("POST")
     * @Template("SociedadReservasBundle:Plantas:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadReservasBundle:Plantas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plantas entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PlantasType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $foto=$entity->getFoto();
            $formula=preg_match_all('/(:)/', $foto,$salida);
            if($formula>0){
                $entity->subirFoto($this->container->getParameter('sociedad.directorio.imagenes'),$this->container->getParameter('sociedad.nofotoplanta'));
            }else{
                $session = $this->get('request')->getSession();
                $entity->setFoto($session->get('foto'));
            }
            
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('plantas'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Plantas entity.
     *
     * @Route("/{id}/delete", name="plantas_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        //if ($form->isValid()) {
          if(true){  
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SociedadReservasBundle:Plantas')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Plantas entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('plantas'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}

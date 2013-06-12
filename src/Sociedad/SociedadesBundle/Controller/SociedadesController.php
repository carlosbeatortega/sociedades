<?php

namespace Sociedad\SociedadesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sociedad\SociedadesBundle\Entity\Sociedades;
use Sociedad\SociedadesBundle\Form\SociedadesType;

/**
 * Sociedades controller.
 *
 * @Route("/sociedades")
 */
class SociedadesController extends Controller
{
    /**
     * Lists all Sociedades entities.
     *
     * @Route("/", name="sociedades")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SociedadSociedadesBundle:Sociedades')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Sociedades entity.
     *
     * @Route("/{id}/show", name="sociedades_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $userManager = $this->get('security.context')->getToken()->getUser();
        $sociedades_id=$userManager->getSociedadesId();
        //habra que discriminar por ROLE si usar id o usuarios_id
        $entity = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($sociedades_id);
        $plantas = $em->getRepository('SociedadReservasBundle:Plantas')->plantas($sociedades_id,1);
        $request = $this->get('request');        
        $request->query->set('plantaid',0);
        if($plantas){
            $request->query->set('plantaid',$plantas[0]->getId());
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sociedades entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Sociedades entity.
     *
     * @Route("/new", name="sociedades_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Sociedades();
        $form   = $this->createForm(new SociedadesType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Sociedades entity.
     *
     * @Route("/create", name="sociedades_create")
     * @Method("POST")
     * @Template("SociedadSociedadesBundle:Sociedades:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Sociedades();
        $form = $this->createForm(new SociedadesType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
//            $someNewFilename = "";
//
//            $form['foto']->getData()->move($dir, $someNewFilename);            
            $entity->subirFoto($this->container->getParameter('sociedad.directorio.imagenes'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sociedades_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Sociedades entity.
     *
     * @Route("/{id}/edit", name="sociedades_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sociedades entity.');
        }
        $session = $this->get('request')->getSession();
        $session->set('foto',$entity->getFoto());
        $editForm = $this->createForm(new SociedadesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Sociedades entity.
     *
     * @Route("/{id}/update", name="sociedades_update")
     * @Method("POST")
     * @Template("SociedadSociedadesBundle:Sociedades:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sociedades entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new SociedadesType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $foto=$entity->getFoto();
            $formula=preg_match_all('/(:)/', $foto,$salida);
            if($formula>0){
                $entity->subirFoto($this->container->getParameter('sociedad.directorio.imagenes'));
            }else{
                $session = $this->get('request')->getSession();
                $entity->setFoto($session->get('foto'));
                
            }
                
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sociedades'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Sociedades entity.
     *
     * @Route("/{id}/delete", name="sociedades_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

//        if ($form->isValid()) {
          if(true){  
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Sociedades entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sociedades'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}

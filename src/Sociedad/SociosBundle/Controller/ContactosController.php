<?php

namespace Sociedad\SociosBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sociedad\SociosBundle\Entity\Contactos;
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

        $entities = $em->getRepository('SociedadSociosBundle:Contactos')->findAll();

        return array(
            'entities' => $entities,
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
        $form   = $this->createForm(new ContactosType(), $entity);

        return array(
            'entity' => $entity,
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('contactos_show', array('id' => $entity->getId())));
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

        $entity = $em->getRepository('SociedadSociosBundle:Contactos')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contactos entity.');
        }

        $editForm = $this->createForm(new ContactosType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
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
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
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
}
<?php

namespace Sociedad\ReservasBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sociedad\ReservasBundle\Entity\Mesas;
use Sociedad\ReservasBundle\Form\MesasType;

/**
 * Mesas controller.
 *
 * @Route("/mesas")
 */
class MesasController extends Controller
{
    /**
     * Lists all Mesas entities.
     *
     * @Route("/", name="mesas")
     * @Template()
     */
    public function indexAction()
    {
        $session = $this->get('request')->getSession();
        $plantaid = $session->get('plantaid');
        if (is_null($plantaid)) {
            $plantaid=0;
        }
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('security.context')->getToken()->getUser();
        $sociedades_id=$userManager->getSociedadesId();

        $entities = $em->getRepository('SociedadReservasBundle:Mesas')->mesas($sociedades_id);
        $sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($sociedades_id);
        $plantas = $em->getRepository('SociedadReservasBundle:Plantas')->findBy(array('sociedades_id'=>$sociedades_id));
        $planta = $em->getRepository('SociedadReservasBundle:Plantas')->findBy(array('id'=>$plantaid));
        $mesasplantas = $em->getRepository('SociedadReservasBundle:MesasPlantas')->findBy(array('plantas_id'=>$plantaid,'sociedades_id'=>$sociedades_id));

        return array(
            'entities' => $entities,
            'sociedades'=>$sociedades,
            'plantas'=>$plantas,
            'plantafoto'=>$planta,
            'mesasplantas'=>$mesasplantas
        );
    }

    /**
     * Finds and displays a Mesas entity.
     *
     * @Route("/{id}/show", name="mesas_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadReservasBundle:Mesas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mesas entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Mesas entity.
     *
     * @Route("/new", name="mesas_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Mesas();
        $userManager = $this->get('security.context')->getToken()->getUser();
        $sociedades_id=$userManager->getSociedadesId();
        $form   = $this->createForm(new MesasType(), $entity);
        $em = $this->getDoctrine()->getManager();
        $sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($sociedades_id);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'sociedades'=>$sociedades
        );
    }

    /**
     * Creates a new Mesas entity.
     *
     * @Route("/create", name="mesas_create")
     * @Method("POST")
     * @Template("SociedadReservasBundle:Mesas:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Mesas();
        $form = $this->createForm(new MesasType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $userManager = $this->get('security.context')->getToken()->getUser();
            $entity->setSociedadesId($userManager->getSociedadesId());
//            $plantaId=1;
//            $planta = $em->getRepository('SociedadReservasBundle:Plantas')->find($plantaId);
//            $entity->setPlantas($planta);
            $entity->subirFoto($this->container->getParameter('sociedad.directorio.imagenes'));
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mesas'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Mesas entity.
     *
     * @Route("/{id}/edit", name="mesas_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadReservasBundle:Mesas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mesas entity.');
        }
        $userManager = $this->get('security.context')->getToken()->getUser();
        $sociedades_id=$userManager->getSociedadesId();
        $sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($sociedades_id);

        $editForm = $this->createForm(new MesasType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'sociedades'   => $sociedades,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Mesas entity.
     *
     * @Route("/{id}/update", name="mesas_update")
     * @Method("POST")
     * @Template("SociedadReservasBundle:Mesas:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadReservasBundle:Mesas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mesas entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new MesasType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $entity->subirFoto($this->container->getParameter('sociedad.directorio.imagenes'));
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mesas'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Mesas entity.
     *
     * @Route("/{id}/delete", name="mesas_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        //if ($form->isValid()) {
          if(true){  
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SociedadReservasBundle:Mesas')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Mesas entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('mesas'));
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
     * @Route("/{plantaid}/", name="mesasplanta")
     * @Template()
     */
    public function mesasplantaAction($plantaid)
    {
        $session = $this->get('request')->getSession();
        $session->set('plantaid',$plantaid);
        return $this->redirect($this->generateUrl('mesas'));
    }    
}

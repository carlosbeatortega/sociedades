<?php

namespace Sociedad\SociosBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sociedad\SociosBundle\Entity\Socios;
use Sociedad\SociosBundle\Form\SociosType;

/**
 * Socios controller.
 *
 * @Route("/socios")
 */
class SociosController extends Controller
{
    /**
     * Lists all Socios entities.
     *
     * @Route("/", name="socios")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('security.context')->getToken()->getUser();
        $sociedades_id=$userManager->getSociedadesId();

        $entities = $em->getRepository('SociedadSociosBundle:Socios')->findBy(array('sociedades_id'=>$sociedades_id));
        $sociedades = $em->getRepository('SociedadSociedadesBundle:Sociedades')->find($sociedades_id);

        return array(
            'entities' => $entities,
            'sociedades' => $sociedades
        );
    }

    /**
     * Finds and displays a Socios entity.
     *
     * @Route("/{id}/show", name="socios_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadSociosBundle:Socios')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Socios entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Socios entity.
     *
     * @Route("/new", name="socios_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Socios();
        $form   = $this->createForm(new SociosType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Socios entity.
     *
     * @Route("/create", name="socios_create")
     * @Method("POST")
     * @Template("SociedadSociosBundle:Socios:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Socios();
        $form = $this->createForm(new SociosType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $entity->subirFoto($this->container->getParameter('sociedad.directorio.imagenes'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('socios'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Socios entity.
     *
     * @Route("/{id}/edit", name="socios_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadSociosBundle:Socios')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Socios entity.');
        }
        $session = $this->get('request')->getSession();
        $session->set('foto',$entity->getFoto());
        $reservas = $em->getRepository('SociedadReservasBundle:Reservas')->reservaSocioFuturas($entity->getId());

        $editForm = $this->createForm(new SociosType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'reservas'      => $reservas,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Socios entity.
     *
     * @Route("/{id}/update", name="socios_update")
     * @Method("POST")
     * @Template("SociedadSociosBundle:Socios:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SociedadSociosBundle:Socios')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Socios entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new SociosType(), $entity);
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

            return $this->redirect($this->generateUrl('socios'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Socios entity.
     *
     * @Route("/{id}/delete", name="socios_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        //if ($form->isValid()) {
        if(true){
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SociedadSociosBundle:Socios')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Socios entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('socios'));
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
     * @Route("/activa", name="socios_activa")
     * @Method("GET")
     * @Template()
     */
    public function activaAction(Request $request)
    {
        $apo1=$request->query->all();
        if(empty($apo1)){
//            return $this->indexAction();
            return $this->redirect($this->generateUrl('socios'));
        }
        
        $apo=serialize($apo1);
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('SociedadSociedadesBundle:Sociedades')->sociedadesActivas($this->container->getParameter('sociedad.defecto'));
        
        $datos = array();
        foreach($apo1 as $idsocio){
          $datos[] = $em->getRepository('SociedadSociosBundle:Socios')->find($idsocio);  
        }
        
        return $this->render('SociedadSociosBundle:Socios:invitacion.html.twig', array(
            'entities' => $entities,'socios'=>$apo, 'datos' => $datos
        ));
        
    }
    /**
     * Creates a new activacion Socios entity.
     *
     * @Route("/activaSociedades/{socios}", name="socios_activa_sociedades")
     * @Method("GET")
     * @Template()
     */
    public function activaSociedadesAction($socios)
    {
        $request = $this->get('request');
        $apo1=$request->query->all();
        if(empty($apo1)){
//            return $this->indexAction();
            return $this->redirect($this->generateUrl('socios'));
        }
        $tablasocios=unserialize($socios);
        $em = $this->getDoctrine()->getEntityManager();
        foreach ($tablasocios as $key => $value) {
            foreach ($apo1 as $key => $value2) {
                $sociosactivos=$em->getRepository('SociedadSociosBundle:Socios')->find($value);
                if($sociosactivos){
                    $sociedades=$em->getRepository('SociedadSociedadesBundle:Sociedades')->find($value2);
                    if($sociedades){
                        $sociosactivos->setSociedades($sociedades);
                        $sociosactivos->setEnabled(true);
                        $em->persist($sociosactivos);
                        $em->flush();
                    }
                }
            }
        }
//        return $this->indexAction();
            return $this->redirect($this->generateUrl('socios'));
    }
    
}

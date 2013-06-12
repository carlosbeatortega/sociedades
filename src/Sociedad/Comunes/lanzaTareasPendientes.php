<?php

use aner\posicionBundle\Entity\tareas;
use aner\posicionBundle\Entity\lin_resul;

require_once __DIR__.'/../comunes/Analizador.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lanzaTareasPendientes
 *
 * @author CARLOS
 */
class lanzaTareasPendientes{
    //put your code here

    public function __construct(){
        
    }
    public function IniciaAnalisisfecha(){
        $em = $this->getDoctrine()->getEntityManager();
        $fecha=date();
        $tareas=$em->createQuery('SELECT p FROM aner\posicionBundle\Entity\tareas p
                                    WHERE p.fecha>: mifecha')
                    ->setParameters(array('mifecha'=>$fecha))
                    ->getResult();
       foreach ($tareas as $tarea) {
           $this->AnalizaUnId($tarea);
           break;
       } 
    }
    public function IniciaAnalisisPagina($id){
        $em = $this->getDoctrine()->getEntityManager();
        $fecha=date();
        $tareas=$em->createQuery('SELECT p FROM aner\posicionBundle\Entity\tareas p
                                    WHERE p.paginas_id> :miid')
                    ->setParameters(array('miid'=>$id))
                    ->getResult();
       foreach ($tareas as $tarea) {
           $this->AnalizaUnId($tarea);
       } 
    }
    public function AnalizaUnId($mitarea){
       $em = $this->getDoctrine()->getEntityManager();
       $pagina = $em->getRepository('posicionBundle:paginas')->find($mitarea->getPaginasId());
       $url=$pagina->getUrl();
       $buscador=$pagina->getBuscador();
        //$phpKeywordAnalyser = $this->get('aner.posicionBundle.recuperaposicion'); //definido como servicio en symfony/app/config/config.yml
       $phpKeywordAnalyser = new \PhpKeywordAnalyser($mitarea->getKeyword(), $url,$buscador);
       $phpKeywordAnalyser->initSpider();
       if($phpKeywordAnalyser->weboriginal!="" && $phpKeywordAnalyser->webcontador!=0){
            $entity = new lin_resul();
            $entity->setTareasId($id);
            $entity->setFechahora(new \DateTime());
            $entity->setPosicion($phpKeywordAnalyser->webcontador);
            $entity->setWeb($phpKeywordAnalyser->weboriginal);
            $em->persist($entity);
        }
        $mitarea->setFecha(new \DateTime());
        $em->persist($entity);
        $em->flush();
        
        
        
    } 
    
}

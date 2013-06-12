<?php
namespace aner\posicionBundle\comunes;
use aner\posicionBundle\Entity\Grupos;
use aner\posicionBundle\Entity\usuarios;

/**
 * Description of controlAcceso
 *
 * @author CARLOS
 */
class controlAcceso {
    public function __construct(){
        
    }
    public function permisos($id1=0,$id2=0,$cadena='',$em=''){
        
        switch ($cadena) {
            case 'grupos':
            case 'tareastodos':
            case 'paginas':
                 //$id1 es un objeto User
                //$id2 es el id de usuario a validad
                //$em es el entitymanager
                if($id1->getId()!=$id2){
                    $myrole=$id1->getRoles();
                    if($myrole[0]=="ROLE_ADMIN" || $myrole[0]=="ROLE_SUPER_ADMIN"){
                        $usuarios = $em->getRepository('posicionBundle:Usuarios')->findOneBy(array('id'=>$id2));
                        if($usuarios && $usuarios->getIdUsuarios()==$id1->getId()){
                            return $id2;
                        }
                        return $id1->getId();
                    }else{
                        return $id1->getId();
                    }
                }else{
                    return $id2;
                }

                break;
            case 'tareas':
                 //$id1 es un objeto User
                //$id2 es el id de usuario al que pertenece la pagina
                //$em es el entitymanager
                if($id1->getId()!=$id2){
                    $myrole=$id1->getRoles();
                    if($myrole[0]=="ROLE_ADMIN" || $myrole[0]=="ROLE_SUPER_ADMIN"){
                        $usuarios = $em->getRepository('posicionBundle:Usuarios')->findOneBy(array('id'=>$id2));
                        if($usuarios->getIdUsuarios()==$id1->getId()){
                            return $id2;
                        }
                        return $id1->getId();
                    }else{
                        return $id1->getId();
                    }
                }else{
                    return $id2;
                }

                break;

            default:
                break;
        }
        return 0;
    }
}

?>

<?php

namespace Sociedad\ReservasBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PlantasRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlantasRepository extends EntityRepository
{
    public function plantas($sociedadesid,$cuantos=null)
    {
        $consulta=$this->getEntityManager()
                    ->createQuery('SELECT p FROM  Sociedad\ReservasBundle\Entity\Plantas p
                                         WHERE p.sociedades_id=:id');
        $consulta->setParameter('id', $sociedadesid);
        $consulta->useResultCache(true, 3600);
        if($cuantos){
            $consulta->setMaxResults($cuantos);
        }
        return $consulta->getResult();
        
    }    
}

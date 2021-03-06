<?php

namespace Ulost\VilleBundle\Repository;
use Ulost\MunicipaleBundle\Entity\Service;

/**
 * VillesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VilleRepository extends \Doctrine\ORM\EntityRepository
{
    public function findVillePartenaire(){

        $query = $this->createQueryBuilder('v');

        $query->where('v.id = :id_ville')
            ->setParameter('id_ville',9430)
            ->orWhere('v.id LIKE :id_ville')
            ->setParameter('id_ville',13756)
            ->orWhere('v.id LIKE :id_ville')
            ->setParameter('id_ville', 20237)

        ;
        return $query
            ->getQuery()
            ->getResult();
    }


    public function findVilleByService(Service $service){

        $query = $this
            ->createQueryBuilder('v')
            ->leftJoin('v.villeServiceRelations', 'r')
            ->leftJoin('r.service', 's')
            ->andWhere('s = :service')
            ->setParameter('service',$service);




        return $query;

    }
}

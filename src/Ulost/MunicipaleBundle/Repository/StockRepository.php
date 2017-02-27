<?php

namespace Ulost\MunicipaleBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class StockRepository extends \Doctrine\ORM\EntityRepository
{

    public function findStocksByService($service)
    {


       $dql = "
        SELECT s,a,o,u,v,e
        FROM UlostMunicipaleBundle:Stock s
        JOIN s.annonce a
        JOIN s.emplacement e
        JOIN a.object o
        JOIN a.user u
        JOIN a.ville v
        WHERE s.service = :service
        ";

        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter('service', $service);

        return $query;
    }


    public function countAllStocksByService($service)
    {


        $dql = "
        SELECT COUNT(s)
        FROM UlostMunicipaleBundle:Stock s
        WHERE s.service = :service
        ";

        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter('service', $service);

        return $query->getSingleScalarResult();
    }






}

<?php

namespace Ulost\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Ulost\AnnonceBundle\Entity\Annonce;
use Ulost\MunicipaleBundle\Entity\Service;
use Ulost\ObjectBundle\Entity\Object;
use Ulost\UserBundle\Entity\User;
use Ulost\VilleBundle\Entity\Ville;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function countAllUsersByService(Service $service)
    {

        $dql = "
SELECT COUNT(u)
FROM UlostUserBundle:User u
INNER JOIN (SELECT a
        FROM UlostAnnonceBundle:Annonce a
        JOIN a.ville v
        JOIN v.villeServiceRelations r
        JOIN r.service s
        WHERE s=:service
        ) AS annonces ON annonces.user = u

        ";
        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter('service', $service);
        return $query->getSingleScalarResult();
    }
}
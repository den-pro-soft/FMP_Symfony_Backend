<?php

namespace RestBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use RestBundle\Entity\Service;

/**
 * Class ServiceRepository
 * @package RestBundle\Entity\Repository
 */
class ServiceRepository extends EntityRepository
{
    /**
     * @param $exclude
     * @param bool $with_description
     * @return array
     */
    public function getOtherServices($exclude, $with_description = false)
    {
        $qb = $this->createQueryBuilder('s');

        if ($with_description) {
            $qb->select('s.name', 's.icon', 's.link', 's.description');
        } else {
            $qb->select('s.name', 's.icon', 's.link');
        }

        return $qb->where('s.id <> :service')
            ->setParameter('service', $exclude)
            ->getQuery()
            ->getResult();
    }
}

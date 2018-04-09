<?php

namespace RestBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use RestBundle\Entity\Contact;
use RestBundle\Entity\Job;

/**
 * Class ContactRepository
 * @package RestBundle\Entity\Repository
 */
class ContactRepository extends EntityRepository
{
    /**
     * get contacts by job
     * @param Job $job
     * @return array
     */
    public function getJobContacts(Job $job){
        return $this->createQueryBuilder('contact')
            ->where('contact.job = :job')
            ->orderBy('contact.updatedAt', 'DESC')
            ->setParameter('job', $job)
            ->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }
}
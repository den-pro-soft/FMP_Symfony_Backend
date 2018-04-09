<?php

namespace RestBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Patinator;
use RestBundle\Entity\Contact;
use RestBundle\Entity\Attempt;

/**
 * Class ContactRepository
 * @package RestBundle\Entity\Repository
 */
class AttemptRepository extends EntityRepository
{
    /**
     * get contact attempts by contact
     * @param Contact $contact
     * @return array
     */
    public function getContactAttempts(Contact $contact){
        return $this->createQueryBuilder('attempt')
            ->where('attempt.contact = :contact')
            ->orderBy('attempt.date', 'DESC')
            ->setParameter('contact', $contact)
            ->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }
}
<?php

namespace RestBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class QuotesRepository
 * @package RestBundle\Entity\Repository
 */
class QuotesRepository extends EntityRepository
{
    /**
     * @param string $name
     * @return bool|mixed
     */
    public function getQuotes($name = 'about-us')
    {
        if (!in_array($name, [
            'about-us',
            'faq',
            'contact-us',
            'find-profession-best-career-advice-career-finder',
            'terms-of-use',
            'testimonials',
            'career-advice',
            'linkedin',
            'resume',
            'interviewing',
            'job-search',
        ], true)
        ) {
            return false;
        }

        $quote =  $this->createQueryBuilder('p')
            ->setParameter('quote', $name)
            ->getQuery()
            ->getOneOrNullResult();

        return $quote;
    }
}

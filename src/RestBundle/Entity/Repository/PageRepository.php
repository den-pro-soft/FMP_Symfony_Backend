<?php

namespace RestBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PageRepository
 * @package RestBundle\Entity\Repository
 */
class PageRepository extends EntityRepository
{
    /**
     * @param string $name
     * @return bool|mixed
     */
    public function getPage($name = 'about-us')
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

        $page =  $this->createQueryBuilder('p')
            ->where('p.slug = :page')
            ->setParameter('page', $name)
            ->getQuery()
            ->getOneOrNullResult();

        return $page;
    }
}

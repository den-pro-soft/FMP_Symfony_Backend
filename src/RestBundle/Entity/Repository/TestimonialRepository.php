<?php

namespace RestBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use RestBundle\Entity\Service;

/**
 * Class TestimonialRepository
 * @package RestBundle\Entity
 */
class TestimonialRepository extends EntityRepository
{
    /**
     * @param $page
     * @param null $filter
     * @param int $limit
     * @return array
     */
    public function getTestimonials($page, $filter = null, $limit = 15)
    {
        $query = $this->createQueryBuilder('t');

        if ($filter) {
            $query
                ->addSelect('s')
                ->join('t.service', 's')
                ->where('s.link = :filter')
                ->setParameter('filter', $filter);
        }

        $query
            ->andWhere('t.on_homepage = 0')
            ->orderBy('t.date', 'DESC')
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $paginator = new Paginator($query, $fetchJoinCollection = true);

        return ['count' => ceil(count($paginator) / $limit), 'testimonials' => $paginator->getQuery()->getResult()];
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getLastTestimonials($limit = 6)
    {
        return $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.on_homepage = 1')
            ->setMaxResults($limit)
            ->orderBy('t.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Service $service
     * @return array
     */
    public function getLastTestimonialsForService(Service $service)
    {
        return $this->createQueryBuilder('t')
            ->select('t.detail', 't.name', 't.rating', 't.age', 't.industry', 't.date')
            ->where('t.service = :service')
            ->andWhere('t.on_homepage = 0')
            ->setParameter('service', $service)
            ->orderBy('t.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getOnHomepage($page, $limit = 15)
    {
        $query = $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.on_homepage = 1')
            ->orderBy('t.date', 'DESC')
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $paginator = new Paginator($query, $fetchJoinCollection = true);

        return ['count' => ceil(count($paginator) / $limit), 'testimonials' => $paginator->getQuery()->getResult()];
    }
}

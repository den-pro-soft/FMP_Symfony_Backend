<?php
/**
 * Created by LiuWebDev
 */

namespace RestBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;
use RestBundle\Entity\ResumeSample;

/**
 * Class ResumeSampleRepository
 * @package RestBundle\Entity\Repository
 */
class ResumeSampleRepository extends EntityRepository
{
    /**
     * get samples by filter, query, sort, page
     *
     *
     * @param string $filter
     * @param string $query
     * @param string $sortBy
     * @param string $sortOrder
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getSamplesBy($filter = '', $query = '', $sortBy = 'title', $sortOrder = 'ASC', $page = 1, $limit = 10)
    {
        list($sortBy, $sortOrder) = $this->getValidSortFields($sortBy, $sortOrder);

        $sqlQuery = $this->createQueryBuilder('r')
        ->select('r')
        ->where('r.title LIKE :query')
        ->andWhere('r.category LIKE :filter')
        ->orderBy('r.' . $sortBy, $sortOrder)
        ->setParameters(array(
            'query' => '%' . $query . '%',
            'filter' => '%' . $filter . '%'
        ))
        ->setFirstResult($limit * ($page - 1))
        ->setMaxResults($limit);

        $paginator = new Paginator($sqlQuery, $fetchJoinCollection = true);
        return array(
            'count' => ceil(count($paginator) / $limit),
            'samples' => $paginator->getQuery()->getResult()
        );
    }

    /**
     * get valid sort fields
     *
     *
     * @param $sortField
     * @param $sortOrder
     * @return array
     */
    public function getValidSortFields($sortField, $sortOrder)
    {
        $allowedFields = array('title', 'status', 'created_at', 'updated_at');
        $sortOrder = strtoupper($sortOrder);

        if(!in_array($sortField, $allowedFields, true))
        {
            $sortField = 'title';
        }

        if(!in_array($sortOrder, array('ASC', 'DESC')))
        {
            $sortOrder = 'ASC';
        }

        return array($sortField, $sortOrder);
    }

    public function getSamplesByCategoryAsArray($cat)
    {
        return $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.category LIKE :cat')
            ->orderBy('r.created_at', 'DESC')
            ->setParameter('cat', '%' . $cat . '%')
            ->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }
}
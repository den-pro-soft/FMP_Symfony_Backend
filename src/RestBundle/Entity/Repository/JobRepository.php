<?php

namespace RestBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use RestBundle\Entity\Job;
use RestBundle\Entity\User;

/**
 * Class JobRepository
 * @package RestBundle\Entity\Repository
 */
class JobRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return array
     */
    public function getUserJobs(User $user)
    {
        return $this->createQueryBuilder('job')
            ->where('job.user = :user')
            ->orderBy('job.date', 'DESC')
            ->setParameter('user', $user)
            ->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @param $section
     * @param $page
     * @param null $filter
     * @param int $limit
     * @return array
     */
    public function getJobsBySection(User $user,$section, $page, $filter = null, $limit = 7)
    {
        $params['section'] = $section;
        $params['user'] = $user;

        $qb = $this->createQueryBuilder('job')
            ->where('job.section = :section')
            ->andWhere('job.user = :user')
            ->orderBy('job.date', 'DESC');
            // ->setFirstResult($limit * ($page - 1))
            // ->setMaxResults($limit);

        if ($filter) {
            $params['filter'] = $filter;
            $qb->andWhere('job.status = :filter');
        }

        $qb->setParameters($params);

        $paginator = new Paginator($qb, $fetchJoinCollection = true);
        $count = count($paginator);

        return ['pages' => ceil($count / $limit), 'count' => $count, 'jobs' => $paginator->getQuery()->getResult()];
    }

    /**
     * @param User $user
     * @param $section
     * @param null $filter
     * @return array
     */
    public function getJobsByStatuses(User $user, $section, $filter = null)
    {
        $params['section'] = $section;
        $params['user'] = $user;

        $qb = $this->createQueryBuilder('job')
            ->where('job.section = :section')
            ->andWhere('job.user = :user')
            ->orderBy('job.is_new');

        if ($filter) {
            $params['filter'] = $filter;
            $qb->andWhere('job.status = :filter');
        }

        return $qb->setParameters($params)->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @return array
     */
    public function getJobsCounts(User $user)
    {
        $qb = $this->createQueryBuilder('job');
        /** @var Job[] $jobs */
        $jobs = $qb
            ->where('job.user = :user')
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->in('job.status', ':statuses'),
                    $qb->expr()->andX(
                        $qb->expr()->eq('job.status', ':applied'),
                        $qb->expr()->eq('job.is_new', '1')
                    )
                )
            )
            ->setParameters([
                'user' => $user,
                'statuses' => ["Pending", "Need Info"],
                'applied' => 'Applied'
            ])
            ->getQuery()
            ->getResult();

        $data = [
            'pending' => 0,
            'applied' => 0,
            'info' => 0
        ];

        foreach ($jobs as $job) {
            switch ($job->getStatus()) {
                case 'Pending':
                    ++$data['pending'];
                    break;

                case 'Applied':
                    ++$data['applied'];
                    break;

                case 'Need Info':
                    ++$data['info'];
                    break;
            }
        }

        return $data;
    }

    /**
     * @param User $user
     * @return array
     */
    public function getNewUserJobs(User $user)
    {
        return $this->createQueryBuilder('j')
            ->select('j')
            ->leftJoin('j.user', 'u')
            ->leftJoin('u.admins', 'ad')
            ->where('ad.id = :ad')
            ->andWhere('j.is_new_admin = 1')
            ->setParameter('ad', $user)
            ->getQuery()->getResult();
    }

    /**
     * getReadyJobs
     *
     * @param User $admin
     *
     * @return array
     */
    public function getReadyJobs(User $admin)
    {
        return $this->createQueryBuilder('j')
            ->select('j')
            ->leftJoin('j.user', 'u')
            ->leftJoin('u.admins', 'ad')
            ->where('ad.id = :ad')
            ->andWhere('j.status = :status')
            ->setParameters([
                'ad' => $admin,
                'status' => 'Ready'
            ])
            ->getQuery()->getResult();
    }
}

<?php

namespace RestBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use RestBundle\Entity\Job;
use RestBundle\Entity\Task;
use RestBundle\Entity\User;

/**
 * Class TaskRepository
 * @package RestBundle\Entity\Repository
 */

class TaskRepository extends EntityRepository
{
    /**
     * get "Happy Birthday" task by user
     * @param User $user
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getBirthdayTask(User $user){
        return $this->createQueryBuilder('task')
            ->where('task.user = :user')
            ->andWhere('task.name Like :name')
            ->orderBy('task.id', 'DESC')
            ->setParameters(['user' => $user, 'name' => '%Happy Birthday%'])
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * get "Add Contacts" task by job
     * @param Job $job
     * @return Task $task
     */
    public function getAddContactTask(Job $job){
        return $this->createQueryBuilder('task')
            ->where('task.job_id = :job')
            ->andWhere('task.name LIKE :name')
            ->orderBy('task.id', 'DESC')
            ->setParameters(['job' => $job->getId(), 'name' => '%Add Contacts%'])
            ->setParameter('name', '%Add Contacts%')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * get last "Message 2" task by contact_id
     * @param $contact_id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getLastM2Task($contact_id){
        return $this->createQueryBuilder('task')
            ->where('task.contact_id = :contact_id')
            ->andWhere('task.name LIKE :name')
            ->orderBy('task.id', 'DESC')
            ->setParameters(['contact_id' => $contact_id, 'name' => '%Message 2%'])
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * get last "Message 3" task by contact_id
     * @param $contact_id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getLastM3Task($contact_id){
        return $this->createQueryBuilder('task')
            ->where('task.contact_id = :contact_id')
            ->andWhere('task.name LIKE :name')
            ->orderBy('task.id', 'DESC')
            ->setParameters(['contact_id' => $contact_id, 'name' => '%Message 3%'])
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * get last "Follow-Up" task by contact_id
     * @param $contact_id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getFollowUpTask($contact_id){
        return $this->createQueryBuilder('task')
            ->where('task.contact_id = :contact_id')
            ->andWhere('task.name LIKE :name')
            ->orderBy('task.id', 'DESC')
            ->setParameters(array('contact_id' => $contact_id, 'name' => '%Follow-Up%'))
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * get last "Client 1-on-1" task by job
     * @param $job
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getOneToOneTask(Job $job){
        return $this->createQueryBuilder('task')
            ->where('task.job_id = :job')
            ->andWhere('task.name LIKE :name')
            ->orderBy('task.id', 'DESC')
            ->setParameters(['job' => $job->getId(), 'name' => '%Client 1-on-1%'])
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * get All tasks by contact id
     * @param $contact_id
     * @return array
     */
    public function getAllTasksByContact($contact_id){
        return $this->createQueryBuilder('task')
            ->where('task.contact_id = :contact_id')
            ->setParameter('contact_id', $contact_id)
            ->getQuery()->getResult();
    }

    /**
     * get all tasks by job
     * @param Job $job
     * @return array
     */
    public function getAllTasksByJob(Job $job){
        return $this->createQueryBuilder('task')
            ->where('task.job_id = :job')
            ->orderBy('task.id', 'ASC')
            ->setParameter('job', $job->getId())
            ->getQuery()->getResult();
    }

    /**
     * get all tasks by user
     * @param User $user
     * @return array
     */
    public function getAllTasksByUser(User $user){
        return $this->createQueryBuilder('task')
            ->where('task.user = :user')
            ->orderBy('task.id', 'ASC')
            ->setParameter('user', $user)
            ->getQuery()->getResult();
    }

}
<?php
namespace RestBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use RestBundle\Entity\User;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 * Class UserRepository
 * @package RestBundle\Entity\Repository
 */
class UserRepository extends EntityRepository implements UserLoaderInterface
{
    /**
     * @param string $username
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.token = :token')
            ->andWhere('u.isRemoved = false')
            ->andWhere('u.token IS NOT NULL')
            ->setParameter('username', $username)
            ->setParameter('token', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return mixed
     */
    public function getAdmins()
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->andWhere('u.is_active = true')
            ->orderBy('u.date_created')
            ->setParameter('role', '%ROLE_ADMIN%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return mixed
     */
    public function getAdminsForAssignToUser()
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :roles')
            ->andWhere('u.is_active = true')
            ->andWhere('u.role != :role')
            ->orderBy('u.date_created')
            ->setParameters(array(
                'roles' => '%ROLE_ADMIN%',
                'role' => 'ROLE_MANAGER_BLOG',
            ))
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $admin
     * @return array|mixed
     */
    public function getUsersByAdmin(User $admin){
        if($admin->isSuperAdmin()){
            return $this->createQueryBuilder('u')
                ->where('u.profile IS NOT NULL')
                ->andWhere('u.is_active = true')
                ->orderBy('u.date_created', 'DESC')
                ->getQuery()
                ->getResult();
        } elseif ($admin->isAdminManager()){
            $users = array();
            foreach ($admin->getUsers() as $admin_item){
                foreach ($admin_item->getUsers() as $user){
                    if(!in_array($user, $users)) $users[] = $user;
                }
            }
            return $users;
        } elseif ($admin->isAdmin() || $admin->isSDR()){
            return $admin->getUsers();
        }
        return array();
    }

    /**
     * @return User
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSuperAdmin()
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_SUPER_ADMIN%')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * get all clients
     * @return array
     */
    public function getAllClients(){
        return $this->createQueryBuilder('u')
            ->where('u.profile IS NOT NULL')
            ->andWhere('u.is_active = true')
            ->andWhere('u.roles LIKE :role')
            ->orderBy('u.date_created')
            ->setParameter('role', '%ROLE_USER%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $role
     * @return array
     */
    public function getAllAdminsByRole($role){
        return $this->createQueryBuilder('u')
            ->where('u.role = :role')
            ->andWhere('u.is_active = true')
            ->andWhere('u.roles NOT LIKE :super')
            ->orderBy('u.date_created')
            ->setParameters(array(
                'role' => $role,
                'super' => '%ROLE_SUPER_ADMIN%'
            ))
            ->getQuery()
            ->getResult();
    }
}
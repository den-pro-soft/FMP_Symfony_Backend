<?php

namespace RestBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;
use RestBundle\Entity\Author;
use RestBundle\Entity\Blog;
use RestBundle\Entity\Category;
use RestBundle\Entity\User;

/**
 * Class BlogRepository
 * @package RestBundle\Entity\Repository
 */
class BlogRepository extends EntityRepository
{
    /**
     * @param int $page
     * @param int $limit
     * @param string $sortField
     * @param string $sortOrder
     * @return array
     */
    public function getCareerAdvices($page = 1, $limit = 9, $sortField = 'post_date', $sortOrder = 'DESC')
    {
        list($sortField, $sortOrder) = $this->getValidSortFields($sortField, $sortOrder);

        $query = $this->createQueryBuilder('b')
            ->select('b')
            ->where('b.isRemoved = false')
            ->orderBy('b.' . $sortField, $sortOrder)
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $paginator = new Paginator($query, $fetchJoinCollection = true);

        return ['count' => ceil(count($paginator) / $limit), 'blogs' => $paginator->getQuery()->getResult()];
    }

    /**
     * @param $query
     * @param int $page
     * @param int $limit
     * @param string $sortField
     * @param string $sortOrder
     * @return array
     */
    public function getCareerAdvicesByQuery($query, $page = 1, $limit = 9, $sortField = 'post_date', $sortOrder = 'DESC')
    {
        list($sortField, $sortOrder) = $this->getValidSortFields($sortField, $sortOrder);

        $query = $this->createQueryBuilder('b')
            ->select('b')
            ->where('b.title LIKE :query')
            ->andWhere('b.isRemoved = false')
            ->orderBy('b.' . $sortField, $sortOrder)
            ->setParameter('query', '%' . $query . '%')
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $paginator = new Paginator($query, $fetchJoinCollection = true);

        return ['count' => ceil(count($paginator) / $limit), 'blogs' => $paginator->getQuery()->getResult()];
    }

    /**
     * @param Category $category
     * @param int $page
     * @param int $limit
     * @param string $sortField
     * @param string $sortOrder
     * @return array
     */
    public function getCareerAdvicesByCategory(Category $category, $page = 1, $limit = 9, $sortField = 'post_date', $sortOrder = 'DESC')
    {
        list($sortField, $sortOrder) = $this->getValidSortFields($sortField, $sortOrder);

        $query = $this->createQueryBuilder('b')
            ->select('b')
            ->join('b.category', 'c')
            ->where('c = :category')
            ->andWhere('b.isRemoved = false')
            ->andWhere('b.status = :status')
            ->orderBy('b.' . $sortField, $sortOrder)
            ->setParameters([
                'category' => $category->getId(),
                'status' => 'Publish'
            ])
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $paginator = new Paginator($query);

        
        return ['count' => ceil(count($paginator) / $limit), 'blogs' => $paginator->getQuery()->getResult() , 'total_count' => count($paginator) ];
    }

    /**
     * @param Category $category
     * @param $query
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getCareerAdvicesByCategoryAndQuery(Category $category, $search_query, $page = 1, $limit = 9)
    {
        $qry_list = explode( " " ,  $search_query );
        $qry_str = " ";

        $param = [ 'category' => $category->getId(),
                'status' => 'Publish'];

        for( $i = 0 ; $i < count( $qry_list ) ; $i++ )
        {
            if( $i ) $qry_str .= " or  ( b.description LIKE :x".$i." ) ";
            else     $qry_str .= "     ( b.description LIKE :x".$i." ) ";
            $param[ "x".$i ] = "%".$qry_list[$i]."%";
        }  

        $query = $this->createQueryBuilder('b')
            ->select('b')
            ->join('b.category', 'c')
            ->where('c = :category')
            ->andWhere( 'b.isRemoved = false' )
            ->andWhere( 'b.status  = :status'  )
            ->andWhere( $qry_str )
            ->orderBy('b.post_date', 'DESC')
            ->setParameters($param)
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $paginator = new Paginator($query);
        
        return [ 'count' => ceil(count($paginator) / $limit), 'blogs' => $paginator->getQuery()->getResult() , 'total_count' => count($paginator) ];
    }

    /**
     * @param int $page
     * @param int $limit
     * @param string $sortField
     * @param string $sortOrder
     * @return array
     */
    public function getCareerAdvicesTopCategory($page = 1, $limit = 9, $sortField = 'post_date', $sortOrder = 'DESC')
    {
        list($sortField, $sortOrder) = $this->getValidSortFields($sortField, $sortOrder);

        $query = $this->createQueryBuilder('b')
            ->select('b')
            ->where('b.isRemoved = false')
            ->join('b.category', 'c')
            ->orderBy('b.' . $sortField, $sortOrder)
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $paginator = new Paginator($query);

        return ['count' => ceil(count($paginator) / $limit), 'blogs' => $paginator->getQuery()->getResult()];
    }

    /**
     * @param $query
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getCareerAdvicesTopCategoryAndQuery($query, $page = 1, $limit = 9)
    {
        $query = $this->createQueryBuilder('b')
            ->select('b')
            ->join('b.category', 'c')
            ->where('b.title LIKE :query')
            ->andWhere('b.isRemoved = false')
            ->orderBy('b.post_date', 'DESC')
            ->setParameter('query', '%' . $query . '%')
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $paginator = new Paginator($query);

        return ['count' => ceil(count($paginator) / $limit), 'blogs' => $paginator->getQuery()->getResult()];
    }

    /**
     * @param Category $category
     * @param int $limit
     * @param int $blog_id
     * @return array
     */
    public function getTops(Category $category, $blog_id = null, $limit = 6 )
    {
        // $params['category'] = $category;
        $params['status'] = 'Publish';

        $qb = $this->createQueryBuilder('b')
            ->select('b')
            // ->join('b.top_category', 'c')
            // ->where('c = :category')
            ->andWhere('b.status = :status')
            ->andWhere('b.isRemoved = false')
            // ->orderBy('b.views', 'DESC')
            ->addSelect('RAND() as HIDDEN rand')
            ->orderBy('rand')
            ->setMaxResults($limit);

        if ($blog_id) {
            $params['blog_id'] = $blog_id;
            $qb->andWhere('b.id <> :blog_id');
        }

        $result = $qb->setParameters($params)->getQuery();

        return $result->getResult();
    }


    /**
     * @param Category $category
     * @param int $limit
     * @param int $blog_id
     * @return array
     */
    public function getTopsByCategory(Category $category, $blog_id = null, $limit = 6 )
    {
        $params['category'] = $category;
        $params['status'] = 'Publish';

        $qb = $this->createQueryBuilder('b')
            ->select('b')
            ->join('b.category', 'c')
            ->where('c = :category')
            ->andWhere('b.status = :status')
            ->andWhere('b.isRemoved = false') 
            ->orderBy('b.post_date', 'DESC') 
            ->setMaxResults($limit);
  
        $result = $qb->setParameters($params)->getQuery();

        return $result->getResult();
    }

    /**
     * @param Category $category
     * @param $blog_id
     * @return array
     */
    public function getRandomBlogsByCategory(Category $category, $blog_id)
    {
        $params['blog_id'] = $blog_id;
        $params['status'] = 'Publish';

        $query = $this->createQueryBuilder('b')
            ->where('b.id <> :blog_id')
            ->andWhere('b.status = :status')
            ->andWhere('b.isRemoved = false')
            ->addSelect('RAND() as HIDDEN rand')
            ->orderBy('rand')
            ->setMaxResults(3);

        if ($category !== null) {
            $params['category'] = $category->getId();
            $query
                ->join('b.category', 'c')
                ->andWhere('c = :category');
        }

        $query->setParameters($params);

        return $query->getQuery()->getResult();
    }


    /**
     * @param User $user
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getUserLikes(User $user, $page = 1, $limit = 9)
    {
        $em = $this->getEntityManager();

        $offset = ($page - 1) * $limit;

        $sql = 'SELECT b.*, a.name, a.link, a.avatar_name, a.author_description FROM likes JOIN blog b ON likes.blog_id = b.id INNER JOIN authors a ON b.author = a.id WHERE likes.user_id = ? LIMIT ?, ?';

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(Blog::class, 'b');
        $rsm->addFieldResult('b', 'id', 'id');
        $rsm->addFieldResult('b', 'title', 'title');
        $rsm->addFieldResult('b', 'description', 'description');
        $rsm->addFieldResult('b', 'image_name', 'image_name');
        $rsm->addFieldResult('b', 'image_alt', 'image_alt');
        $rsm->addFieldResult('b', 'post_date', 'post_date');
        $rsm->addFieldResult('b', 'url', 'url');
        $rsm->addFieldResult('b', 'liked', 'liked');
        $rsm->addJoinedEntityResult(Author::class, 'a', 'b', 'author');
        $rsm->addFieldResult('a','author','id');
        $rsm->addFieldResult('a','name','name');
        $rsm->addFieldResult('a','author_description','author_description');
        $rsm->addFieldResult('a','avatar_name','avatar_name');
        $rsm->addFieldResult('a','link','link');


        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameters([
            1 => $user->getId(),
            2 => $offset,
            3 => $limit
        ]);

        /** @var Blog[] $result */
        $result = $query->getResult();
        $likes = [];

        foreach ($result as $like) {
            $like->setLiked(true);
            $likes[] = $like;
        }

        $pages = ceil(count($user->getLikes()) / $limit);

        return ['pages' => $pages, 'likes' => $likes];
    }

    /**
     * @return array
     */
    public function getBlogSlugs()
    {
        return $this->createQueryBuilder('b')
            ->select('b.url')
            ->where('b.status = :status')
            ->setParameter('status', 'Publish')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $sortField
     * @param $sortOrder
     * @return array
     */
    private function getValidSortFields($sortField, $sortOrder)
    {
        $allowedFields = ['post_date', 'title'];
        $sortOrder = strtoupper($sortOrder);

        if (!in_array($sortField, $allowedFields, true)) {
            $sortField = 'post_date';
        }

        if (!in_array($sortOrder, ['ASC', 'DESC'], true)) {
            $sortOrder = 'DESC';
        }

        return [
            $sortField,
            $sortOrder
        ];
    }
}

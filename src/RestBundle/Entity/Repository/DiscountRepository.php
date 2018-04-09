<?php

namespace RestBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use RestBundle\Entity\Discount;

/**
 * Class DiscountRepository
 * @package RestBundle\Entity\Repository
 */
class DiscountRepository extends EntityRepository
{
    /**
     * @param $code
     * @return mixed
     */
    public function getDiscount($code)
    {
        /** @var Discount $discount */
        $discount = $this->createQueryBuilder('discount')
            ->where('discount.code = :code')
            ->andWhere('discount.count > 0')
            ->andWhere('discount.date_from <= :date AND discount.date_undo >= :date')
            ->setParameters([
                'code' => $code,
                'date' => new \DateTime()
            ])
            ->getQuery()
            ->getOneOrNullResult();

        return $discount;
    }
}

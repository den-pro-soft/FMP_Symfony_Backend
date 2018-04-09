<?php

namespace MigrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WpTermRelationships
 *
 * @ORM\Table(name="wp_term_relationships")
 * @ORM\Entity
 */
class WpTermRelationships
{
    /**
     * @var integer
     *
     * @ORM\Column(name="object_id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $objectId;

    /**
     * @var integer
     *
     * @ORM\Column(name="term_taxonomy_id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $termTaxonomyId;

    /**
     * @var integer
     *
     * @ORM\Column(name="term_order", type="integer", nullable=false)
     */
    private $termOrder;

    /**
     * @return int
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param int $objectId
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    }

    /**
     * @return int
     */
    public function getTermTaxonomyId()
    {
        return $this->termTaxonomyId;
    }

    /**
     * @param int $termTaxonomyId
     */
    public function setTermTaxonomyId($termTaxonomyId)
    {
        $this->termTaxonomyId = $termTaxonomyId;
    }

    /**
     * @return int
     */
    public function getTermOrder()
    {
        return $this->termOrder;
    }

    /**
     * @param int $termOrder
     */
    public function setTermOrder($termOrder)
    {
        $this->termOrder = $termOrder;
    }
}

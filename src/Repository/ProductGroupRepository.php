<?php

namespace App\Repository;

use App\Entity\Product\ProductGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;

/**
 * @method ProductGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductGroup[]    findAll()
 * @method ProductGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductGroup::class);
    }

    /**
     * Save product group to database
     * @param $productGroup
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save($productGroup)
    {
        $result = [];
        try {
            $this->getEntityManager()->persist($productGroup);
            $this->getEntityManager()->flush();
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][$e->getMessage()];
        }
        return $result;
    }


    /**
     * Delete product group from database
     * @param $productGroup
     * @return array
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete($productGroup)
    {
        $result = [];
        try {
            $this->getEntityManager()->remove($productGroup);
            $this->getEntityManager()->flush();
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][] = $e->getMessage();
        }
        return $result;
    }

    /**
     * Get archived or non archived product groups
     * @param bool $archived
     * @return Query
     */
    public function getProductGroups($archived = false)
    {
        return $this->createQueryBuilder('pg')
            ->where('pg.archived = :archived')
            ->setParameter('archived', $archived)
            ->orderBy('pg.name', 'ASC')
            ->getQuery();
    }

    /**
     * Get product group by id
     * @param $id
     * @return ProductGroup|null
     */
    public function getProductGroup($id)
    {
        return $this->findOneBy(['id' => $id]);
    }


    /**
     * Search for product group(s)
     * @param $search
     * @return Query
     */
    public function searchProductGroups($search)
    {
        $qb = $this->createQueryBuilder('pg');
        $orX = $qb->expr()->orX();
        $orX->add($qb->expr()->like('pg.name', "'%$search%'"));
        $orX->add($qb->expr()->like('pg.workoutType', "'%$search%'"));

        $qb->where($orX);
        return $qb->getQuery();
    }
}

<?php

namespace App\Repository;

use App\Entity\Product\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Save product to database
     * @param $product
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($product)
    {
        $result['error'] = [];
        try {
            $this->getEntityManager()->persist($product);
            $this->getEntityManager()->flush();
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][$e->getMessage()];
        }
        return $result;
    }


    /**
     * Delete product from database
     * @param $product
     * @return array
     */
    public function delete($product)
    {
        $result = [];
        try {
            $this->getEntityManager()->remove($product);
            $this->getEntityManager()->flush();
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][] = $e->getMessage();
        }
        return $result;
    }

    /**
     * Get archived or non archived products
     * @param bool $deleted
     * @return \Doctrine\ORM\Query
     */
    public function getProducts($archived = false)
    {
        return $this->createQueryBuilder('p')
            ->where('p.archived = :archived')
            ->setParameter('archived', $archived)
            ->orderBy('p.name', 'ASC')
            ->getQuery();
    }

    /**
     * Get product by id
     * @param $id
     * @return Product|null
     */
    public function getProduct($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function searchProducts($search)
    {
        $qb = $this->createQueryBuilder('p');
        $orX = $qb->expr()->orX();
        $orX->add($qb->expr()->like('a.name', "'%$search%'"));
        $orX->add($qb->expr()->like('a.workoutType', "'%$search%'"));

        $qb->where($orX);
        return $qb->getQuery();
    }

}

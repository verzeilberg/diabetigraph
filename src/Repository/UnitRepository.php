<?php

namespace App\Repository;

use App\Entity\Product\Unit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Unit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Unit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Unit[]    findAll()
 * @method Unit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unit::class);
    }

    /**
     * Save unit to database
     * @param $unit
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($unit)
    {
        $result['error'] = [];
        try {
            $this->getEntityManager()->persist($unit);
            $this->getEntityManager()->flush();
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][$e->getMessage()];
        }
        return $result;
    }


    /**
     * Delete unit from database
     * @param $unit
     * @return array
     */
    public function delete($unit)
    {
        $result = [];
        try {
            $this->getEntityManager()->remove($unit);
            $this->getEntityManager()->flush();
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][] = $e->getMessage();
        }
        return $result;
    }

    /**
     * Get archived or non archived units
     * @param bool $deleted
     * @return \Doctrine\ORM\Query
     */
    public function getUnits($archived = false)
    {
        return $this->createQueryBuilder('u')
            ->where('u.archived = :archived')
            ->setParameter('archived', $archived)
            ->orderBy('u.order', 'ASC')
            ->getQuery();
    }

    /**
     * Get unit by id
     * @param $id
     * @return Unit|null
     */
    public function getUnit($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * @param $search
     * @return \Doctrine\ORM\Query
     */
    public function searchUnits($search)
    {
        $qb = $this->createQueryBuilder('u');
        $orX = $qb->expr()->orX();
        $orX->add($qb->expr()->like('u.name', "'%$search%'"));
        $orX->add($qb->expr()->like('a.workoutType', "'%$search%'"));

        $qb->where($orX);
        return $qb->getQuery();
    }

    /**
     * @return Unit|null
     */
    public function getHighestUnitByOrder()
    {
        return $this->findOneBy([], ['order' => 'DESC']);
    }

}

<?php

namespace App\Repository;

use App\Entity\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Symfony\Component\Security\Core\Exception\UnsupportedRouteException;
use Symfony\Component\Security\Core\Route\RouteInterface;

/**
 * @method Route|null find($id, $lockMode = null, $lockVersion = null)
 * @method Route|null findOneBy(array $criteria, array $orderBy = null)
 * @method Route[]    findAll()
 * @method Route[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Route::class);
    }

    /**
     * Save route to database
     * @param $route
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($route)
    {
        $result['error'] = [];
        try {
            $this->getEntityManager()->persist($route);
            $this->getEntityManager()->flush();
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][$e->getMessage()];
        }
        return $result;
    }


    /**
     * Delete route from database
     * @param $route
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($route)
    {
        $result = [];
        try {
            $this->getEntityManager()->remove($route);
            $this->getEntityManager()->flush();
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][] = $e->getMessage();
        }
        return $result;
    }

    /**
     * Get archived or non archived routes
     * @param bool $deleted
     * @return Query
     */
    public function getRoutes($archived = false)
    {
        return $this->createQueryBuilder('r')
            ->where('r.archived = :archived')
            ->setParameter('archived', $archived)
            ->orderBy('r.name', 'ASC')
            ->getQuery();
    }

    /**
     * Get route by id
     * @param $id
     * @return Route|null
     */
    public function getRoute($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function searchRoutes($search)
    {
        $qb = $this->createQueryBuilder('r');
        $orX = $qb->expr()->orX();
        $orX->add($qb->expr()->like('r.name', "'%$search%'"));
        $orX->add($qb->expr()->like('r.workoutType', "'%$search%'"));

        $qb->where($orX);
        return $qb->getQuery();
    }
}

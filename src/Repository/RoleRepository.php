<?php

namespace App\Repository;

use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Symfony\Component\Security\Core\Exception\UnsupportedRoleException;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * @method Role|null find($id, $lockMode = null, $lockVersion = null)
 * @method Role|null findOneBy(array $criteria, array $orderBy = null)
 * @method Role[]    findAll()
 * @method Role[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
    }

    /**
     * Save role to database
     * @param $role
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($role)
    {
        $result['error'] = [];
        try {
            $this->getEntityManager()->persist($role);
            $this->getEntityManager()->flush();
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][$e->getMessage()];
        }
        return $result;
    }


    /**
     * Delete role from database
     * @param $role
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($role)
    {
        $result = [];
        try {
            $this->getEntityManager()->remove($role);
            $this->getEntityManager()->flush();
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][] = $e->getMessage();
        }
        return $result;
    }

    /**
     * Get archived or non archived roles
     * @param bool $deleted
     * @return Query
     */
    public function getRoles($archived = false)
    {
        return $this->createQueryBuilder('r')
            ->where('r.archived = :archived')
            ->setParameter('archived', $archived)
            ->orderBy('r.name', 'ASC')
            ->getQuery();
    }

    /**
     * Get role by id
     * @param $id
     * @return Role|null
     */
    public function getRole($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function searchRoles($search)
    {
        $qb = $this->createQueryBuilder('r');
        $orX = $qb->expr()->orX();
        $orX->add($qb->expr()->like('r.name', "'%$search%'"));
        $orX->add($qb->expr()->like('r.workoutType', "'%$search%'"));

        $qb->where($orX);
        return $qb->getQuery();
    }

    /**
     * Used to upgrade (rehash) the role's password automatically over time.
     */
    public function upgradePassword(RoleInterface $role, string $newEncodedPassword): void
    {
        if (!$role instanceof Role) {
            throw new UnsupportedRoleException(sprintf('Instances of "%s" are not supported.', \get_class($role)));
        }

        $role->setPassword($newEncodedPassword);
        $this->_em->persist($role);
        $this->_em->flush();
    }
}

<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    /**
     * Save user to database
     * @param $user
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($user)
    {
        $result['error'] = [];
        try {
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][$e->getMessage()];
        }
        return $result;
    }


    /**
     * Delete user from database
     * @param $user
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($user)
    {
        $result = [];
        try {
            $this->getEntityManager()->remove($user);
            $this->getEntityManager()->flush();
            $result['error'] = null;
        } catch (Exception $e) {
            $result['error'][] = $e->getMessage();
        }
        return $result;
    }

    /**
     * Get archived or non archived users
     * @param bool $deleted
     * @return Query
     */
    public function getUsers($archived = false)
    {
        return $this->createQueryBuilder('u')
            ->where('u.archived = :archived')
            ->setParameter('archived', $archived)
            ->orderBy('u.userName', 'ASC')
            ->getQuery();
    }

    /**
     * Get user by id
     * @param $id
     * @return User|null
     */
    public function getUser($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function searchUsers($search)
    {
        $qb = $this->createQueryBuilder('p');
        $orX = $qb->expr()->orX();
        $orX->add($qb->expr()->like('a.name', "'%$search%'"));
        $orX->add($qb->expr()->like('a.workoutType', "'%$search%'"));

        $qb->where($orX);
        return $qb->getQuery();
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }
}

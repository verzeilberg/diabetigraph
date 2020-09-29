<?php

namespace App\Repository;

use App\Entity\UserProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserProfileRepository
 * @package App\Repository
 */
class UserProfileRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProfile::class);
    }


    /**
     * Save userProfile to database
     * @param $userProfile
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($userProfile)
    {
        $result['error'] = [];
        try {
            $this->getEntityManager()->persist($userProfile);
            $this->getEntityManager()->flush();
        } catch (Exception $e) {
            $result['error'][$e->getMessage()];
        }
        return $result;
    }

}

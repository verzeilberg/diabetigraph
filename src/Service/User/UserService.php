<?php


namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;


class UserService
{

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @var UserRepository
     */
    public $repository;

    /**
     * UserService constructor.
     * @param UserRepository $repository
     */
    public function __construct(
        UserRepository $repository
    )
    {
        $this->repository = $repository;
    }

    /**
     * Create new user instance
     * @return User
     */
    public function newUser()
    {
        return new User();
    }

    /**
     * Archive user
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function archiveUser(User $user, $archive = true)
    {
        $user->setArchived($archive);
        return $this->repository->save($user);
    }
}

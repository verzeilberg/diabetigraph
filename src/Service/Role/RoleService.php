<?php


namespace App\Service\Role;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;


class RoleService
{

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @var RoleRepository
     */
    public $repository;

    /**
     * RoleService constructor.
     * @param RoleRepository $repository
     */
    public function __construct(
        RoleRepository $repository
    )
    {
        $this->repository = $repository;
    }

    /**
     * Create new role instance
     * @return Role
     */
    public function newRole()
    {
        return new Role();
    }

    /**
     * Archive role
     * @param Role $role
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function archiveRole(Role $role, $archive = true)
    {
        $role->setArchived($archive);
        return $this->repository->save($role);
    }
}

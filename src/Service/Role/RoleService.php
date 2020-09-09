<?php


namespace App\Service\Role;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class RoleService
{

    /** @var RoleRepository */
    public $repository;

    /** @var UrlGeneratorInterface */
    private $router;

    /**
     * RoleService constructor.
     * @param RoleRepository $repository
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        RoleRepository $repository,
        UrlGeneratorInterface $router
    )
    {
        $this->repository = $repository;
        $this->router = $router;
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

    public function getAllRoutes()
    {
        $collection = $this->router->getRouteCollection();
        $allRoutes = $collection->all();

        $routes = array();

        /** @var $params \Symfony\Component\Routing\Route */
        foreach ($allRoutes as $route => $params)
        {
            if (substr($route, 0, 3) === 'app') {
                $routes[] = $route;
            }
        }

        return $routes;
    }
}

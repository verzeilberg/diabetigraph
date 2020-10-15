<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Route;
use App\Service\Role\RoleService;
use App\Service\Route\RouteService;
use App\Service\User\UserService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{

    private $passwordEncoder;
    private $userService;
    private $roleService;
    private $routeService;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UserService $userService,
        RoleService $roleService,
        RouteService $routeService
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->routeService = $routeService;
    }

    public function load(ObjectManager $manager)
    {
        $route = $this->routeService->repository->getRouteByName('app_admin');



        if(empty($route))
        {
            $route = $this->routeService->newRoute();
            $route->setRoute('app_admin');
            $this->routeService->repository->save($route);

        }



        $role = $this->roleService->newRole();

        $role->setRoleId('ROLE_ADMIN');
        $role->setDescription('Role for admin business');
        $role->setName('Admin role');
        $role->setLoginPath( $route);

        $this->roleService->repository->save($role);


        $user = $this->userService->newUser();
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'Gravity35#'
        ));

        $user->setUserName('sander');
        $user->setEmail('sander@verzeilberg.nl');
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());
        $user->setIsActive(true);
        $user->setAuthRoles([$role]);

        $this->userService->repository->save($user);

    }
}

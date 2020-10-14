<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Route;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {



        $route = new Route();
        $route->setRoute('app_admin');

        $manager->persist($route);

        $role = new Role();

        $role->setRoleId('ROLE_ADMIN');
        $role->setDescription('Role for admin business');
        $role->setName('Admin role');
        $role->setLoginPath( $route);

        $manager->persist($role);

        $user = new User();
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

        $manager->persist($user);
        $manager->flush();
    }
}

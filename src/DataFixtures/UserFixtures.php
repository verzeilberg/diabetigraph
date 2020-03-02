<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
        $user = new User();

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'Gravity35#'
        ));

        $user->setDisplayName('Sander');
        $user->setUserName('sander');
        $user->setFirstName('Sander');
        $user->setLastName('Sander');
        $user->setEmail('sander@verzeilberg.nl');


        $manager->persist($user);
        $manager->flush();
    }
}

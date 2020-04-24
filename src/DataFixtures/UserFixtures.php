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

        $user->setUserName('sander');
        $user->setFirstName('Sander');
        $user->setLastName('Sander');
        $user->setEmail('sander@verzeilberg.nl');
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());

        $manager->persist($user);
        $manager->flush();
    }
}

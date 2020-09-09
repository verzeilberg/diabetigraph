<?php


namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Date;
use Nzo\UrlEncryptorBundle\UrlEncryptor\UrlEncryptor;


class UserService
{
    /**
     * @var UserRepository
     */
    public $repository;

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /** @var UrlEncryptor */
    private $encryptor;

    /**
     * UserService constructor.
     * @param UserRepository $repository
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        UserRepository $repository,
        UserPasswordEncoderInterface $passwordEncoder,
        UrlEncryptor $encryptor
    )
    {
        $this->repository = $repository;
        $this->passwordEncoder = $passwordEncoder;
        $this->encryptor = $encryptor;
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
     * Save user with or without new password
     * @param $user
     * @param bool $newPassword
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveUser($user, bool $newPassword = true, $newUser = false) {
        if ($newPassword) {
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $user->getPassword()
            ));
        }

        if ($newUser) {
            $user->setCreatedAt(new DateTime());
        }

        $user->setUpdatedAt(new DateTime());
        return $this->repository->save($user);
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

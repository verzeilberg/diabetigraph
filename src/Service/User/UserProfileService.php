<?php


namespace App\Service\User;

use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;

class UserProfileService
{
    /**
     * @var UserProfileRepository
     */
    public $repository;

    /**
     * UserProfileService constructor.
     * @param UserProfileRepository $repository
     */
    public function __construct(
        UserProfileRepository $repository
    )
    {
        $this->repository = $repository;
    }

    /**
     * Create new user instance
     * @return User
     */
    public function newUserProfile()
    {
        return new UserProfile();
    }

}

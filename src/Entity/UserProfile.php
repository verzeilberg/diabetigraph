<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Rollerworks\Component\PasswordStrength\Validator\Constraints as RollerworksPassword;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserProfileRepository")
 */
class UserProfile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="first_name", type="string", length=180, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=180, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(name="last_name_prefix", type="string", length=180, nullable=true)
     */
    private $lastNamePrefix;

    /**
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(name="image_filename", type="string", nullable=true)
     */
    private $imageFilename;


    /**
     * One UserProfile has One User.
     * @ORM\OneToOne(targetEntity="User", inversedBy="userProfile")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return UserProfile
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     * @return UserProfile
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return UserProfile
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastNamePrefix()
    {
        return $this->lastNamePrefix;
    }

    /**
     * @param mixed $lastNamePrefix
     * @return UserProfile
     */
    public function setLastNamePrefix($lastNamePrefix)
    {
        $this->lastNamePrefix = $lastNamePrefix;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     * @return UserProfile
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return UserProfile
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageFilename()
    {
        return $this->imageFilename;
    }

    /**
     * @param mixed $imageFilename
     */
    public function setImageFilename($imageFilename): void
    {
        $this->imageFilename = $imageFilename;
    }



}

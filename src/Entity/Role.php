<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Route;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roleId;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * Many Roles have Many Users.
     * @ORM\ManyToMany(targetEntity="User", mappedBy="authRoles")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="Route")
     * @ORM\JoinColumn(name="login_path_id", referencedColumnName="id")
     */
    private $loginPath;

    /**
     * @ORM\Column(type="boolean", nullable=false, )
     */
    private $archived = false;

    public function __construct() {
        $this->users = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return mixed
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * @param mixed $roleId
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;
    }

    /**
     * @return mixed
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * @param mixed $archived
     * @return Role
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLoginPath()
    {
        return $this->loginPath;
    }

    /**
     * @param mixed $loginPath
     * @return Role
     */
    public function setLoginPath($loginPath)
    {
        $this->loginPath = $loginPath;
        return $this;
    }





}

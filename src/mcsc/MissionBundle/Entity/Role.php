<?php

namespace mcsc\MissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;
use mcsc\MissionBundle\Entity\Users;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Role
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Role implements RoleInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="Users", mappedBy="roles")
     * 
     */
    private $users;	

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add users
     *
     * @param \mcsc\MissionBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\mcsc\MissionBundle\Entity\Users $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \mcsc\MissionBundle\Entity\User $users
     */
    public function removeUser(\mcsc\MissionBundle\Entity\Users $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set users
     *
     * @param \mcsc\MissionBundle\Entity\Users $users
     * @return Role
     */
    public function setUsers(\mcsc\MissionBundle\Entity\Users $users = null)
    {
        $this->users = $users;

        return $this;
    }
}

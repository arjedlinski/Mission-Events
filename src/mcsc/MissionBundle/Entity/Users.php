<?php

namespace mcsc\MissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Users
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Users implements UserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id_password;

    /**
     * @var string
     *
     * @ORM\Column(name="id_user", type="string", length=255)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="isActive", type="string", length=255)
     */
    private $isActive;
    
    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     *
     */
    private $roles;
    
    /**
     * @var string
     *
     * @ORM\Column(name="missionNumber", type="integer", length=255)
     */
    private $missionNumber;
    
    /**
     * @var string
     *
     * @ORM\Column(name="slotsMissionNumber", type="integer", length=255)
     */
    private $slotsMissionNumber;
    
    /**
     * @ORM\OneToMany(targetEntity="Logs", mappedBy="userlog", cascade={"all"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $userlog;
    
    public function __construct()
    {
    	$this->roles = new ArrayCollection();
    }
    
	
    /**
     * Get id_password
     *
     * @return integer
     */
    public function getIdPassword()
    {
    	return $this->id_password;
    }
    
    /**
     * Set idUser
     *
     * @param string $idUser
     * @return Users
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return string 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set isActive
     *
     * @param string $isActive
     * @return Users
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return string 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    
	/** (non-PHPdoc)
	 * @see \Symfony\Component\Security\Core\User\UserInterface::getRoles()
	 */
	public function getRoles() {
		
		return $this->roles->toArray();

	}

	/** (non-PHPdoc)
	 * @see \Symfony\Component\Security\Core\User\UserInterface::getSalt()
	 */
	public function getSalt() {
		// TODO: Auto-generated method stub

	}

	/** (non-PHPdoc)
	 * @see \Symfony\Component\Security\Core\User\UserInterface::eraseCredentials()
	 */
	public function eraseCredentials() {
		// TODO: Auto-generated method stub

	}
	/**
	 * @see \Serializable::serialize()
	 */
	public function serialize()
	{
		return serialize(array(
				$this->id_password,
				$this->username,
				$this->password,
				// see section on salt below
				// $this->salt,
		));
	}
	
	/**
	 * @see \Serializable::unserialize()
	 */
	public function unserialize($serialized)
	{
		list (
				$this->id_password,
				$this->username,
				$this->password,
				// see section on salt below
				// $this->salt
		) = unserialize($serialized);
	}


    

    /**
     * Add roles
     *
     * @param \mcsc\MissionBundle\Entity\Role $roles
     * @return Users
     */
    public function addRole(\mcsc\MissionBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \mcsc\MissionBundle\Entity\Role $roles
     */
    public function removeRole(\mcsc\MissionBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Set missionNumber
     *
     * @param integer $missionNumber
     * @return Users
     */
    public function setMissionNumber($missionNumber)
    {
        $this->missionNumber = $missionNumber;

        return $this;
    }

    /**
     * Get missionNumber
     *
     * @return integer 
     */
    public function getMissionNumber()
    {
        return $this->missionNumber;
    }

    /**
     * Add userlog
     *
     * @param \mcsc\MissionBundle\Entity\Logs $userlog
     * @return Users
     */
    public function addUserlog(\mcsc\MissionBundle\Entity\Logs $userlog)
    {
        $this->userlog[] = $userlog;

        return $this;
    }

    /**
     * Remove userlog
     *
     * @param \mcsc\MissionBundle\Entity\logs $userlog
     */
    public function removeUserlog(\mcsc\MissionBundle\Entity\Logs $userlog)
    {
        $this->userlog->removeElement($userlog);
    }

    /**
     * Get userlog
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserlog()
    {
        return $this->userlog;
    }

    /**
     * Set slotsMissionNumber
     *
     * @param integer $slotsMissionNumber
     * @return Users
     */
    public function setSlotsMissionNumber($slotsMissionNumber)
    {
        $this->slotsMissionNumber = $slotsMissionNumber;

        return $this;
    }

    /**
     * Get slotsMissionNumber
     *
     * @return integer 
     */
    public function getSlotsMissionNumber()
    {
        return $this->slotsMissionNumber;
    }
}

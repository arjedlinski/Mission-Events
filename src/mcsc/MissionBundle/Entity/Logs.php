<?php

namespace mcsc\MissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Logs
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Logs
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
     * @ORM\Column(name="logdate", type="datetime", length=255)
     */
    private $logdate;

    /**
     * @var string
     *
     * @ORM\Column(name="logmessage", type="string", length=255)
     */
    private $logmessage;
    
    /**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="userlog",cascade={"all"} )
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * @Assert\Valid()
     */
    protected $userlog;
	
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->userlog = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set logdate
     *
     * @param string $logdate
     * @return Logs
     */
    public function setLogdate($logdate)
    {
        $this->logdate = $logdate;

        return $this;
    }

    /**
     * Get logdate
     *
     * @return string 
     */
    public function getLogdate()
    {
        return $this->logdate;
    }

    /**
     * Set logmessage
     *
     * @param string $logmessage
     * @return Logs
     */
    public function setLogmessage($logmessage)
    {
        $this->logmessage = $logmessage;

        return $this;
    }

    /**
     * Get logmessage
     *
     * @return string 
     */
    public function getLogmessage()
    {
        return $this->logmessage;
    }

    /**
     * Set userlog
     *
     * @param \mcsc\MissionBundle\Entity\Users $userlog
     * @return Logs
     */
    public function setUserlog(\mcsc\MissionBundle\Entity\Users $userlog = null)
    {
        $this->userlog = $userlog;

        return $this;
    }

    /**
     * Get userlog
     *
     * @return \mcsc\MissionBundle\Entity\Users 
     */
    public function getUserlog()
    {
        return $this->userlog;
    }
}

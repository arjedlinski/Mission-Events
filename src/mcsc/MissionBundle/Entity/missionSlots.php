<?php

namespace mcsc\MissionBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Form;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * missionSlots
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class missionSlots
{
    /**
     * @var integer
     *
     * @ORM\Column(name="slot_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Assert\Valid()
     */
    protected $slot_id;

    /**
     * @var string
     *
     * @ORM\Column(name="function", type="string", length=40, nullable=true)
     * @Assert\Valid()
     */
    protected $function;

    /**
     * @var string
     *
     * @ORM\Column(name="player", type="string", length=40, nullable=true)
     * @Assert\Valid()
     */
    protected $player;

    
    /**
     * @ORM\ManyToOne(targetEntity="missionSection", inversedBy="missionSlots")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="section_id")
     * @Assert\Valid()
     */
    protected $missionSection;
    
    /**
     * @var string
     *
     * @ORM\Column(name="user_id", type="string", length=40, nullable=true)
     * @Assert\Valid()
     */
    protected $user_id;

    /**
     * Get slot_id
     *
     * @return integer 
     */
    public function getSlotId()
    {
        return $this->slot_id;
    }

    /**
     * Set function
     *
     * @param string $function
     * @return missionSlots
     */
    public function setFunction($function)
    {
        $this->function = $function;

        return $this;
    }

    /**
     * Get function
     *
     * @return string 
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * Set player
     *
     * @param string $player
     * @return missionSlots
     */
    public function setPlayer($player)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return string 
     */
    public function getPlayer()
    {
        return $this->player;
    }

  /**
     * Set missionSection
     *
     * @param \mcsc\MissionBundle\Entity\missionSection $missionSection
     * @return missionSlots
     */
    public function setMissionSection(\mcsc\MissionBundle\Entity\missionSection $missionSection = null)
    {
        $this->missionSection = $missionSection;

        return $this;
    }

    /**
     * Get missionSection
     *
     * @return \mcsc\MissionBundle\Entity\missionSection 
     */
    public function getMissionSection()
    {
        return $this->missionSection;
    }

    /**
     * Set user_id
     *
     * @param string $userId
     * @return missionSlots
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return string 
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}

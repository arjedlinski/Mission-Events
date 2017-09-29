<?php

namespace mcsc\MissionBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Form;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\Collection;

/**
 * missionSection
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class missionSection 
{
    /**
     * @var integer
     *
     * @ORM\Column(name="section_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $section_id;

    /**
     * @var string
     *
     * @ORM\Column(name="section_name", type="string", length=40)
     */
    protected $section_name;
	
    /**
     * @ORM\OneToMany(targetEntity="missionSlots", mappedBy="missionSection", cascade={"all"}, orphanRemoval=true )
     * @Assert\Valid()
     */
    protected $missionSlots;
    
    /**
     * @ORM\ManyToOne(targetEntity="mission", inversedBy="missionSection" )
     * @ORM\JoinColumn(name="mission_id", referencedColumnName="mission_id")
     * @Assert\Valid()
     */
    protected $missionSection;

    /**
     * Get section_id
     *
     * @return integer 
     */
    public function getSectionId()
    {
        return $this->section_id;
    }

    /**
     * Set section_name
     *
     * @param string $sectionName
     * @return missionSection
     */
    public function setSectionName($sectionName)
    {
        $this->section_name = $sectionName;

        return $this;
    }

    /**
     * Get section_name
     *
     * @return string 
     */
    public function getSectionName()
    {
        return $this->section_name;
    }

    /**
     * Set missionSlots
     *
     * @param \mcsc\MissionBundle\Entity\missionSlots $missionSlots
     * @return missionSection
     */
    public function addMissionSlots(\mcsc\MissionBundle\Entity\missionSlots $missionSlots)
    {

        $this->missionSlots[] = $missionSlots;
    	 
    	return $this;
    }
    /**
     * Set missionSlots
     *
     * @param \mcsc\MissionBundle\Entity\missionSlots $missionSlots
     * @return missionSection
     */
    public function addMissionSlot(\mcsc\MissionBundle\Entity\missionSlots $missionSlots)
    {
    
    	$missionSlots->setMissionSection($this);
        $this->missionSlots->add($missionSlots);
        return $this;
    }
    

    /**
     * Remove missionSlots
     *
     * @param \mcsc\MissionBundle\Entity\missionSlots $missionSlots
     */
    public function removeMissionSlot(\mcsc\MissionBundle\Entity\missionSlots $missionSlots)
    {
        $this->missionSlots->removeElement($missionSlots);
    }

    /**
     * Get missionSlots
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMissionSlots()
    {
        return $this->missionSlots;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->missionSlots = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set missionSection
     *
     * @param \mcsc\MissionBundle\Entity\mission $missionSection
     * @return missionSection
     */
    public function setMissionSection(\mcsc\MissionBundle\Entity\mission $missionSection = null)
    {
        $this->missionSection = $missionSection;

        return $this;
    }

    /**
     * Get missionSection
     *
     * @return \mcsc\MissionBundle\Entity\mission 
     */
    public function getMissionSection()
    {
        return $this->missionSection;
    }
}

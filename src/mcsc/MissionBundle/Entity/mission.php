<?php

namespace mcsc\MissionBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Form;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Collections\Collection;
use mcsc\MissionBundle\Form\Type\MissionType;
use mcsc;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
/**
 * mission
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class mission
{
	public static function loadValidatorMetadata(ClassMetadata $metadata)
	{
		$metadata->addConstraint(new Assert\Callback('validate'));
	}
	
	public function validate(ExecutionContextInterface $context)
	{
		if ($this->getStartDate()->getTimestamp() < time())
		{
			$context->buildViolation('Niepoprawna data startu.')
			->atPath('startdate')
			->addViolation();
	
		}
		if ($this->getStartDate() < $this->getDeadline())
		{
			$context->buildViolation('Koniec zapisów musi być ustawiony przed rozpoczęciem misji.')
			->atPath('deadline')
			->addViolation();

		}

	}		

	
    /**
     * @var integer
     *
     * @ORM\Column(name="mission_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
     protected $mission_id;

    /**
     * @ORM\ManyToOne(targetEntity="game", inversedBy="mission")
     * @ORM\JoinColumn(name="game", referencedColumnName="id")
     */
     protected $game;
     
     /**
      * @ORM\ManyToOne(targetEntity="mods", inversedBy="mission")
      * @ORM\JoinColumn(name="type", referencedColumnName="id")
      */
     
     protected $mods;
      
     /**
      * @ORM\ManyToOne(targetEntity="type", inversedBy="mission")
      * @ORM\JoinColumn(name="type", referencedColumnName="id")
      */
     protected $type;
     
     /**
      *
      *
      * @ORM\Column(name="name", type="string", length=30, nullable=true)
      */
     private $name;
     
     /**
     * @var string
     *
     * @ORM\Column(name="mods", type="string", length=30, nullable=true)
     */
     
     /**
      * @var string
      *
      * @ORM\Column(name="start_date", type="datetime", nullable=true)
      */

     private $start_date;
     
     /**
     * @var string
     *
     * @ORM\Column(name="deadline", type="datetime", nullable=true)
     */
     private $deadline;

    /**
     * @var string
     *
     * @ORM\Column(name="number_of_slots", type="integer", nullable=true)
     */
     private $number_of_slots;

    /**
     * @var string
     *
     * @ORM\Column(name="map", type="string", length=30, nullable=true)
     */
     private $map;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=30, nullable=true)
     */
     private $author;

    /**
     * @var array
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
      private $description;
      
    /**
     * @var string
     *
     * @ORM\Column(name="author_id", type="string", length=255, nullable=true)
     */
      private $author_id;	
    
   /**
     * @var string
     *
     * @ORM\Column(name="free_slots", type="string", length=255, nullable=true)
     */
      private $free_slots;
      
     /**
      * @ORM\OneToMany(targetEntity="missionSection", mappedBy="missionSection", cascade={"all"}, orphanRemoval=true)
      * @Assert\Valid()
      */
      
     protected $missionSection;
     

	 

	
	 /**
     * Get mission_id
     *
     * @return integer 
     */
    public function getmission_id()
    {
        return $this->mission_id;
    }

    /**
     * Set game
     *
     * @param string $game
     * @return mission
     */
    public function setGame($game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return string 
     */
    public function getGame()
    {
        return $this->game;
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return mission
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
     * Set mod
     *
     * @param string $mod
     * @return mission
     */
    public function setMod($mod)
    {
        $this->mod = $mod;

        return $this;
    }

    /**
     * Get mod
     *
     * @return string 
     */
    public function getMod()
    {
        return $this->mod;
    }

    /**
     * Set start_date
     *
     * @param \DateTime $startDate
     * @return mission
     */
    public function setStartDate($startDate)
    {
        $this->start_date = $startDate;

        return $this;
    }

    /**
     * Get start_date
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set mission_type
     *
     * @param string $missionType
     * @return mission
     */
    public function setMissionType($missionType)
    {
        $this->mission_type = $missionType;

        return $this;
    }

    /**
     * Get mission_type
     *
     * @return string 
     */
    public function getMissionType()
    {
        return $this->mission_type;
    }

    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     * @return mission
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime 
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set number_of_slots
     *
     * @param integer $numberOfSlots
     * @return mission
     */
    public function setNumberOfSlots($numberOfSlots)
    {
        $this->number_of_slots = $numberOfSlots;

        return $this;
    }

    /**
     * Get number_of_slots
     *
     * @return integer 
     */
    public function getNumberOfSlots()
    {
        return $this->number_of_slots;
    }

    /**
     * Set map
     *
     * @param string $map
     * @return mission
     */
    public function setMap($map)
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Get map
     *
     * @return string 
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return mission
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return mission
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->missionSlots = new \Doctrine\Common\Collections\ArrayCollection();
        $this->missionSection = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
   /**
     * Set mods
     *
     * @param string $mods
     * @return mission
     */
    public function setMods($mods)
    {
        $this->mods = $mods;

        return $this;
    }

    /**
     * Get mods
     *
     * @return string 
     */
    public function getMods()
    {
        return $this->mods;
    }

    /**
     * Add missionSection
     *
     * @param \mcsc\MissionBundle\Entity\missionSection $missionSection
     * @return mission
     */
    public function addMissionSection(\mcsc\MissionBundle\Entity\missionSection $missionSection)
    {

        $missionSection->setMissionSection($this);
        $this->missionSection->add($missionSection);
        return $this;
    }
    
    /**
     * Add missionSection
     *
     * @param \mcsc\MissionBundle\Entity\missionSection $missionSection
     * @return mission
     */
    public function addMissionSections(\mcsc\MissionBundle\Entity\missionSection $missionSection)
    {
    
    	$this->missionSection[] = $missionSection;
    	 
    	return $this;
    }

    /**
     * Remove missionSection
     *
     * @param \mcsc\MissionBundle\Entity\missionSection $missionSection
     */
    public function removeMissionSection(\mcsc\MissionBundle\Entity\missionSection $missionSection)
    {
        $this->missionSection->removeElement($missionSection);
    }

    /**
     * Get missionSection
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMissionSection()
    {
        return $this->missionSection;
    }

    /**
     * Get mission_id
     *
     * @return integer 
     */
    public function getMissionId()
    {
        return $this->mission_id;
    }

    /**
     * Set author_id
     *
     * @param string $authorId
     * @return mission
     */
    public function setAuthorId($authorId)
    {
        $this->author_id = $authorId;

        return $this;
    }

    /**
     * Get author_id
     *
     * @return string 
     */
    public function getAuthorId()
    {
        return $this->author_id;
    }

    /**
     * Set free_slots
     *
     * @param string $freeSlots
     * @return mission
     */
    public function setFreeSlots($freeSlots)
    {
        $this->free_slots = $freeSlots;

        return $this;
    }

    /**
     * Get free_slots
     *
     * @return string 
     */
    public function getFreeSlots()
    {
        return $this->free_slots;
    }

    /**
     * Set type
     *
     * @param \mcsc\MissionBundle\Entity\type $type
     * @return mission
     */
    public function setType(\mcsc\MissionBundle\Entity\type $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \mcsc\MissionBundle\Entity\type 
     */
    public function getType()
    {
        return $this->type;
    }
}

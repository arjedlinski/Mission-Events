<?php
namespace mcsc\MissionBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="game")
 */
class game
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;
    

    /**
     * @ORM\OneToMany(targetEntity="mission", mappedBy="game")
     */
    protected $mission;
    
    public function __construct()
    {
    	$this->mission = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return game
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
     * Add mission
     *
     * @param \mcsc\MissionBundle\Entity\mission $mission
     * @return game
     */
    public function addMission(\mcsc\MissionBundle\Entity\mission $mission)
    {
        $this->mission[] = $mission;

        return $this;
    }

    /**
     * Remove mission
     *
     * @param \mcsc\MissionBundle\Entity\mission $mission
     */
    public function removeMission(\mcsc\MissionBundle\Entity\mission $mission)
    {
        $this->mission->removeElement($mission);
    }

    /**
     * Get mission
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMission()
    {
        return $this->mission;
    }
}

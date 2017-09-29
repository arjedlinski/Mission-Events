<?php 
namespace mcsc\MissionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use mcsc\MissionBundle\Entity\missionSlots;
use mcsc\MissionBundle\Entity\mission;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('function', 'text', array('label' => 'Funkcja'));
    }
    public function getName()
    {
    	
        return 'MissionType';
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    			'data_class' => 'mcsc\MissionBundle\Entity\missionSlots',
    			'cascade_validation' => true,
    			
    	
    	));
    }
  
}




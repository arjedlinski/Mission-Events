<?php 
namespace mcsc\MissionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use mcsc\MissionBundle\Entity\missionSlots;
use mcsc\MissionBundle\Entity\mission;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

class MissionSection extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('section_name','text', array('label' => 'Nazwa sekcji','by_reference' => false))
       		->add('missionSlots','collection',array(
        		'type' => new MissionType(),
        		'allow_add' => true,
       			'allow_delete' => true,
       			'label' => false,
       			'prototype' => true,
       			'options' => array('label' => false),
       			'attr' => array(
       						'data-widget-controls' => 'true'
       				),
        		'by_reference' => false,
       			'cascade_validation' => true,
        ));
    }
    public function getName()
    {
        return 'MissionSection';
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	
    	$resolver->setDefaults(array(
    			'data_class' => 'mcsc\MissionBundle\Entity\missionSection',
    			'cascade_validation' => true,
    	));
    }
}




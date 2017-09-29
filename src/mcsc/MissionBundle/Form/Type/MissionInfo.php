<?php 
namespace mcsc\MissionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MissionInfo extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('game', 'text')
            ->add('game', 'entity', array(
            		'class' => 'mcscMissionBundle:game',
            		'property' => 'name'))
            ->add('type', 'entity', array(
            		'class' => 'mcscMissionBundle:type',
            		'property' => 'name'))
            ->add('mods', 'entity', array(
            		'class' => 'mcscMissionBundle:mods',
            		'property' => 'name'))
            ->add('name', 'text')
            ->add('deadline','datetime')
            ->add('startdate','datetime')
            ->add('map', 'text')
            ->add('description', 'text')


//			Dynamiczne generowanie formularza na podstawie już wprowadzony danych
//			Może się kiedyś przydać.
//           ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $form_event) {
//             		$event = $form_event->getData();
//             		$form = $form_event->getForm();
//             		dump($event);
//             		if ($event['game'] == "1")
//             		{
//             			$form->add('mods', 'text');
//             			$form->add('mission_type', 'textarea');
//             		}
//             		$form_event->setData($event);
//             })
            ->add('missionSection','collection',array(
            		'type' => new MissionSection(),
            		'allow_add' => true,
            		'allow_delete' => true,
            		'label' => false,
            		'prototype' => true,
            		'by_reference' => false,
            		'cascade_validation' => true));
            

            
    }
    public function getName()
    {
        return 'MissionInfo';
    }

}





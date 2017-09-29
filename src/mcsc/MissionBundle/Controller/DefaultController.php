<?php

namespace mcsc\MissionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('mcscMissionBundle:Default:index.html.twig', array('name' => $name));
    }
}

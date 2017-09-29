<?php

namespace Misja\misjaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisjamisjaBundle:Default:index.html.twig');
    }
}

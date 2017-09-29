<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Test;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    
	public function createAction()
	{
	    $product = new Test();
	    $product->setName('A Foo Bar');
	    $product->setPrice('19.99');
	    $product->setDescription('Lorem ipsum dolor');
	
	    $em = $this->getDoctrine()->getManager();
	
	    $em->persist($product);
	    $em->flush();
	
	    return new Response('Created product id '.$product->getId());
	}
}

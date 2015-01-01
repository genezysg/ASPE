<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
    public function createAction(){
    	$product = new Product();
    	$product->setName("Sabao");
    	$product->setPrice("4.50");
    	$product->setDescription("Sabao para lavar roupa");
    	
    	
    }
}

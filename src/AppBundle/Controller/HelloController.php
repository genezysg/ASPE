<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HelloController extends Controller
{
	public function indexAction($name)
	{
// 		return new Response('<html><body>Hello '.$name."!</body></html>");
		
		return $this->render('default/index.html.twig', array('name' => $name));

// 		$response = $this->forward('AppBundle:Default:index', array(
// 				'name' => $name,
// 		));
// 		return $response;
// 		echo "Hello ".$name;
	}
}